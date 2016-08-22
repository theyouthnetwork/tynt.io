(function(){
    'use strict';

    require('dotenv').config();
    
    var exec = require('child_process').exec;
    var crypto = require('crypto');
    
    var http = require('http');
    var server = http.createServer(function(req, res){
        if (req.method === 'POST'){
            var payload = '';
            req.on('data', function(chunk) {
                payload += chunk.toString();
                console.log(payload);
            });
            req.on('end', function() {
                
                // Check the sha1 in x-hub-signature against payload hashed with process.env.GITHUB_WEBHOOK_SECRET
                if (process.env.GITHUB_WEBHOOK_SECRET){
                    var sha1Hash = crypto.createHmac('sha1', process.env.GITHUB_WEBHOOK_SECRET).update(payload).digest('hex');
                    if (('sha1=' + sha1Hash) !== req.headers['x-hub-signature']){
                        res.writeHead(403, "Unauthorized", {'Content-Type': 'text/plain'});
                        res.end();
                    }
                }
                
                payload = JSON.parse(payload);
                if (payload.ref == process.env.LISTEN_REF_NAME){
                    exec('./pull-repo.sh', function(error, stdout, stderr){
                        if (error) {
                            console.error('exec error: ' + error);
                            return;
                        }
                    });
                }
                
                res.writeHead(200, "OK", {'Content-Type': 'text/plain'});
                res.end();
                
            });
        }
        else {
            res.writeHead(405, "Method not supported", {'Content-Type': 'text/plain'});
            res.end();
        }
    });
    
    var port = process.env.GITHUB_WEBHOOK_LISTEN_PORT || 8090;
    server.listen(port);
        
}());

#!/usr/bin/env bash

OWNER='tynt'

cd /var/www
if [ -d html ]; then
    sudo chown -R $OWNER:$OWNER html
    sudo chmod g+w html
fi
#!/usr/bin/env bash

DIRNAME=`dirname $0` 
CWD=`pwd`
ME=`whoami`

LISTENER_HOME='/opt/listeners'

GHL_DIRNAME='github-webhook-listener'
GHL_SRC_PATH="$DIRNAME/../src/$GHL_DIRNAME"
GHL_TARGET_PATH="$LISTENER_HOME/$GHL_DIRNAME"


## Expecting the linux box not to have drop of nodejs on it

echo -ne Installing NPM and PM2... 
curl -sL https://deb.nodesource.com/setup_4.x | sudo bash -
sudo apt-get install -y nodejs
sudo npm install pm2 -g
echo Done.

echo -ne Preparing listener home directory... 
sudo mkdir -p "$LISTENER_HOME"
sudo chown -R $ME:$ME "$LISTENER_HOME"
echo Done.

## So by now, this (girl has no name) should be able to write to the listener folder
echo -ne Installing GitHub webhook listener... 
cp -R "$GHL_SRC_PATH" "$LISTENER_HOME"
cd "$GHL_TARGET_PATH"
npm install
echo Done.

echo -ne Running listener via PM2... 
$GHL_TARGET_PATH/up.sh
cd "$CWD"
echo Done.

## Apache tinkerations
## Expect debian-like apache 
DEFAULT_FILE='000-default.conf'
DEFAULT_FILE_PATH="/etc/apache2/sites-available/$DEFAULT_FILE"
RPROXY_FILE='github-listener-reverse-proxy.conf'
RPROXY_FILE_PATH="$DIRNAME/../conf/$RPROXY_FILE"
APACHE_CONF='/etc/apache2/'
APACHE_MODS_DROPIN_DIRNAME='mods-available'
APACHE_MODS_ENABLED_DIRNAME='mods-enabled'
APACHE_SITES_DROPIN_DIRNAME='sites-available'
APACHE_SITES_ENABLED_DIRNAME='sites-enabled'

echo -ne Enable mod_proxy on Apache... 
sudo a2enmod proxy
sudo a2enmod proxy_http
#cd "$APACHE_CONF/$APACHE_MODS_ENABLED_DIRNAME"
#sudo ln -s "../$APACHE_MODS_DROPIN_DIRNAME/proxy.load" proxy.load
echo Done.

echo -ne Drop in our reverse proxy, include it from main file... 
sudo sed -i.bak "s/<VirtualHost.*>/&\nInclude sites-available\/${RPROXY_FILE}/" "$DEFAULT_FILE_PATH"
sudo rm -f "${DEFAULT_FILE_PATH}.bak"
sudo cp "$RPROXY_FILE_PATH" "$APACHE_CONF/$APACHE_SITES_DROPIN_DIRNAME"
echo Done.

echo -ne Restart apache... 
sudo apachectl graceful
echo Done.

echo All done.
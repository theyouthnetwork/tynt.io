#!/usr/bin/env bash

DIRNAME=`dirname $0`
WPLOCKDOWN_FILE='tynt-wordpress-harden.conf'
WPLOCKDOWN_FILE_PATH="$DIRNAME/../conf/$WPLOCKDOWN_FILE"

## Expect debian-like apache 
APACHE_CONF='/etc/apache2/'
APACHE_CONF_DROPIN_DIRNAME='conf-available'
APACHE_CONF_ENABLED_DIRNAME='conf-enabled'

sudo cp "$WPLOCKDOWN_FILE_PATH" "$APACHE_CONF/$APACHE_CONF_DROPIN_DIRNAME"

## Symlink it to conf-enabled to make it work
cd "$APACHE_CONF/$APACHE_CONF_ENABLED_DIRNAME"
sudo ln -s "../$APACHE_CONF_DROPIN_DIRNAME/$WPLOCKDOWN_FILE" "$WPLOCKDOWN_FILE"
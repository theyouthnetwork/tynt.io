#!/usr/bin/env bash

echo 'Installing WPMDBP plugins';

if [ -f composer.json ]; then
	## We expect licence keys to have been populated in the env immediately prior
	echo Using WPMDBP Licence Key: $WPMDBP_LICENCE_KEY
	echo Using WPMDBP Site Domain: $WPMDBP_SITE_DOMAIN
	sed -i.bak "s/<LICENCE_KEY>/${WPMDBP_LICENCE_KEY}/" composer.json && rm composer.json.bak
	sed -i.bak "s/<SITE_DOMAIN>/${WPMDBP_SITE_DOMAIN}/" composer.json && rm composer.json.bak
	echo Installing via Composer
	composer install 
	git checkout -- composer.json
else
	echo composer.json not found, this script will not run.
fi

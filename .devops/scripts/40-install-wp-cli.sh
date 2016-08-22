#!/usr/bin/env bash

## https://wp-cli.org/#installing

cd /tmp
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar

## Working test
php wp-cli.phar --info

chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp

wp --info


#!/usr/bin/env bash

sudo apt-get -y update
sudo apt-get -y install curl php5-cli git
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
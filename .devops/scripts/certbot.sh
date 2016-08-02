#!/usr/bin/env bash

mkdir -p ~/certbot
cd ~/certbot

wget https://dl.eff.org/certbot-auto
chmod a+x certbot-auto

## First run
./certbot-auto --apache -t -m tynt@westpac.com.au --agree-tos --keep-until-expiring --uir --redirect -d stage.tynt.io

## Crontab a job to check 
crontab -l > currentcron
echo "16 2,14 * * * ~/certbot-auto renew --quiet --no-self-upgrade" >> currentcron
crontab currentcron
rm currentcron


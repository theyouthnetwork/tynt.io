#!/usr/bin/env bash

if [ $# -eq 0 ]; then
  echo ERROR: expect argument 1 to be the domain name
  exit 1
fi

mkdir -p ~/certbot
cd ~/certbot

wget https://dl.eff.org/certbot-auto
chmod a+x certbot-auto

## Jump into apache config, add a servername for stage
## First run
./certbot-auto --apache -t --no-self-upgrade -m tynt@westpac.com.au --agree-tos --keep-until-expiring --uir --redirect -d $1

## Crontab a job to check 
crontab -l > currentcron
echo "16 2,14 * * * ~/certbot-auto renew --quiet --no-self-upgrade" >> currentcron
crontab currentcron
rm currentcron


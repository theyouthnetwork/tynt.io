#!/usr/bin/env bash

. ~/.bash_profile
GITHUB_WEBHOOK_LISTENER_PORT=8090 pm2 start listener.js --name "webhook-listener"

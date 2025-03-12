#!/bin/bash

ENV_FILE=/var/www/.env

a2enmod rewrite
service apache2 start
if [ -f $ENV_FILE ]; then
    rm $ENV_FILE
fi

# symfony server:start --port=8002

symfony server:start --port=8002 --allow-all-ip

# while : ; do sleep 1000; done

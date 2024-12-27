#!/bin/bash

ENV_FILE=/var/www/.env

a2enmod rewrite
service apache2 start
if [ -f $ENV_FILE ]; then
    rm $ENV_FILE
fi
cp /var/www/.env.sample /var/www/.env
while : ; do sleep 1000; done

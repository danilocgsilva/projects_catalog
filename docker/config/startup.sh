#!/bin/bash

a2enmod rewrite
service apache2 start
cp /var/www/.env.sample /var/www/.env
while : ; do sleep 1000; done

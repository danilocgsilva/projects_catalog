#!/bin/bash

ENV_FILE=/var/www/.env

a2enmod rewrite
service apache2 start
if [ -f $ENV_FILE ]; then
    rm $ENV_FILE
fi
cp /var/www/.env.sample $ENV_FILE
sed -i s/\<USER\>/root/g $ENV_FILE
sed -i s/\<PASSWORD\>/projectscatalogstrongpassword/g $ENV_FILE
sed -i s/\<DNS\>/projects_catalog_dev_db/g $ENV_FILE
sed -i s/\<DATABASENAME\>/projects_catalog/g $ENV_FILE
sed -i s/\<PORT\>/3306/g $ENV_FILE
while : ; do sleep 1000; done

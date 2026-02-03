#!/bin/bash

set -e

docker compose up -d --build

echo Awaiting 20 seconds to startup the database...
sleep 20

docker exec -i projects_catalog_dev_db bash <<EOF
echo [client] >> /root/.my.cnf
echo password=projectscatalogstrongpassword >> /root/.my.cnf
EOF

docker exec -i projects_catalog_dev_db bash <<EOF
mysql -e "CREATE DATABASE IF NOT EXISTS projects_catalog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
EOF

docker exec -i projects_catalog_dev bash <<EOF
cp .env.sample .env
sed -i 's/<USER>/root/g' .env
sed -i 's/<PASSWORD>/projectscatalogstrongpassword/g' .env
sed -i 's/<DNS>/projects_catalog_dev_db/g' .env
sed -i 's/<PORT>/3306/g' .env
sed -i 's/<DATABASENAME>/projects_catalog/g' .env
composer install
php bin/console --no-interaction doctrine:migrations:migrate
EOF

echo The build is ready. Don't need to run it again. Now, you can run inside the container "symfony server:start --port=8002 --allow-all-ip". Just ensure that the container is up. We have a script just to make it start: "start_server.sh"


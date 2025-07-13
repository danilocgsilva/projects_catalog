#!/bin/bash

docker exec -it projects_catalog_dev symfony server:start --port=8002 --allow-all-ip

# Projects Catralog

Manages your projects

## Project Entities

* Environment: Where the projects can exists. Example: computer A and computer B.
* Project: The project storing.
* DatabaseCredential: List of database credential. Usefull for fast backup.
* RepositoryAddress: Repository addresses.

## Frontend compilation

This project uses the Asset Mapper and Bootstrap. On any change in frontend code, don't you forget to compile assets. Do this with:
```
php bin/console asset-map:compile
```

## Build

```
composer install
```

## Running during environment

Good to know that in the Docker receipt, the script `startup.sh` is executed on the container start. The end of this script, that are a symfony script that starts a server on a port 8002.

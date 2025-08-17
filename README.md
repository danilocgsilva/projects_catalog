# Projects Catralog

Manages your projects

## Running project

This project ships the docker environment receipt. Look to the `docker` folder to check the environment receipt. Also there you can start the environment wirh the command `docker compose up -d --build`.

After that, you need to start the server. For this, I have the `start_server.sh` that can be executed from the host machine.

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
There's a script in the `composer.json` to do this task: `compile-front`.

## Build

```
composer install
```

## Running during environment

In the project root you can find the `start_docker.sh` and `start_server.sh`. This scritps are shortcuts to start the development environment. First, just run `start_docker.sh` and await start the environment. Then, run `start_server.sh`. This serves the application in the port 8002.

## WARNING about data encryption (storing password)

The application holds information from password for database (an pontentially other sets of data as well in the future). It is important no know that this information is encrypted in the database, and that the package responsible for this is the `doctrineencryptbundle/doctrine-encrypt-bundle`. Note that if the application needs to work in different environments, special care must be taken on this subject. May you should decrypt the table fields before transfering data between environments (the package documentation gives enough information on this), or may need to manage the encryption key accordingly.

By default, the configurations for this package is in `config/services.yaml`, in the key `ambta_doctrine_encrypt`.

## The feature to make a database backup

Note as well that for the database backup feature, it is handled by the Symfony Messages package. The packages responsible to this are `symfony/messenger`, and the `symfony/doctrine-messenger` (the latter serves as a conector between the doctrine and the messager package). It is important to know because depending on the package configuration, this task may be performed synchronously or assynchronously. Just check the configuration in `config/packages/messenger.yaml`. If the configurations is setted to assynchronously processing, after requesting for a database backup, the action is performed assynchronously. A *worker* must be running and monitoring the requests. For development environments, you must run the following command: `php bin/console messenger:consume async --limit=10`. This will process any requests in the queue.

If you want to change from *async* to *sync* or vice-versa, you may change values both from *transports* and *routing* messeger branch configuration.

The class responsible for the database backup is the `MakeDatabaseBackupService`.

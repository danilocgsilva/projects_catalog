<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\DatabaseCredential;
use PDO;
use PDOException;

class TestDatabase
{
    public static function test(
        DatabaseCredential|array $databaseCredential
    ): bool
    {
        if (is_array($databaseCredential)) {
            return false;
        }
        
        try {
            $dns = "mysql:host=" . $databaseCredential->getHost() . ";dbname=" . $databaseCredential->getDatabaseName();
            new PDO(
                $dns,
                $databaseCredential->getUser(),
                $databaseCredential->getPassword(),
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

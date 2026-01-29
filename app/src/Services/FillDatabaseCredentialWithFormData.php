<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\DatabaseCredential;

class FillDatabaseCredentialWithFormData
{
    public static function fill(
        DatabaseCredential $databaseCredentialWithData,
        DatabaseCredential $databaseCredentialToFill
    ): void
    {
        $databaseCredentialToFill->setName($databaseCredentialWithData->name ?? null);
        $databaseCredentialToFill->setDatabaseName($databaseCredentialWithData->databaseName ?? null);
        $databaseCredentialToFill->setHost($databaseCredentialWithData->host ?? null);
        $databaseCredentialToFill->setPort($databaseCredentialWithData->port ?? null);
        $databaseCredentialToFill->setUser($databaseCredentialWithData->user ?? null);
        $databaseCredentialToFill->setPassword($databaseCredentialWithData->password ?? null);
    }
}

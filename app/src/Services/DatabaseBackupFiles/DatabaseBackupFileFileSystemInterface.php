<?php

declare(strict_types=1);

namespace App\Services\DatabaseBackupFiles;

interface DatabaseBackupFileFileSystemInterface
{
    public function exists(string $exists): bool;

    public function getFileSystemAddressPath(string $path): string;

    public function getFileContent(string $path): string;
}

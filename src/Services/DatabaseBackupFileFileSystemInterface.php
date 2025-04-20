<?php

declare(strict_types=1);

namespace App\Services;

interface DatabaseBackupFileFileSystemInterface
{
    public function exists(string $exists): bool;

    public function getFileSystemAddressPath(string $path): string;
}

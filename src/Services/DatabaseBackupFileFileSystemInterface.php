<?php

declare(strict_types=1);

interface DatabaseBackupFileFileSystemInterface
{
    public function exists(string $exists): bool;

    public function getFileSystemAddressPath(string $path): string;
}

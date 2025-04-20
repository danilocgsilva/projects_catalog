<?php

declare(strict_types=1);

class ComputerFileSystemService implements DatabaseBackupFileFileSystemInterface
{
    private const BASE_PATH = __DIR__ . "../../var/database_backups/";
    
    public function exists(string $path): bool
    {
        return file_exists($this->getFileSystemAddressPath($path));
    }

    public function getFileSystemAddressPath(string $path): string
    {
        return self::BASE_PATH . $path;
    }
}

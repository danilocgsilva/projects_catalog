<?php

declare(strict_types=1);

namespace App\Services;

use Symfony\Component\Filesystem\Filesystem;

class S3FileSystemService implements DatabaseBackupFileFileSystemInterface
{
    private const BASE_PATH = "s3://my-bucket/database_backups/";

    public function __construct()
    {
    }
    
    public function exists(string $path): bool
    {
        return $this->fs->exists($this->getFileSystemAddressPath($path));
    }

    public function getFileSystemAddressPath(string $path): string
    {
        return self::BASE_PATH . $path;
    }

    public function write(string $path, string $content): void
    {
        
    }
}

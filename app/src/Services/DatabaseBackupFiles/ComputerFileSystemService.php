<?php

declare(strict_types=1);

namespace App\Services\DatabaseBackupFiles;

use Symfony\Component\Filesystem\Filesystem;

class ComputerFileSystemService implements DatabaseBackupFileFileSystemInterface
{
    private const BASE_PATH = __DIR__ . "/../../var/database_backups/";

    public function __construct(private Filesystem $fs)
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

    public function write(string $fileName, string $content): void
    {
        $this->fs->dumpFile($this->getFileSystemAddressPath($fileName), $content);
    }

    public function getFileContent(string $path): string
    {
        return file_get_contents($this->getFileSystemAddressPath($path));
    }
}

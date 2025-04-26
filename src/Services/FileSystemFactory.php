<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\ComputerFileSystemService;
use App\Services\S3FileSystemService;
use InvalidArgumentException;
use App\Services\DatabaseBackupFileFileSystemInterface;
use Symfony\Component\Filesystem\Filesystem;

class FileSystemFactory
{
    public function __construct(
        private string $filesystemHandler
    ) {}

    public function get(): DatabaseBackupFileFileSystemInterface
    {
        return match ($this->filesystemHandler) {
            's3' => new S3FileSystemService(),
            'local' => new ComputerFileSystemService(new Filesystem()),
            default => throw new InvalidArgumentException('Invalid filesystem handler'),
        };
    }
}

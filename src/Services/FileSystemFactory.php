<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\ComputerFileSystemService;
use App\Services\S3FileSystemService;
use InvalidArgumentException;
use App\Services\DatabaseBackupFileFileSystemInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileSystemFactory
{
    public function __construct(
        private string $filesystemHandler,
        private ContainerInterface $container
    ) {}

    public function get(): DatabaseBackupFileFileSystemInterface
    {
        return match ($this->filesystemHandler) {
            's3' => $this->container->get(S3FileSystemService::class),
            'local' => new ComputerFileSystemService(new Filesystem()),
            default => throw new InvalidArgumentException('Invalid filesystem handler'),
        };
    }
}

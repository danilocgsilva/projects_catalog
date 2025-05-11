<?php

declare(strict_types=1);

namespace App\Services\DatabaseBackupFiles;

use App\Services\DatabaseBackupFiles\S3FileSystemService;
use App\Services\DatabaseBackupFiles\ComputerFileSystemService;

class BringToLocal
{
    private string $localPath;

    public function __construct(
        private S3FileSystemService $s3FileSystemService,
        private ComputerFileSystemService $computerFileSystemService
    ) {
    }

    public function toLocal(string $path): void
    {
        $s3FileContent = $this->s3FileSystemService->getFileContent($path);
        $this->computerFileSystemService->write($path, $s3FileContent);
        $this->localPath = $this->computerFileSystemService->getFileSystemAddressPath($path);
    }

    public function getLocalPath(): string
    {
        return $this->localPath;
    }
}

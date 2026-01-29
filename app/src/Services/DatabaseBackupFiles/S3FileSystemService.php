<?php

declare(strict_types=1);

namespace App\Services\DatabaseBackupFiles;

use Aws\S3\S3Client;

class S3FileSystemService implements DatabaseBackupFileFileSystemInterface
{
    private const ADDRESS = "database_backups";

    private S3Client $s3Client;

    public function __construct(
        string $s3ClientId,
        string $s3ClientSecret,
        private string $s3BucketName
    ) {
        $this->s3Client = new S3Client([
            'region' => 'us-east-1',
            'version' => '2006-03-01',
            'credentials' => [
                'key'    => $s3ClientId,
                'secret' => $s3ClientSecret
            ]
        ]);
    }
    
    public function exists(string $path): bool
    {
        $result = $this->s3Client->doesObjectExist(
            $this->s3BucketName, 
            self::ADDRESS . '/' . $path
        );
        if ($result) {
            return true;
        }

        return false;
    }

    public function getFileSystemAddressPath(string $path): string
    {
        return self::ADDRESS;
    }

    public function write(string $path, string $content): void
    {
        $result = $this->s3Client->putObject([
            'Bucket' => $this->s3BucketName,
            'Key'    => self::ADDRESS . '/' . $path,
            'Body'   => $content
        ]);
    }

    public function delete(string $path): void
    {
        $this->s3Client->deleteObject([
            'Bucket' => $this->s3BucketName,
            'Key'    => self::ADDRESS . '/' . $path
        ]);
    }

    public function getFileContent(string $path): string
    {
        $result = $this->s3Client->getObject([
            'Bucket' => $this->s3BucketName,
            'Key'    => self::ADDRESS . '/' . $path
        ]);

        return (string) $result['Body'];
    }
}

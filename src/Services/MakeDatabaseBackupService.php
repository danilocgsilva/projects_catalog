<?php

declare(strict_types=1);

namespace App\Services;

use App\Repository\DatabaseCredentialRepository;
use App\Entity\DatabaseBackupFile;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Path;
use Symfony\Component\HttpKernel\KernelInterface;

class MakeDatabaseBackupService
{
    public function __construct(
        private DatabaseCredentialRepository $databaseCredentialRepository,
        private EntityManagerInterface $entityManager,
        private KernelInterface $kernel
    ) {}
    
    public function generateDatabaseBackup(int $databaseId)
    {
        /**
         * @var \App\Entity\DatabaseCredential
         */
        $databaseCredential = $this->databaseCredentialRepository->findOneBy(["id" => $databaseId]);

        $fileName = $this->makeDatabaseBackupFileName($databaseCredential->name);
        $shellCommand = $this->generateShellCommand(
            $databaseCredential->user,
            $databaseCredential->getPassword(),
            $databaseCredential->host,
            $databaseCredential->databaseName,
            $this->kernel->getProjectDir() . "/var/database_backups/" . $fileName,
            (string) $databaseCredential->getPort()
        );
        $result = shell_exec($shellCommand);

        $this->writeEntryDatabaseBackup($databaseId, $fileName);
    }

    private function writeEntryDatabaseBackup(int $databaseId, string $fileName)
    {
        $databaseBackupFile = new DatabaseBackupFile();
        $databaseBackupFile->setDate(new DateTime());
        $databaseBackupFile->setFileName($fileName);
        $this->entityManager->persist($databaseBackupFile);
        $this->entityManager->flush();
    }

    private function generateShellCommand(
        string $databaseUser,
        string $databasePassword,
        string $host,
        string $databaseName,
        string $fileName,
        string $port = "3306"
    ): string
    {
        $baseFormat = "mysqldump%s%s%s%s%s > %s";
        
        return sprintf(
            $baseFormat,
            " -u{$databaseUser}",
            " -p{$databasePassword}",
            " -h{$host}",
            " -P{$port}",
            " {$databaseName}",
            "{$fileName}"
        );
    }

    private function makeDatabaseBackupFileName(string $databaseCredentialName): string
    {
        $dateTimeNow = (new DateTime())->format("U");
        $databaseCredentialTitle = str_replace(" ", "_", $databaseCredentialName);
        return $databaseCredentialTitle . "-" . $dateTimeNow . ".sql";
    }
}
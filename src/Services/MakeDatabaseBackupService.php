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

class MakeDatabaseBackupService
{
    public function __construct(
        private DatabaseCredentialRepository $databaseCredentialRepository,
        private EntityManagerInterface $entityManager
    ) {}
    
    public function generateDatabaseBackup(int $databaseId)
    {
        /**
         * @var \App\Entity\DatabaseCredential
         */
        $databaseCredential = $this->databaseCredentialRepository->findOneBy(["id" => $databaseId]);

        
        $shellCommand = $this->generateShellCommand(
            $databaseCredential->user,
            $databaseCredential->getPassword(),
            $databaseCredential->host,
            $databaseCredential->databaseName,
            "../var/database_backups/" . $this->makeDatabaseBackupFileName($databaseCredential->name)
        );
        shell_exec($shellCommand);

        $this->writeEntryDatabaseBackup($databaseId);
        
        $this->writeEntryDatabaseBackup($databaseId);
    }

    private function writeEntryDatabaseBackup(int $databaseId)
    {
        $databaseBackupFile = new DatabaseBackupFile();
        $databaseBackupFile->setDate(new DateTime());
        $databaseBackupFile->setFileName("arbitrary_name_" . $databaseId);
        $this->entityManager->persist($databaseBackupFile);
        $this->entityManager->flush();
    }

    private function generateShellCommand(
        string $databaseUser,
        string $databasePassword,
        string $host,
        string $databaseName,
        string $fileName
    ): string
    {
        return sprintf(
            "mysqldump -u%s -p%s -h%s %s > %s",
            $databaseUser,
            $databasePassword,
            $host,
            $databaseName,
            $fileName
        );
    }

    private function makeDatabaseBackupFileName(string $databaseCredentialName): string
    {
        $dateTimeNow = (new DateTime())->format("U");
        $databaseCredentialTitle = str_replace(" ", "_", $databaseCredentialName);
        return $databaseCredentialTitle . "-" . $dateTimeNow . ".sql";
    }
}
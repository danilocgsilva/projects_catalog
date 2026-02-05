<?php

declare(strict_types=1);

namespace App\Services\DatabaseBackupFiles;

use App\Repository\DatabaseCredentialRepository;
use App\Entity\DatabaseBackupFile;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use App\Services\DatabaseBackupFiles\{S3FileSystemService, ComputerFileSystemService};
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DatabaseBackupService
{
    private $projectDir;

    private $container;

    private DatabaseBackupFile $databaseBackupFile;

    public function __construct(
        private DatabaseCredentialRepository $databaseCredentialRepository,
        private EntityManagerInterface $entityManager,
        private KernelInterface $kernel,
        private ParameterBagInterface $parameterBag,
        private Filesystem $localFilesystem,
        private ManagerRegistry $managerRegistry
    ) {
        $this->container = $this->kernel
            ->getContainer();
        $this->projectDir = $this->kernel
            ->getProjectDir();
    }

    public function restoreOwnDatabase(UploadedFile $uploadedFile)
    {
        $computerFileSystem = $this->container->get(ComputerFileSystemService::class);
        $fileSystem = $this->container->get(Filesystem::class);

        $newFilename = sprintf(
            '%s_%s.%s',
            pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME),
            uniqid(),
            $uploadedFile->getClientOriginalExtension(),
        );

        $uploadedFile->move(
            $absolutePath = $computerFileSystem->getFileSystemAddressPath(""),
            $newFilename
        );

        $fullPathFileName = $absolutePath . $newFilename;

        $restoreScript = $this->generateShellCommandOwnRestore($fullPathFileName);
        shell_exec($restoreScript);
        $fileSystem->remove($fullPathFileName);
    }

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
            (string) $databaseCredential->getPort()
        );
        $backupScriptContent = shell_exec($shellCommand);
        $this->saveToFileSystem($fileName, $backupScriptContent);
        $this->writeEntryDatabaseBackup($databaseId, $fileName);
        $databaseCredential->addDatabaseBackupFile($this->databaseBackupFile);
        $this->entityManager->persist($databaseCredential);
        $this->entityManager->flush();

        if ($this->parameterBag->get("filesystem_handler") === "s3") {
            $this->container
                ->get(S3FileSystemService::class)
                ->write($fileName, $backupScriptContent);
        }
    }

    public function deleteDatabaseBackupFile(string $fileName): void
    {
        $filePath = $this->projectDir . "/var/database_backups/" . $fileName;
        if ($this->localFilesystem->exists($filePath)) {
            $this->localFilesystem->remove($filePath);
        }
        if ($this->parameterBag->get("filesystem_handler") === "s3") {
            $this->container
                ->get(S3FileSystemService::class)
                ->delete($fileName);
        }
    }

    private function generateShellCommandOwnRestore(string $filePath)
    {
        $stringBase = "mysql -u%s -p%s -h%s %s < %s";

        $connection = $this->managerRegistry->getConnection();
        $connectionParameter = $connection->getParams();

        return sprintf(
            $stringBase,
            $connectionParameter['user'],
            $connectionParameter['password'],
            $connectionParameter['host'],
            $connectionParameter['dbname'],
            $filePath
        );
    }

    private function writeEntryDatabaseBackup(int $databaseId, string $fileName)
    {
        $this->databaseBackupFile = (new DatabaseBackupFile())
            ->setDate(new DateTime())
            ->setFileName($fileName);
        $this->entityManager->persist($this->databaseBackupFile);
        $this->entityManager->flush();
    }

    private function generateShellCommand(
        string $databaseUser,
        string $databasePassword,
        string $host,
        string $databaseName,
        string $port = "3306"
    ): string {
        $baseFormat = "mysqldump%s%s%s%s%s";

        return sprintf(
            $baseFormat,
            " -u{$databaseUser}",
            " -p{$databasePassword}",
            " -h{$host}",
            " -P{$port}",
            " {$databaseName}"
        );
    }

    private function makeDatabaseBackupFileName(string $databaseCredentialName): string
    {
        $dateTimeNow = (new DateTime())->format("U");
        $databaseCredentialTitle = str_replace(" ", "_", $databaseCredentialName);
        return $databaseCredentialTitle . "-" . $dateTimeNow . ".sql";
    }

    private function saveToFileSystem(string $fileName, string $content): void
    {
        $this->localFilesystem->dumpFile($this->projectDir . "/var/database_backups/" . $fileName, $content);
    }
}

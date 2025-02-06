<?php

declare(strict_types=1);

namespace App\MessageHandler;

use DateTime;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Message\DatabaseBackupNotification;
use App\Repository\DatabaseBackupFileRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\DatabaseBackupFile;

#[AsMessageHandler]
class DatabaseBackupNotificationHandler
{
    private EntityManagerInterface $entityManager;

    // private DatabaseBackupFileRepository $databaseBackupFileRepository;


    public function __construct(
        EntityManagerInterface $entityManager
        // DatabaseBackupFileRepository $databaseBackupFileRepository
    ) {
        $this->entityManager = $entityManager;
        // $this->databaseBackupFileRepository = $databaseBackupFileRepository;
    }

    public function __invoke(
        DatabaseBackupNotification $message
        // DatabaseBackupFileRepository $databaseBackupFileRepository,
        // EntityManagerInterface $entityManager
    ) {
        // $databaseBackupFileRepository = new DatabaseBackupFileRepository();
        // EntityManagerInterface $entityManager

        // echo 'Processing message: ';
        $databaseBackupFile = new DatabaseBackupFile();
        $databaseBackupFile->setDate(new DateTime());
        $databaseBackupFile->setFileName("arbitrary_name");
        $this->entityManager->persist($databaseBackupFile);
        $this->entityManager->flush();

        // echo "Agora sim: " . $message->getContent();
    }
}

<?php

declare(strict_types=1);

namespace App\MessageHandler;

use DateTime;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Message\DatabaseBackupNotification;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\DatabaseBackupFile;

#[AsMessageHandler]
class DatabaseBackupNotificationHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function __invoke(
        DatabaseBackupNotification $message
    ) {
        $databaseBackupFile = new DatabaseBackupFile();
        $databaseBackupFile->setDate(new DateTime());
        $databaseBackupFile->setFileName("arbitrary_name_" . $message->getContent());
        $this->entityManager->persist($databaseBackupFile);
        $this->entityManager->flush();
    }
}

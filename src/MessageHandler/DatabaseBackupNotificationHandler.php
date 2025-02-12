<?php

declare(strict_types=1);

namespace App\MessageHandler;

use DateTime;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Message\DatabaseBackupNotification;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\DatabaseBackupFile;
use App\Repository\DatabaseBackupFileRepository;
use App\Services\MakeDatabaseBackupService;

#[AsMessageHandler]
class DatabaseBackupNotificationHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DatabaseBackupFileRepository $databaseBackupFileRepository,
        private MakeDatabaseBackupService $makeDatabaseBackupService
    ) {
    }

    public function __invoke(
        DatabaseBackupNotification $message
    ) {
        $databaseId = $message->getContent();
        $this->makeDatabaseBackupService->generateDatabaseBackup((int) $databaseId);
    }
}

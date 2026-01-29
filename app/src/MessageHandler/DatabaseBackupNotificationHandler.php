<?php

declare(strict_types=1);

namespace App\MessageHandler;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use App\Message\DatabaseBackupNotification;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DatabaseBackupFileRepository;
use App\Services\DatabaseBackupFiles\DatabaseBackupService;

#[AsMessageHandler]
class DatabaseBackupNotificationHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private DatabaseBackupFileRepository $databaseBackupFileRepository,
        private DatabaseBackupService $databaseBackupService
    ) {
    }

    public function __invoke(
        DatabaseBackupNotification $message
    ) {
        $databaseId = $message->getContent();
        $this->databaseBackupService->generateDatabaseBackup((int) $databaseId);
    }
}

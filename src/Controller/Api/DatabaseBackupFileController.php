<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DatabaseBackupFileRepository;
use App\Entity\DatabaseBackupFile;

#[Route('/database/backup/file')]
final class DatabaseBackupFileController extends AbstractController
{
    #[Route('/download', name: 'app_database_backup_download', methods: ['POST'])]
    public function download(Request $request, DatabaseBackupFileRepository $databaseBackupFileRepository)
    {
        $csrfToken = $request->get("_token");
        $databaseBackupId = $request->get("database_backup_id");
        if (!$this->isCsrfTokenValid("download_database_backup" . $databaseBackupId, $csrfToken)) {
            throw $this->createNotFoundException();
        }

        /** @var \App\Entity\DatabaseBackupFile */
        $databaseBackup = $databaseBackupFileRepository->findOneBy(["id" => (int) $databaseBackupId]);
        return $this->file("../var/database_backups/" . $databaseBackup->getFileName());
    }
}

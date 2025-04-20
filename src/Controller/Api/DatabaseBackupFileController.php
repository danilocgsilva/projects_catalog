<?php

declare(strict_types=1);

namespace App\Controller\Api;

use ErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DatabaseBackupFileRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[Route('/database/backup/file')]
final class DatabaseBackupFileController extends AbstractController
{
    #[Route('/download', name: 'app_database_backup_download', methods: ['POST'])]
    public function download(Request $request, DatabaseBackupFileRepository $databaseBackupFileRepository): BinaryFileResponse|RedirectResponse
    {
        $csrfToken = $request->get("_token");
        $databaseBackupId = $request->get("database_backup_id");
        if (!$this->isCsrfTokenValid("download_database_backup" . $databaseBackupId, $csrfToken)) {
            throw $this->createNotFoundException();
        }

        /** @var \App\Entity\DatabaseBackupFile */
        $databaseBackup = $databaseBackupFileRepository->findOneBy(["id" => (int) $databaseBackupId]);
        $databaseFilePath = "../var/database_backups/" . $databaseBackup->getFileName();
        if (file_exists($databaseFilePath)) {
            return $this->file($databaseFilePath);
        }
        return $this->redirectWithErrorMessage();
    }

    private function redirectWithErrorMessage(): RedirectResponse
    {
        $this->addFlash('error', 'Sorry! I could not attend to yout request!');
        return $this->redirectToRoute('app_database_backup_file_index');
    }
}

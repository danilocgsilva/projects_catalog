<?php

declare(strict_types=1);

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DatabaseBackupFileRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Services\DatabaseBackupFiles\{
    BringToLocal,
    ComputerFileSystemService,
    DatabaseBackupFileFileSystemInterface
};

#[Route('/database/backup/file')]
final class DatabaseBackupFileController extends AbstractController
{
    #[Route('/download', name: 'app_database_backup_download', methods: ['POST'])]
    public function download(
        Request $request, 
        DatabaseBackupFileRepository $databaseBackupFileRepository,
        DatabaseBackupFileFileSystemInterface $dbfs,
        BringToLocal $bringToLocal
    ): BinaryFileResponse|RedirectResponse
    {
        $csrfToken = $request->get("_token");
        $databaseBackupId = $request->get("database_backup_id");
        if (!$this->isCsrfTokenValid("download_database_backup" . $databaseBackupId, $csrfToken)) {
            throw $this->createNotFoundException();
        }

        /** @var \App\Entity\DatabaseBackupFile */
        $databaseBackup = $databaseBackupFileRepository->findOneBy(["id" => (int) $databaseBackupId]);
        $databaseBackupFileName = $databaseBackup->getFileName();
        if ($dbfs->exists($databaseBackupFileName)) {
            return $this->downloadFile($databaseBackupFileName, $bringToLocal, $dbfs);
        }
        return $this->redirectWithErrorMessage();
    }

    private function redirectWithErrorMessage(): RedirectResponse
    {
        $this->addFlash('error', 'Sorry! I could not attend to yout request!');
        return $this->redirectToRoute('app_database_backup_file_index');
    }

    private function downloadFile(
        string $fileName, 
        BringToLocal $bringToLocal, 
        DatabaseBackupFileFileSystemInterface $dbfs
    ): BinaryFileResponse
    {
        if ($dbfs instanceof ComputerFileSystemService) {
            return $this->file($dbfs->getFileSystemAddressPath($fileName));
        } else {
            $bringToLocal->toLocal($fileName);
            return $this->file($bringToLocal->getLocalPath());
        }
    }
}

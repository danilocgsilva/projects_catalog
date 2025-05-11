<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Entity\DatabaseBackupFile;
use App\Repository\DatabaseBackupFileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Message\DatabaseBackupNotification;
use App\Services\DatabaseBackupFiles\DatabaseBackupService;

#[Route('/database/backup/file')]
final class DatabaseBackupFileController extends AbstractController
{
    #[Route(name: 'app_database_backup_file_index', methods: ['GET'])]
    public function index(DatabaseBackupFileRepository $databaseBackupFileRepository): Response
    {
        return $this->render('database_backup_file/index.html.twig', [
            'database_backup_files' => $databaseBackupFileRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_database_backup_file_show', methods: ['GET'])]
    public function show(DatabaseBackupFile $databaseBackupFile): Response
    {
        return $this->render('database_backup_file/show.html.twig', [
            'database_backup_file' => $databaseBackupFile,
        ]);
    }

    #[Route('/{id}', name: 'app_database_backup_file_delete', methods: ['POST'])]
    public function delete(
        Request $request, 
        DatabaseBackupFile $databaseBackupFile, 
        EntityManagerInterface $entityManager,
        DatabaseBackupService $databaseBackupService
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$databaseBackupFile->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($databaseBackupFile);
            $entityManager->flush();
            $databaseBackupService->deleteDatabaseBackupFile(
                $databaseBackupFile->getFileName()
            );
        }

        return $this->redirectToRoute('app_database_backup_file_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('create', name: 'app_database_backup_file_create_database_backup', methods: ['POST'])]
    public function createDatabaseBackup(
        MessageBusInterface $bus,
        Request $request
    ): Response
    {
        $submittedToken = $request->getPayload()->get('token');
        if ($this->isCsrfTokenValid("database-backup", $submittedToken)) {
            $database_credential_id = $request->get("database_credential_id");
            $bus->dispatch(new DatabaseBackupNotification((string) $database_credential_id));
        }
        return $this->redirectToRoute('app_database_backup_file_index', [], Response::HTTP_SEE_OTHER);
    }
}

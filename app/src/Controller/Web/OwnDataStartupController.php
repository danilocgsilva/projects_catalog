<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\OwnDataStartupType;
use Symfony\Component\HttpFoundation\Request;
use App\Services\DatabaseBackupFiles\{ComputerFileSystemService, DatabaseBackupService};
use Exception;

final class OwnDataStartupController extends AbstractController
{
    #[Route('/own_data_startup', name: 'app_own_data_startup')]
    public function index(
        Request $request, 
        DatabaseBackupService $databaseBackupService
    ): Response
    {
        $form = $this->createForm(OwnDataStartupType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $databaseBackupService->restoreOwnDatabase($form->get('own_data_startup')->getData());
                $this->addFlash('success', 'File uploaded successfully!');
            } catch (Exception $e) {
                $this->addFlash('error', 'Troubles in restoring own database');
            }

            return $this->redirectToRoute('app_own_data_startup');
        }

        return $this->render('own_data_startup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

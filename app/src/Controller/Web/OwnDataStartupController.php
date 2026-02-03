<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\OwnDataStartupType;
use Symfony\Component\HttpFoundation\Request;
use App\Services\DatabaseBackupFiles\ComputerFileSystemService;
use Symfony\Component\Filesystem\Filesystem;

final class OwnDataStartupController extends AbstractController
{
    #[Route('/own_data_startup', name: 'app_own_data_startup')]
    public function index(Request $request, ComputerFileSystemService $computerFileSystem): Response
    {
        $form = $this->createForm(OwnDataStartupType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('own_data_startup')->getData();
            
            // Here you would handle the file
            // - Move it to a directory
            // - Save info to database
            // - Process it, etc.
            // Generate a unique filename
            $fileName = uniqid() . '.' . $file->guessExtension();

            // $computerFileSystem = new ComputerFileSystemService(new Filesystem());
            
            $file->move(
                $computerFileSystem->getFileSystemAddressPath(""),
                $fileName
            );
            
            $this->addFlash('success', 'File uploaded successfully!');
            
            return $this->redirectToRoute('app_own_data_startup');
        }


        return $this->render('own_data_startup/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

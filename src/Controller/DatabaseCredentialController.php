<?php

namespace App\Controller;

use App\Entity\DatabaseCredential;
use App\Form\DatabaseCredentialType;
use App\Repository\DatabaseCredentialRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/database/credential')]
final class DatabaseCredentialController extends AbstractController
{
    #[Route(name: 'app_database_credential_index', methods: ['GET'])]
    public function index(DatabaseCredentialRepository $databaseCredentialRepository): Response
    {
        return $this->render('database_credential/index.html.twig', [
            'database_credentials' => $databaseCredentialRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_database_credential_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $databaseCredential = new DatabaseCredential();
        $form = $this->createForm(DatabaseCredentialType::class, $databaseCredential);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($databaseCredential);
            $entityManager->flush();

            return $this->redirectToRoute('app_database_credential_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('database_credential/new.html.twig', [
            'database_credential' => $databaseCredential,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_database_credential_show', methods: ['GET'])]
    public function show(DatabaseCredential $databaseCredential): Response
    {
        return $this->render('database_credential/show.html.twig', [
            'database_credential' => $databaseCredential,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_database_credential_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DatabaseCredential $databaseCredential, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DatabaseCredentialType::class, $databaseCredential);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_database_credential_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('database_credential/edit.html.twig', [
            'database_credential' => $databaseCredential,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_database_credential_delete', methods: ['POST'])]
    public function delete(Request $request, DatabaseCredential $databaseCredential, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $databaseCredential->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($databaseCredential);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_database_credential_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/expose', name: 'app_database_credential_expose_password', methods: ['GET'])]
    public function getPassword(DatabaseCredential $databaseCredential): JsonResponse
    {
        return $this->json(['password' => $databaseCredential->getPassword()]);
    }
}

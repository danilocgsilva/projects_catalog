<?php

namespace App\Controller;

use App\Entity\Environment;
use App\Form\EnvironmentType;
use App\Repository\EnvironmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/environment')]
final class EnvironmentController extends AbstractController
{
    #[Route(name: 'app_environment_index', methods: ['GET'])]
    public function index(EnvironmentRepository $environmentRepository): Response
    {
        return $this->render('environment/index.html.twig', [
            'environments' => $environmentRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_environment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $environment = new Environment();
        $form = $this->createForm(EnvironmentType::class, $environment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($environment);
            $entityManager->flush();

            return $this->redirectToRoute('app_environment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('environment/new.html.twig', [
            'environment' => $environment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_environment_show', methods: ['GET'])]
    public function show(Environment $environment): Response
    {
        return $this->render('environment/show.html.twig', [
            'environment' => $environment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_environment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Environment $environment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EnvironmentType::class, $environment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_environment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('environment/edit.html.twig', [
            'environment' => $environment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_environment_delete', methods: ['POST'])]
    public function delete(Request $request, Environment $environment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$environment->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($environment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_environment_index', [], Response::HTTP_SEE_OTHER);
    }
}

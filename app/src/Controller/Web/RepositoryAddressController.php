<?php

declare(strict_types=1);

namespace App\Controller\Web;

use App\Entity\RepositoryAddress;
use App\Form\RepositoryAddressType;
use App\Repository\RepositoryAddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/repository/address')]
final class RepositoryAddressController extends AbstractController
{
    #[Route(name: 'app_repository_address_index', methods: ['GET'])]
    public function index(RepositoryAddressRepository $repositoryAddressRepository): Response
    {
        return $this->render('repository_address/index.html.twig', [
            'repository_addresses' => $repositoryAddressRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_repository_address_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $repositoryAddress = new RepositoryAddress();
        $form = $this->createForm(RepositoryAddressType::class, $repositoryAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($repositoryAddress);
            $entityManager->flush();

            return $this->redirectToRoute('app_repository_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repository_address/new.html.twig', [
            'repository_address' => $repositoryAddress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_repository_address_show', methods: ['GET'])]
    public function show(RepositoryAddress $repositoryAddress): Response
    {
        return $this->render('repository_address/show.html.twig', [
            'repository_address' => $repositoryAddress,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_repository_address_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RepositoryAddress $repositoryAddress, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RepositoryAddressType::class, $repositoryAddress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_repository_address_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('repository_address/edit.html.twig', [
            'repository_address' => $repositoryAddress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_repository_address_delete', methods: ['POST'])]
    public function delete(Request $request, RepositoryAddress $repositoryAddress, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$repositoryAddress->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($repositoryAddress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_repository_address_index', [], Response::HTTP_SEE_OTHER);
    }
}

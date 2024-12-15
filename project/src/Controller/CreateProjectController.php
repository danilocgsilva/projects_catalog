<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\CreateProjectFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreateProjectController extends AbstractController
{
    #[Route('/create/project', name: 'app_create_project')]
    public function new(Request $request): Response 
    {
        $project = new Project();
        
        $form = $this->createForm(CreateProjectFormType::class);
        $form->handleRequest($request);
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $data = $form->getData();
            
        //     $this->addFlash('success', 'Your message has been sent!');
        //     return $this->redirectToRoute('contact');
        // }

        // return $this->render('base.html.twig');
        return $this->render('projects/create.html.twig', [
            'form' => $form
        ]);
    }
}

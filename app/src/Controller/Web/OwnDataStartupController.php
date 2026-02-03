<?php

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class OwnDataStartupController extends AbstractController
{
    #[Route('/own_data_startup', name: 'app_own_data_startup')]
    public function index(): Response
    {
        return $this->render('own_data_startup/index.html.twig');
    }
}

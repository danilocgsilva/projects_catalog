<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\DatabaseCredential;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/database/credential')]
final class DatabaseCredentialController extends AbstractController
{
    #[Route('/{id}/expose', name: 'app_database_credential_expose_password', methods: ['POST'])]
    public function getPassword(DatabaseCredential $databaseCredential, Request $request): JsonResponse
    {
        $csrfToken = json_decode($request->getContent())->csrfProtection ?? $this->createNotFoundException();
        
        if (!$this->isCsrfTokenValid('reveal-database-password', $csrfToken)) {
            throw $this->createNotFoundException();
        }

        return $this->json(['password' => $databaseCredential->getPassword()]);
    }
}

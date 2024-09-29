<?php

namespace App\Controller\Admin\Profile;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/profile')]
class AdminProfileController extends AbstractController
{
    #[Route('', name: 'app_admin_profile_edit', methods: ['POST'])]
    public function edit(): JsonResponse
    {
        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}

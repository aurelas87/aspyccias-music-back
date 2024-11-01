<?php

namespace App\Controller\Admin\Profile;

use App\Service\Profile\ProfileLinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/profile/links')]
class AdminProfileLinkController extends AbstractController
{
    #[Route('', name: 'app_admin_profile_link', methods: ['GET'])]
    public function list(ProfileLinkService $profileLinkService): JsonResponse
    {
        return $this->json(data: $profileLinkService->listProfileLinks(), context: ['groups' => ['default', 'admin']]);
    }
}

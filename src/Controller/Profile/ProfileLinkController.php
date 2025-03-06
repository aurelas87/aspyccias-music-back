<?php

namespace App\Controller\Profile;

use App\Service\Profile\ProfileLinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/profile/links')]
class ProfileLinkController extends AbstractController
{
    #[Route(path: '', name: 'app_profile_link', methods: ['GET'])]
    public function list(ProfileLinkService $profileLinkService): JsonResponse
    {
        return $this->json(
            data: $profileLinkService->listProfileLinks(),
            context: ['groups' => ['default']]
        );
    }
}

<?php

namespace App\Controller\Profile;

use App\Service\Profile\ProfileLinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class ProfileLinkController extends AbstractController
{
    #[Route('/profile/links', name: 'app_profile_profile_link')]
    public function list(ProfileLinkService $profileLinkService): JsonResponse
    {
        return $this->json($profileLinkService->listProfileLinks());
    }
}

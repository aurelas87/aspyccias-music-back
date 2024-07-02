<?php

namespace App\Controller\Profile;

use App\Service\Profile\ProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile', methods: ['GET'])]
    public function get(Request $request, ProfileService $profileService): JsonResponse
    {
        return $this->json($profileService->getProfile($request->getLocale()));
    }
}

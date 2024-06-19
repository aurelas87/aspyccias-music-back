<?php

namespace App\Controller\Profile;

use App\Service\Profile\ProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(Request $request, ProfileService $profileService): Response
    {
        return $this->json($profileService->getProfile($request->getLocale()));
    }
}

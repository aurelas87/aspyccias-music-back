<?php

namespace App\Controller;

use App\Service\ProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(ProfileService $profileService): Response
    {
        return $this->json($profileService->getAspycciasProfile());
    }
}

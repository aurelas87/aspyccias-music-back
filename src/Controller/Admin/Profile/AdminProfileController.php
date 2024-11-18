<?php

namespace App\Controller\Admin\Profile;

use App\Model\Profile\ProfileDTO;
use App\Service\Profile\ProfileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/profile')]
class AdminProfileController extends AbstractController
{
    #[Route(path: '', name: 'app_admin_profile', methods: ['GET'])]
    public function get(ProfileService $profileService): JsonResponse
    {
        return $this->json($profileService->getProfileForAdmin());
    }

    #[Route(path: '', name: 'app_admin_profile_edit', methods: ['PUT'])]
    public function edit(
        #[MapRequestPayload(acceptFormat: 'json')] ProfileDTO $profileDTO,
        ProfileService $profileService
    ): JsonResponse {
        $profileService->updateProfile($profileDTO);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}

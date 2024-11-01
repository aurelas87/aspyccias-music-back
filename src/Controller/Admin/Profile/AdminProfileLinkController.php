<?php

namespace App\Controller\Admin\Profile;

use App\Model\Profile\ProfileLinkDTO;
use App\Service\Profile\ProfileLinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/profile/links')]
class AdminProfileLinkController extends AbstractController
{
    #[Route('', name: 'app_admin_profile_link', methods: ['GET'])]
    public function list(ProfileLinkService $profileLinkService): JsonResponse
    {
        return $this->json(data: $profileLinkService->listProfileLinks(), context: ['groups' => ['default', 'admin']]);
    }

    #[Route('', name: 'app_admin_profile_link_add', methods: ['POST'])]
    public function add(
        #[MapRequestPayload(acceptFormat: 'json')] ProfileLinkDTO $profileLinkDTO,
        ProfileLinkService $profileLinkService
    ): JsonResponse {
        $profileLinkService->addProfileLink($profileLinkDTO);

        return $this->json(null, Response::HTTP_CREATED);
    }
}

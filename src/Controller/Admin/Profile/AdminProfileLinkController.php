<?php

namespace App\Controller\Admin\Profile;

use App\Entity\Profile\ProfileLink;
use App\Model\Profile\ProfileLinkDTO;
use App\Repository\Profile\ProfileLinkRepository;
use App\Service\Profile\ProfileLinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[Route('admin/profile/links')]
class AdminProfileLinkController extends AbstractController
{
    #[Route('', name: 'app_admin_profile_link_list', methods: ['GET'])]
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

    #[Route('/{name}', name: 'app_admin_profile_link_details', methods: ['GET'])]
    public function get(string $name, ProfileLinkRepository $profileLinkRepository): JsonResponse
    {
        return $this->json(
            data: $profileLinkRepository->findOneBy(['name' => $name]),
            context: ['groups' => ['default', 'admin']]
        );
    }

    #[Route('/{profileLink}', name: 'app_admin_profile_link_edit', methods: ['PUT'])]
    public function edit(
        #[ValueResolver('profile_link')] ProfileLink $profileLink,
        #[MapRequestPayload(acceptFormat: 'json')] ProfileLinkDTO $profileLinkDTO,
        ProfileLinkService $profileLinkService
    ): JsonResponse {
        $profileLinkService->editProfileLink($profileLink, $profileLinkDTO);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}

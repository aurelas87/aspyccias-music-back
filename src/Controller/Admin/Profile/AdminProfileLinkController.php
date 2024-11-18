<?php

namespace App\Controller\Admin\Profile;

use App\Entity\Profile\ProfileLink;
use App\Model\DirectionType;
use App\Model\Profile\ProfileLinkDTO;
use App\Service\Profile\ProfileLinkService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;

#[Route(path: '/admin/profile/links')]
class AdminProfileLinkController extends AbstractController
{
    #[Route(path: '', name: 'app_admin_profile_link_list', methods: ['GET'])]
    public function list(ProfileLinkService $profileLinkService): JsonResponse
    {
        return $this->json(data: $profileLinkService->listProfileLinks(), context: ['groups' => ['default', 'admin']]);
    }

    #[Route(path: '', name: 'app_admin_profile_link_add', methods: ['POST'])]
    public function add(
        #[MapRequestPayload(acceptFormat: 'json')] ProfileLinkDTO $profileLinkDTO,
        ProfileLinkService $profileLinkService
    ): JsonResponse {
        $profileLinkService->addProfileLink($profileLinkDTO);

        return $this->json(null, Response::HTTP_CREATED);
    }

    #[Route(path: '/{name}', name: 'app_admin_profile_link_details', methods: ['GET'])]
    public function get(string $name, ProfileLinkService $profileLinkService): JsonResponse
    {
        return $this->json(
            data: $profileLinkService->getProfileLinkForAdmin($name),
            context: ['groups' => ['default', 'admin']]
        );
    }

    #[Route(path: '/{profileLink}', name: 'app_admin_profile_link_edit', methods: ['PUT'])]
    public function edit(
        #[ValueResolver('profile_link')] ProfileLink $profileLink,
        #[MapRequestPayload(acceptFormat: 'json')] ProfileLinkDTO $profileLinkDTO,
        ProfileLinkService $profileLinkService
    ): JsonResponse {
        $profileLinkService->editProfileLink($profileLink, $profileLinkDTO);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(
        path: '/{profileLink}/move/{direction}',
        name: 'app_admin_profile_link_move',
        requirements: ['direction' => new EnumRequirement(DirectionType::class)],
        methods: ['PUT']
    )]
    public function move(
        #[ValueResolver('profile_link')] ProfileLink $profileLink,
        DirectionType $direction,
        ProfileLinkService $profileLinkService
    ): JsonResponse {
        $profileLinkService->moveProfileLink($profileLink, $direction);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{profileLink}', name: 'app_admin_profile_link_delete', methods: ['DELETE'])]
    public function delete(
        #[ValueResolver('profile_link')] ProfileLink $profileLink,
        ProfileLinkService $profileLinkService
    ): JsonResponse {
        $profileLinkService->deleteProfileLink($profileLink);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}

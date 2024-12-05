<?php

namespace App\Controller\Admin\Release;

use App\Entity\Release\ReleaseLinkName;
use App\Model\Release\ReleaseLinkNameDTO;
use App\Service\Release\ReleaseLinkNameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/release-link-names')]
class AdminReleaseLinkNameController extends AbstractController
{
    #[Route(path: '', name: 'app_admin_release_link_name_list', methods: ['GET'])]
    public function list(ReleaseLinkNameService $releaseLinkNameService): JsonResponse
    {
        return $this->json(
            data: $releaseLinkNameService->listReleaseLinkNamesForAdmin(),
            context: ['groups' => ['default', 'admin']]
        );
    }

    #[Route(path: '', name: 'app_admin_release_link_name_add', methods: ['POST'])]
    public function add(
        #[MapRequestPayload(acceptFormat: 'json')] ReleaseLinkNameDTO $releaseLinkNameDTO,
        ReleaseLinkNameService $releaseLinkNameService
    ): JsonResponse {
        $releaseLinkNameService->addReleaseLinkName($releaseLinkNameDTO);

        return $this->json(null, Response::HTTP_CREATED);
    }

    #[Route(path: '/{releaseLinkName}', name: 'app_admin_release_link_name_details', methods: ['GET'])]
    public function get(#[ValueResolver('release_link_name')] ReleaseLinkName $releaseLinkName): JsonResponse
    {
        return $this->json(
            data: $releaseLinkName,
            context: ['groups' => ['default', 'admin']]
        );
    }

    #[Route(path: '/{releaseLinkName}', name: 'app_admin_release_link_name_edit', methods: ['PUT'])]
    public function edit(
        #[ValueResolver('release_link_name')] ReleaseLinkName $releaseLinkName,
        #[MapRequestPayload(acceptFormat: 'json')] ReleaseLinkNameDTO $releaseLinkNameDTO,
        ReleaseLinkNameService $releaseLinkNameService
    ): JsonResponse {
        $releaseLinkNameService->editReleaseLinkName($releaseLinkName, $releaseLinkNameDTO);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{releaseLinkName}', name: 'app_admin_release_link_name_delete', methods: ['DELETE'])]
    public function delete(
        #[ValueResolver('release_link_name')] ReleaseLinkName $releaseLinkName,
        ReleaseLinkNameService $releaseLinkNameService
    ): JsonResponse {
        $releaseLinkNameService->deleteReleaseCreditType($releaseLinkName);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}

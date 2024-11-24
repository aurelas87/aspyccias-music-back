<?php

namespace App\Controller\Admin\Release;

use App\Entity\Release\ReleaseCreditType;
use App\Model\Release\ReleaseCreditTypeDTO;
use App\Service\Release\ReleaseCreditTypeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/releases/credit-types')]
class AdminReleaseCreditTypeController extends AbstractController
{
    #[Route(path: '', name: 'app_admin_release_credit_type_list', methods: ['GET'])]
    public function list(ReleaseCreditTypeService $releaseCreditTypeService): JsonResponse
    {
        return $this->json(
            data: $releaseCreditTypeService->listReleaseCreditTypesForAdmin(),
            context: ['groups' => ['default', 'admin']]
        );
    }

    #[Route(path: '', name: 'app_admin_release_credit_type_add', methods: ['POST'])]
    public function add(
        #[MapRequestPayload(acceptFormat: 'json')] ReleaseCreditTypeDTO $releaseCreditTypeDTO,
        ReleaseCreditTypeService $releaseCreditTypeService
    ): JsonResponse {
        $releaseCreditTypeService->addReleaseCreditType($releaseCreditTypeDTO);

        return $this->json(null, Response::HTTP_CREATED);
    }

    #[Route(path: '/{releaseCreditType}', name: 'app_admin_release_credit_type_details', methods: ['GET'])]
    public function get(#[ValueResolver('release_credit_type')] ReleaseCreditType $releaseCreditType): JsonResponse
    {
        return $this->json(
            data: $releaseCreditType,
            context: ['groups' => ['default', 'admin']]
        );
    }

    #[Route(path: '/{releaseCreditType}', name: 'app_admin_release_credit_type_edit', methods: ['PUT'])]
    public function edit(
        #[ValueResolver('release_credit_type')] ReleaseCreditType $releaseCreditType,
        #[MapRequestPayload(acceptFormat: 'json')] ReleaseCreditTypeDTO $releaseCreditTypeDTO,
        ReleaseCreditTypeService $releaseCreditTypeService
    ): JsonResponse {
        $releaseCreditTypeService->editReleaseCreditType($releaseCreditType, $releaseCreditTypeDTO);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{releaseCreditType}', name: 'app_admin_release_credit_type_delete', methods: ['DELETE'])]
    public function delete(
        #[ValueResolver('release_credit_type')] ReleaseCreditType $releaseCreditType,
        ReleaseCreditTypeService $releaseCreditTypeService
    ): JsonResponse {
        $releaseCreditTypeService->deleteReleaseCreditType($releaseCreditType);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}

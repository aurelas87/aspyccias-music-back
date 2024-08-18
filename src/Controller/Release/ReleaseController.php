<?php

declare(strict_types=1);

namespace App\Controller\Release;

use App\Model\Release\ReleaseType;
use App\Service\Release\ReleaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/releases')]
class ReleaseController extends AbstractController
{
    #[Route('/{releaseType}', name: 'app_release_list', requirements: ['releaseType' => 'single|ep|album'], methods: ['GET'])]
    public function list(
        #[ValueResolver('release_type')] ReleaseType $releaseType,
        Request $request,
        ReleaseService $releaseService
    ): JsonResponse {
        return $this->json(
            data: $releaseService->listReleases($request->getLocale(), $releaseType),
            context: ['groups' => ['default', 'list']]
        );
    }

    #[Route('/{slug}', name: 'app_release', methods: ['GET'])]
    public function releaseDetails(string $slug, Request $request, ReleaseService $releaseService): JsonResponse
    {
        return $this->json(
            data: $releaseService->getReleaseDetails($slug, $request->getLocale()),
            context: ['groups' => ['default', 'details']],
        );
    }
}

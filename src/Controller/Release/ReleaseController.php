<?php

declare(strict_types=1);

namespace App\Controller\Release;

use App\Service\Release\ReleaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class ReleaseController extends AbstractController
{
    #[Route('/releases', name: 'app_release_list')]
    public function list(Request $request, ReleaseService $releaseService): JsonResponse
    {
        return $this->json(
            data: $releaseService->listReleases($request->getLocale(), $request->query->all()),
            context: ['groups' => ['default', 'list']]
        );
    }
}

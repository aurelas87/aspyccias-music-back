<?php

namespace App\Controller;

use App\Model\Image\ResourceType;
use App\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;

#[Route(path: '/image')]
class ImageController extends AbstractController
{
    #[Route(
        path: '/{resourceType}/{resourceSlug}',
        name: 'app_image',
        requirements: ['resourceType' => new EnumRequirement(ResourceType::class)],
        methods: ['GET']
    )]
    public function get(
        ImageService $imageService,
        string $resourceType,
        string $resourceSlug = '',
    ): BinaryFileResponse {
        $filePath = $imageService->getImageFilePath($resourceType, $resourceSlug);

        return new BinaryFileResponse($filePath);
    }
}

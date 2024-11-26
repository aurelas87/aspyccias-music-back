<?php

namespace App\Controller;

use App\Model\Image\ResourceType;
use App\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;

#[Route(path: '/image')]
class ImageController extends AbstractController
{
    #[Route(
        path: '/{resourceType}/{resourceSlug}/{prefix}',
        name: 'app_image',
        requirements: ['resourceType' => new EnumRequirement(ResourceType::class)],
        methods: ['GET']
    )]
    public function get(
        ImageService $imageService,
        #[ValueResolver('resource_type')] ResourceType $resourceType,
        string $resourceSlug = '',
        string $prefix = '',
    ): BinaryFileResponse {
        $filePath = $imageService->getImageFilePath($resourceType, $resourceSlug, $prefix);

        return new BinaryFileResponse($filePath);
    }
}

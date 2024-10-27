<?php

namespace App\Controller;

use App\Model\Image\ResourceType;
use App\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\EnumRequirement;

#[Route('/image')]
class ImageController extends AbstractController
{
    #[Route(
        '/{resourceType}',
        name: 'app_image',
        requirements: ['resourceType' => new EnumRequirement(ResourceType::class)],
        methods: ['GET']
    )]
    public function get(string $resourceType, ImageService $imageService): BinaryFileResponse
    {
        $filePath = $imageService->getImageFilePath($resourceType);

        return new BinaryFileResponse($filePath);
    }
}

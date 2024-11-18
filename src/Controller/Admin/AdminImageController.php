<?php

namespace App\Controller\Admin;

use App\Model\Image\ImageMetadataDTO;
use App\Service\ImageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\MapUploadedFile;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\File;

#[Route(path: '/admin/image')]
class AdminImageController extends AbstractController
{
    #[Route(path: '', name: 'app_admin_image_upload', methods: ['POST'])]
    public function upload(
        #[MapRequestPayload] ImageMetadataDTO $imageDTO,
        #[MapUploadedFile([new File(mimeTypes: ['image/jpeg'])])] UploadedFile $image,
        ImageService $imageService
    ): JsonResponse {
        $imageService->createImageFile($imageDTO, $image);

        return $this->json(null, Response::HTTP_CREATED);
    }
}

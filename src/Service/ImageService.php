<?php

namespace App\Service;

use App\Helper\ImageHelper;
use App\Model\Image\ImageMetadataDTO;
use App\Model\Image\ResourceType;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    private ImageHelper $imageHelper;

    public function __construct(ImageHelper $imageHelper)
    {
        $this->imageHelper = $imageHelper;
    }

    public function createImageFile(ImageMetadataDTO $imageDTO, UploadedFile $image): void
    {
        $filePath = $this->imageHelper->getImageDirectoryPath($imageDTO->resourceType);

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        if ($imageDTO->resourceType === ResourceType::profile->value) {
            $filePath .= '/'.ImageHelper::PROFILE_MAIN_IMAGE_NAME;
        }

        $handle = \fopen($filePath, 'w');
        \fwrite($handle, $image->getContent());
        \fclose($handle);
    }

    public function getImageFilePath(string $resourceType): string
    {
        $filePath = $this->imageHelper->getImageDirectoryPath($resourceType);

        if ($resourceType === ResourceType::profile->value) {
            $filePath .= '/'.ImageHelper::PROFILE_MAIN_IMAGE_NAME;
        }

        return $filePath;
    }
}

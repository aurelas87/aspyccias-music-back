<?php

namespace App\Service;

use App\Entity\News\News;
use App\Exception\News\NewsNotFoundException;
use App\Helper\ImageHelper;
use App\Model\Image\ImageMetadataDTO;
use App\Model\Image\ResourceType;
use App\Repository\News\NewsRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageService
{
    private ImageHelper $imageHelper;
    private NewsRepository $newsRepository;

    public function __construct(ImageHelper $imageHelper, NewsRepository $newsRepository)
    {
        $this->imageHelper = $imageHelper;
        $this->newsRepository = $newsRepository;
    }

    public function createImageFile(ImageMetadataDTO $imageDTO, UploadedFile $image): void
    {
        if (!\in_array($imageDTO->resourceType, [
            ResourceType::profile->value,
            ResourceType::news->value,
            ResourceType::release->value,
        ])) {
            throw new BadRequestHttpException();
        }

        $filePath = $this->imageHelper->getImageDirectoryPath($imageDTO->resourceType);

        if (!file_exists($filePath)) {
            mkdir($filePath, 0777, true);
        }

        $filePath = $this->getImageFilePath($imageDTO->resourceType, $imageDTO->resourceSlug, true);

        $handle = \fopen($filePath, 'w');
        \fwrite($handle, $image->getContent());
        \fclose($handle);
    }

    public function getImageFilePath(string $resourceType, string $resourceSlug, bool $create = false): string
    {
        $filePath = $this->imageHelper->getImageDirectoryPath($resourceType);

        if ($resourceType === ResourceType::profile->value) {
            $filePath .= '/'.ImageHelper::PROFILE_MAIN_IMAGE_NAME;
        } elseif ($resourceSlug !== '') {
            $year = null;
            $formattedDate = null;

            if ($resourceType === ResourceType::news->value) {
                $news = $this->newsRepository->findOneBy(['slug' => $resourceSlug]);
                if (!$news instanceof News) {
                    throw new NewsNotFoundException();
                }

                $year = $news->getDate()->format('Y');
                $formattedDate = $news->getDate()->format('Y-m-d');
            }

            if (!$year || !$formattedDate) {
                throw new NotFoundHttpException();
            }

            $filePath .= "/$year";
            if ($create && !\file_exists($filePath)) {
                mkdir($filePath, 0777, true);
            }

            $filePath .= "/$formattedDate-$resourceSlug";
        } else {
            throw new BadRequestHttpException();
        }

        $filePath .= ImageHelper::DEFAULT_IMAGE_EXTENSION;

        if (!$create && !\file_exists($filePath)) {
            throw new NotFoundHttpException();
        }

        return $filePath;
    }

    public function deleteImageFile(string $resourceType, string $resourceSlug): void
    {
        if ($resourceType === ResourceType::profile->value) {
            throw new BadRequestHttpException();
        }

        $filePath = $this->getImageFilePath($resourceType, $resourceSlug, true);
        if (!\file_exists($filePath)) {
            return;
        }

        \unlink($filePath);

        $directory = \dirname($filePath);
        if (\count(\scandir($directory)) === 2) {
            \rmdir($directory);
        }
    }
}

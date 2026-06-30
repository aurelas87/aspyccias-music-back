<?php

namespace App\Service;

use App\Entity\News\News;
use App\Entity\Release\Release;
use App\Exception\News\NewsNotFoundException;
use App\Exception\Release\ReleaseNotFoundException;
use App\Helper\ImageHelper;
use App\Model\Image\ImageAction;
use App\Model\Image\ImageMetadataDTO;
use App\Model\Image\ResourceType;
use App\Repository\News\NewsRepository;
use App\Repository\Release\ReleaseRepository;
use LogicException;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ImageService
{
    private ImageHelper $imageHelper;
    private NewsRepository $newsRepository;
    private ReleaseRepository $releaseRepository;

    public function __construct(
        ImageHelper $imageHelper,
        NewsRepository $newsRepository,
        ReleaseRepository $releaseRepository
    ) {
        $this->imageHelper = $imageHelper;
        $this->newsRepository = $newsRepository;
        $this->releaseRepository = $releaseRepository;
    }

    public function createImageFile(ImageMetadataDTO $imageDTO, UploadedFile $image): void
    {
        if (!in_array($imageDTO->resourceType, [
            ResourceType::profile->value,
            ResourceType::news->value,
            ResourceType::releases->value,
        ], true)) {
            throw new BadRequestHttpException();
        }

        $resourceType = ResourceType::tryFrom($imageDTO->resourceType);

        $filePath = $this->imageHelper->getImageDirectoryPath($resourceType);

        if (!file_exists($filePath) && !mkdir($filePath, 0777, true) && !is_dir($filePath)) {
            throw new RuntimeException(sprintf('Directory "%s" was not created', $filePath));
        }

        $filePath = $this->getImageFilePath(
            $resourceType,
            $imageDTO->resourceSlug,
            $imageDTO->prefix,
            ImageAction::create
        );

        $handle = fopen($filePath, 'wb');
        fwrite($handle, $image->getContent());
        fclose($handle);
    }

    public function getImageFilePath(
        ResourceType $resourceType,
        string $resourceSlug,
        string $prefix = '',
        ImageAction $imageAction = ImageAction::get
    ): string {
        $filePath = $this->imageHelper->getImageDirectoryPath($resourceType);

        if ($resourceType === ResourceType::profile) {
            $filePath .= '/'.ImageHelper::PROFILE_MAIN_IMAGE_NAME;
        } elseif ($resourceSlug !== '') {
            $year = null;
            $formattedDate = null;

            if ($resourceType === ResourceType::news) {
                $news = $this->newsRepository->findOneBy(['slug' => $resourceSlug]);
                if (!$news instanceof News) {
                    throw new NewsNotFoundException();
                }

                $year = $news->getDate()->format('Y');
                $formattedDate = $news->getDate()->format('Y-m-d');
            }

            if ($resourceType === ResourceType::releases) {
                $release = $this->releaseRepository->findOneBy(['slug' => $resourceSlug]);
                if (!$release instanceof Release) {
                    throw new ReleaseNotFoundException();
                }

                $year = $release->getReleaseDate()->format('Y');
                $formattedDate = $release->getReleaseDate()->format('Y-m-d');
            }

            if (!$year || !$formattedDate) {
                throw new LogicException('Release date is invalid');
            }

            $filePath .= "/$year";
            if (
                (
                    $imageAction === ImageAction::create
                    || $imageAction === ImageAction::move
                )
                && !file_exists($filePath)
                && !mkdir($filePath, 0777, true) && !is_dir($filePath)
            ) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $filePath));
            }

            $filePath .= "/$formattedDate-$resourceSlug";

            if ($prefix !== '') {
                $filePath .= "-$prefix";
            }
        } else {
            throw new BadRequestHttpException();
        }

        $filePath .= ImageHelper::DEFAULT_IMAGE_EXTENSION;

        if ($imageAction === ImageAction::get && !file_exists($filePath)) {
            throw new NotFoundHttpException('File not found');
        }

        return $filePath;
    }

    public function moveImageFile(
        string $oldImagePath,
        ResourceType $resourceType,
        string $resourceSlug,
        string $prefix = ''
    ): void {
        if (!file_exists($oldImagePath)) {
            throw new NotFoundHttpException('File not found');
        }

        $newImagePath = $this->getImageFilePath($resourceType, $resourceSlug, $prefix, ImageAction::move);
        rename($oldImagePath, $newImagePath);

        $this->checkAndDeleteDirectory($oldImagePath);
    }

    public function deleteImageFile(ResourceType $resourceType, string $resourceSlug, string $prefix = ''): void
    {
        if ($resourceType === ResourceType::profile) {
            throw new BadRequestHttpException();
        }

        $filePath = $this->getImageFilePath($resourceType, $resourceSlug, $prefix, ImageAction::delete);
        if (!file_exists($filePath)) {
            $this->checkAndDeleteDirectory($filePath);

            return;
        }

        unlink($filePath);

        $this->checkAndDeleteDirectory($filePath);
    }

    private function checkAndDeleteDirectory(string $filePath): void
    {
        $directory = dirname($filePath);
        if (is_dir($directory) && count(scandir($directory)) === 2) {
            rmdir($directory);
        }
    }
}

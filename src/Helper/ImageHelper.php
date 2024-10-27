<?php

namespace App\Helper;

readonly class ImageHelper
{
    const DEFAULT_IMAGE_EXTENSION = '.jpg';
    const PROFILE_MAIN_IMAGE_NAME = 'profile-main'.self::DEFAULT_IMAGE_EXTENSION;

    public function __construct(private string $imagesPath)
    {
    }

    public function getImageDirectoryPath(string $resourceType): string
    {
        return $this->imagesPath.'/'.$resourceType;
    }
}

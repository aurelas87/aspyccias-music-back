<?php

namespace App\Helper;

use App\Model\Image\ResourceType;

readonly class ImageHelper
{
    const DEFAULT_IMAGE_EXTENSION = '.jpg';
    const PROFILE_MAIN_IMAGE_NAME = 'profile-main';

    public function __construct(private string $imagesPath)
    {
    }

    public function getImageDirectoryPath(ResourceType $resourceType): string
    {
        return $this->imagesPath.'/'.$resourceType->value;
    }
}

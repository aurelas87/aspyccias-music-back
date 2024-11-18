<?php

namespace App\Model\Image;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ImageMetadataDTO
{
    public function __construct(
        #[Assert\Choice(callback: [ResourceType::class, 'enumValues'])]
        public string $resourceType,

        #[Assert\When(
            expression: 'this.resourceType !== "profile"',
            constraints: [new Assert\NotBlank]
        )]
        public string $resourceSlug = '',
    ) {
    }
}

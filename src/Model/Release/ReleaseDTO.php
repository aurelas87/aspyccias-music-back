<?php

namespace App\Model\Release;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ReleaseDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $title = '',

        #[Assert\Choice(callback: [ReleaseTypeString::class, 'enumValues'])]
        public string $type = '',

        #[Assert\NotBlank]
        #[Assert\Date]
        public string $releaseDate = '',

        #[Assert\NotBlank]
        public string $slug = '',

        #[Assert\NotNull]
        public ?ReleaseTranslationsDTO $fr,

        #[Assert\NotNull]
        public ?ReleaseTranslationsDTO $en,

        public bool $artworkBackImage = false
    ) {
    }
}

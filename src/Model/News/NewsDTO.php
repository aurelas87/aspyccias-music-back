<?php

namespace App\Model\News;

use Symfony\Component\Validator\Constraints as Assert;

readonly class NewsDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Date]
        public string $date = '',

        #[Assert\NotBlank]
        public string $slug = '',

        #[Assert\NotNull]
        public ?NewsTranslationsDTO $fr = null,

        #[Assert\NotNull]
        public ?NewsTranslationsDTO $en = null,
    ) {
    }
}

<?php

namespace App\Model\News;

use Symfony\Component\Validator\Constraints as Assert;

readonly class NewsTranslationsDTO
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(max: 255)]
        public string $title = '',

        #[Assert\NotBlank]
        public string $content = '',
    ) {
    }
}

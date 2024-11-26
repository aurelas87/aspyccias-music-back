<?php

namespace App\Model\Release;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ReleaseTranslationsDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $description = '',
    ) {
    }
}

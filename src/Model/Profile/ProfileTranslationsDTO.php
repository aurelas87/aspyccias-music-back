<?php

namespace App\Model\Profile;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ProfileTranslationsDTO
{
    public function __construct(
        #[Assert\Length(max: 255)]
        public string $welcome,

        #[Assert\NotBlank]
        public string $description,
    ) {
    }
}

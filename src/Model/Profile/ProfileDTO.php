<?php

namespace App\Model\Profile;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ProfileDTO
{
    public function __construct(
        #[Assert\Length(max: 255)]
        public string $welcomeFr,

        #[Assert\NotBlank]
        public string $descriptionFr,

        #[Assert\Length(max: 255)]
        public string $welcomeEn,

        #[Assert\NotBlank]
        public string $descriptionEn,
    ) {
    }
}

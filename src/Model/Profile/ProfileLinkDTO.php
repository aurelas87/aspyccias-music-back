<?php

namespace App\Model\Profile;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ProfileLinkDTO
{

    public function __construct(
        #[Assert\Length(min: 3, max: 20)]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\Length(min: 18, max: 255)]
        #[Assert\Url]
        public string $link,
    ) {
    }
}

<?php

namespace App\Model\Profile;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ProfileDTO
{
    public function __construct(
        #[Assert\NotNull]
        public ?ProfileTranslationsDTO $fr,

        #[Assert\NotNull]
        public ?ProfileTranslationsDTO $en,
    ) {
    }
}

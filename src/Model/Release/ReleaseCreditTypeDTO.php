<?php

namespace App\Model\Release;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ReleaseCreditTypeDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $creditNameKey = '',

        #[Assert\NotBlank]
        public string $creditNameFr = '',

        #[Assert\NotBlank]
        public string $creditNameEn = '',
    ) {
    }
}

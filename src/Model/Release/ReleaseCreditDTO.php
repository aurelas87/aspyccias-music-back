<?php

namespace App\Model\Release;

use App\Validator\Release\ReleaseCreditType;
use Symfony\Component\Validator\Constraints as Assert;

readonly class ReleaseCreditDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $fullName = '',

        #[Assert\When(
            expression: 'this.link !== null',
            constraints: [
                new Assert\Length(min: 18, max: 255),
                new Assert\Url(),
            ]
        )]
        public ?string $link = null,

        #[Assert\NotBlank]
        #[ReleaseCreditType]
        public string $type = ''
    ) {
    }
}

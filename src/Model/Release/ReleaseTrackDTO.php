<?php

namespace App\Model\Release;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ReleaseTrackDTO
{
    public function __construct(
        #[Assert\NotBlank]
        public string $title = '',

        #[Assert\NotNull]
        #[Assert\GreaterThan(value: 0)]
        public ?int $position = null,

        #[Assert\NotNull]
        #[Assert\GreaterThan(value: 0)]
        public ?int $duration = null
    ) {
    }
}

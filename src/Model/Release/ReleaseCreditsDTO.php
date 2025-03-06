<?php

namespace App\Model\Release;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ReleaseCreditsDTO
{
    public function __construct(
        /**
         * @var ReleaseCreditDTO[]
         */
        #[Assert\NotNull]
        #[Assert\Valid]
        public ?array $credits,
    ) {
    }
}

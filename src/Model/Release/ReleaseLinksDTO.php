<?php

namespace App\Model\Release;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ReleaseLinksDTO
{
    public function __construct(
        /**
         * @var ReleaseLinkDTO[]
         */
        #[Assert\NotNull]
        #[Assert\Valid]
        public ?array $links,
    ) {
    }
}

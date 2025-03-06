<?php

namespace App\Model\Release;

use Symfony\Component\Validator\Constraints as Assert;

readonly class ReleaseTracksDTO
{
    public function __construct(
        /**
         * @var ReleaseTrackDTO[]
         */
        #[Assert\NotNull]
        #[Assert\Valid]
        public ?array $tracks,
    ) {
    }
}

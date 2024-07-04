<?php

namespace App\Service\Profile;

use App\Entity\Profile\ProfileLink;
use App\Repository\Profile\ProfileLinkRepository;

class ProfileLinkService
{
    private ProfileLinkRepository $profileLinkRepository;

    public function __construct(ProfileLinkRepository $profileLinkRepository)
    {
        $this->profileLinkRepository = $profileLinkRepository;
    }

    /** @return ProfileLink[] */
    public function listProfileLinks(): array
    {
        return $this->profileLinkRepository->findBy([], ['position' => 'ASC']);
    }
}

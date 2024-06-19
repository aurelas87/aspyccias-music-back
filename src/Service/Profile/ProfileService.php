<?php

namespace App\Service\Profile;

use App\Entity\Profile\Profile;
use App\Exception\Profile\ProfileNotFoundException;
use App\Repository\Profile\ProfileRepository;

class ProfileService
{
    private ProfileRepository $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function getProfile(string $locale): Profile
    {
        $profile = $this->profileRepository->findProfileByLocale($locale);
        if (!$profile instanceof Profile) {
            throw new ProfileNotFoundException();
        }

        return $profile;
    }
}

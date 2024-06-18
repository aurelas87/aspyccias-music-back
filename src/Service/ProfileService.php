<?php

namespace App\Service;

use App\Entity\Profile;
use App\Exception\Profile\ProfileNotFoundException;
use App\Repository\ProfileRepository;

class ProfileService
{
    private ProfileRepository $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    public function getAspycciasProfile(): Profile
    {
        $profile = $this->profileRepository->findProfileByName(Profile::DEFAULT_NAME);
        if (!$profile instanceof Profile) {
            throw new ProfileNotFoundException(Profile::DEFAULT_NAME);
        }

        return $profile;
    }
}

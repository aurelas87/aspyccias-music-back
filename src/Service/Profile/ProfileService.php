<?php

namespace App\Service\Profile;

use App\Entity\Profile\Profile;
use App\Exception\Profile\ProfileNotFoundException;
use App\Model\Profile\ProfileDTO;
use App\Repository\Profile\ProfileRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProfileService
{
    private ProfileRepository $profileRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ProfileRepository $profileRepository, EntityManagerInterface $entityManager)
    {
        $this->profileRepository = $profileRepository;
        $this->entityManager = $entityManager;
    }

    public function getProfile(string $locale): Profile
    {
        $profile = $this->profileRepository->findOneBy(['locale' => $locale]);
        if (!$profile instanceof Profile) {
            throw new ProfileNotFoundException();
        }

        return $profile;
    }

    public function updateProfile(ProfileDTO $profileDTO): void
    {
        $profileFr = $this->getProfile('fr');
        $profileFr->setWelcome($profileDTO->welcomeFr);
        $profileFr->setDescription($profileDTO->descriptionFr);

        $profileEn = $this->getProfile('en');
        $profileEn->setWelcome($profileDTO->welcomeEn);
        $profileEn->setDescription($profileDTO->descriptionEn);

        $this->entityManager->flush();
    }
}

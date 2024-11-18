<?php

namespace App\Service\Profile;

use App\Entity\Profile\Profile;
use App\Exception\Profile\ProfileNotFoundException;
use App\Model\Profile\ProfileDTO;
use App\Repository\Profile\ProfileRepository;
use App\Service\EntitySanitizer;
use Doctrine\ORM\EntityManagerInterface;

class ProfileService
{
    private ProfileRepository $profileRepository;
    private EntityManagerInterface $entityManager;
    private EntitySanitizer $entitySanitizer;

    public function __construct(
        ProfileRepository $profileRepository,
        EntityManagerInterface $entityManager,
        EntitySanitizer $htmlSanitizer
    ) {
        $this->profileRepository = $profileRepository;
        $this->entityManager = $entityManager;
        $this->entitySanitizer = $htmlSanitizer;
    }

    public function getProfile(string $locale): Profile
    {
        $profile = $this->profileRepository->findOneBy(['locale' => $locale]);
        if (!$profile instanceof Profile) {
            throw new ProfileNotFoundException();
        }

        return $profile;
    }

    /**
     * @return Profile[]
     */
    public function getProfileForAdmin(): array
    {
        $profileFr = $this->getProfile('fr');
        $profileEn = $this->getProfile('en');

        return [
            'fr' => $profileFr,
            'en' => $profileEn,
        ];
    }

    public function updateProfile(ProfileDTO $profileDTO): void
    {
        $profileFr = $this->getProfile('fr');
        $profileFr->setWelcome($profileDTO->fr->welcome);
        $profileFr->setDescription($profileDTO->fr->description);

        $profileEn = $this->getProfile('en');
        $profileEn->setWelcome($profileDTO->en->welcome);
        $profileEn->setDescription($profileDTO->en->description);

        $this->entitySanitizer->sanitizeEntity($profileFr);
        $this->entitySanitizer->sanitizeEntity($profileEn);

        $this->entityManager->flush();
    }
}

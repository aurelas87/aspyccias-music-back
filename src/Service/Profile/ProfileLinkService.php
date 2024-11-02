<?php

namespace App\Service\Profile;

use App\Entity\Profile\ProfileLink;
use App\Model\Profile\ProfileLinkDTO;
use App\Repository\Profile\ProfileLinkRepository;
use Doctrine\ORM\EntityManagerInterface;

class ProfileLinkService
{
    private ProfileLinkRepository $profileLinkRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(ProfileLinkRepository $profileLinkRepository, EntityManagerInterface $entityManager)
    {
        $this->profileLinkRepository = $profileLinkRepository;
        $this->entityManager = $entityManager;
    }

    /** @return ProfileLink[] */
    public function listProfileLinks(): array
    {
        return $this->profileLinkRepository->findBy([], ['position' => 'ASC']);
    }

    public function addProfileLink(ProfileLinkDTO $profileLinkDTO): void
    {
        $maxPosition = $this->profileLinkRepository->findMaxPosition();

        $profileLink = new ProfileLink();
        $profileLink->setName($profileLinkDTO->name)
            ->setLink($profileLinkDTO->link)
            ->setPosition(++$maxPosition);

        $this->entityManager->persist($profileLink);
        $this->entityManager->flush();
    }

    public function editProfileLink(ProfileLink $profileLink, ProfileLinkDTO $profileLinkDTO): void
    {
        $profileLink->setName($profileLinkDTO->name)
            ->setLink($profileLinkDTO->link);

        $this->entityManager->flush();
    }
}

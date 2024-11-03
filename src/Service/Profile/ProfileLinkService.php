<?php

namespace App\Service\Profile;

use App\Entity\Profile\ProfileLink;
use App\Model\DirectionType;
use App\Model\Profile\ProfileLinkDTO;
use App\Repository\Profile\ProfileLinkRepository;
use Doctrine\Common\Collections\Criteria;
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

    public function moveProfileLink(ProfileLink $profileLink, DirectionType $direction): void
    {
        $currentPosition = $profileLink->getPosition();
        $maxPosition = $this->profileLinkRepository->findMaxPosition();

        if ($direction === DirectionType::up) {
            if ($currentPosition === 1) {
                return;
            }

            $newPosition = $currentPosition - 1;
        } else {
            if ($currentPosition === $maxPosition) {
                return;
            }

            $newPosition = $currentPosition + 1;
        }

        $profileLink->setPosition($maxPosition + 1);

        $profileLinkToSwitchWith = $this->profileLinkRepository->findOneBy(['position' => $newPosition]);
        $profileLinkToSwitchWith->setPosition($currentPosition);
        $this->entityManager->flush();

        $profileLink->setPosition($newPosition);
        $this->entityManager->flush();
    }

    public function deleteProfileLink(ProfileLink $profileLink): void
    {
        $deletedPosition = $profileLink->getPosition();

        $this->entityManager->remove($profileLink);
        $this->entityManager->flush();

        $criteria = new Criteria();
        $criteria->where(Criteria::expr()->gt('position', $deletedPosition));
        $profileLinksToFix = $this->profileLinkRepository->matching($criteria);

        $newPosition = $deletedPosition;
        foreach ($profileLinksToFix as $profileLinkToFix) {
            $profileLinkToFix->setPosition($newPosition);
            $newPosition++;
        }

        $this->entityManager->flush();
    }
}

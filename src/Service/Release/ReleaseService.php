<?php

namespace App\Service\Release;

use App\Entity\Release\Release;
use App\Entity\Release\ReleaseCredit;
use App\Entity\Release\ReleaseCreditType;
use App\Entity\Release\ReleaseTrack;
use App\Entity\Release\ReleaseTranslation;
use App\Exception\Release\ReleaseNotFoundException;
use App\Helper\PaginationHelper;
use App\Model\PaginatedList;
use App\Model\Release\ReleaseCreditsDTO;
use App\Model\Release\ReleaseDTO;
use App\Model\Release\ReleaseTracksDTO;
use App\Model\Release\ReleaseType;
use App\Repository\Release\ReleaseCreditTypeRepository;
use App\Repository\Release\ReleaseRepository;
use App\Service\EntitySanitizer;
use Doctrine\ORM\EntityManagerInterface;

class ReleaseService
{
    private ReleaseRepository $releaseRepository;
    private ReleaseCreditTypeRepository $releaseCreditTypeRepository;
    private EntityManagerInterface $entityManager;
    private EntitySanitizer $entitySanitizer;

    public function __construct(
        ReleaseRepository $releaseRepository,
        ReleaseCreditTypeRepository $releaseCreditTypeRepository,
        EntityManagerInterface $entityManager,
        EntitySanitizer $entitySanitizer
    ) {
        $this->releaseRepository = $releaseRepository;
        $this->entityManager = $entityManager;
        $this->entitySanitizer = $entitySanitizer;
        $this->releaseCreditTypeRepository = $releaseCreditTypeRepository;
    }

    /**
     * @return Release[]
     */
    public function listReleases(string $locale, ReleaseType $releaseType): array
    {
        return $this->releaseRepository->findByTypeLocalized($releaseType, $locale);
    }

    public function listReleasesForAdmin(array $options): PaginatedList
    {
        $paginationHelper = new PaginationHelper();
        $paginationHelper->parseQueryParameters($options, true);

        $releasesItems = $this->releaseRepository->findPaginated(
            $paginationHelper->getOffset(),
            $paginationHelper->getLimit(),
            'releaseDate',
            PaginationHelper::DEFAULT_SORT_ORDER
        );

        return $paginationHelper->mapItemsToPaginatedList($releasesItems);
    }

    public function getReleaseDetails(string $slug, string $locale): Release
    {
        $release = $this->releaseRepository->findOneBySlugLocalized($slug, $locale);
        if (!$release instanceof Release) {
            throw new ReleaseNotFoundException();
        }

        return $release;
    }

    /**
     * @throws \DateMalformedStringException
     */
    public function addRelease(ReleaseDTO $releaseDTO): void
    {
        $release = new Release();

        $release->setReleaseDate(new \DateTimeImmutable($releaseDTO->releaseDate));
        $release->setTitle($releaseDTO->title);
        $release->setType(ReleaseType::tryFromName($releaseDTO->type));
        $release->setSlug($releaseDTO->slug);
        $release->addTranslation(
            (new ReleaseTranslation())
                ->setLocale('fr')
                ->setDescription($releaseDTO->fr->description)
        );
        $release->addTranslation(
            (new ReleaseTranslation())
                ->setLocale('en')
                ->setDescription($releaseDTO->en->description)
        );
        $release->setArtworkBackImage($releaseDTO->artworkBackImage);

        $this->entitySanitizer->sanitizeEntity($release);

        $this->entityManager->persist($release);
        $this->entityManager->flush();
    }

    public function editRelease(Release $release, ReleaseDTO $releaseDTO): void
    {
        $release->setReleaseDate(new \DateTimeImmutable($releaseDTO->releaseDate));
        $release->setTitle($releaseDTO->title);
        $release->setType(ReleaseType::tryFromName($releaseDTO->type));
        $release->setSlug($releaseDTO->slug);

        foreach ($release->getTranslations() as $releaseTranslation) {
            if ($releaseTranslation->getLocale() === 'fr') {
                $releaseTranslation->setDescription($releaseDTO->fr->description);
            } else {
                $releaseTranslation->setDescription($releaseDTO->en->description);
            }
        }

        $release->setArtworkBackImage($releaseDTO->artworkBackImage);

        $this->entitySanitizer->sanitizeEntity($release);

        $this->entityManager->flush();
    }

    public function editTracks(Release $release, ReleaseTracksDTO $releaseTracksDTO): void
    {
        foreach ($release->getTracks() as $releaseTrack) {
            $release->removeTrack($releaseTrack);
        }

        $this->entityManager->flush();

        foreach ($releaseTracksDTO->tracks as $releaseTrackDTO) {
            $release->addTrack(
                (new ReleaseTrack())
                    ->setTitle($releaseTrackDTO->title)
                    ->setPosition($releaseTrackDTO->position)
                    ->setDuration($releaseTrackDTO->duration)
            );
        }

        $this->entityManager->flush();
    }

    public function editCredits(Release $release, ReleaseCreditsDTO $releaseCreditsDTO): void
    {
        foreach ($release->getCredits() as $releaseCredit) {
            $release->removeCredit($releaseCredit);
        }

        $this->entityManager->flush();

        foreach ($releaseCreditsDTO->credits as $releaseCreditDTO) {
            $releaseCreditType = $this->releaseCreditTypeRepository
                ->findOneBy(['creditNameKey' => $releaseCreditDTO->type]);

            if (!$releaseCreditType instanceof ReleaseCreditType) {
                continue;
            }

            $release->addCredit(
                (new ReleaseCredit())
                    ->setReleaseCreditType($releaseCreditType)
                    ->setFullName($releaseCreditDTO->fullName)
                    ->setLink($releaseCreditDTO->link)
            );
        }

        $this->entityManager->flush();
    }

    public function deleteRelease(Release $release): void
    {
        $this->entityManager->remove($release);
        $this->entityManager->flush();
    }
}

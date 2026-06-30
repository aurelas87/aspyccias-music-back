<?php

namespace App\Service\Release;

use App\Entity\Release\Release;
use App\Entity\Release\ReleaseCredit;
use App\Entity\Release\ReleaseCreditType;
use App\Entity\Release\ReleaseLink;
use App\Entity\Release\ReleaseLinkName;
use App\Entity\Release\ReleaseTrack;
use App\Entity\Release\ReleaseTranslation;
use App\Exception\Release\ReleaseNotFoundException;
use App\Helper\PaginationHelper;
use App\Model\PaginatedList;
use App\Model\Release\ReleaseCreditsDTO;
use App\Model\Release\ReleaseDTO;
use App\Model\Release\ReleaseLinkCategory;
use App\Model\Release\ReleaseLinksDTO;
use App\Model\Release\ReleaseTracksDTO;
use App\Model\Release\ReleaseType;
use App\Repository\Release\ReleaseCreditTypeRepository;
use App\Repository\Release\ReleaseLinkNameRepository;
use App\Repository\Release\ReleaseRepository;
use App\Service\EntitySanitizer;
use DateMalformedStringException;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class ReleaseService
{
    private ReleaseRepository $releaseRepository;
    private ReleaseCreditTypeRepository $releaseCreditTypeRepository;
    private ReleaseLinkNameRepository $releaseLinkNameRepository;
    private EntityManagerInterface $entityManager;
    private EntitySanitizer $entitySanitizer;

    public function __construct(
        ReleaseRepository $releaseRepository,
        ReleaseCreditTypeRepository $releaseCreditTypeRepository,
        ReleaseLinkNameRepository $releaseLinkNameRepository,
        EntityManagerInterface $entityManager,
        EntitySanitizer $entitySanitizer
    ) {
        $this->releaseRepository = $releaseRepository;
        $this->releaseCreditTypeRepository = $releaseCreditTypeRepository;
        $this->releaseLinkNameRepository = $releaseLinkNameRepository;
        $this->entityManager = $entityManager;
        $this->entitySanitizer = $entitySanitizer;
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
     * @throws DateMalformedStringException
     */
    public function addRelease(ReleaseDTO $releaseDTO): void
    {
        $release = new Release();

        $release->setReleaseDate(new DateTimeImmutable($releaseDTO->releaseDate));
        $release->setTitle($releaseDTO->title);
        $release->setType(ReleaseType::tryFromName($releaseDTO->type));
        $release->setSlug($releaseDTO->slug);
        $release->addTranslation(
            new ReleaseTranslation()
                ->setLocale('fr')
                ->setDescription($releaseDTO->fr->description)
        );
        $release->addTranslation(
            new ReleaseTranslation()
                ->setLocale('en')
                ->setDescription($releaseDTO->en->description)
        );
        $release->setArtworkBackImage($releaseDTO->artworkBackImage);

        $this->entitySanitizer->sanitizeEntity($release);

        $this->entityManager->persist($release);
        $this->entityManager->flush();
    }

    /**
     * @throws DateMalformedStringException
     */
    public function editRelease(Release $release, ReleaseDTO $releaseDTO): void
    {
        $release->setReleaseDate(new DateTimeImmutable($releaseDTO->releaseDate));
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

        $releaseTracksAdded = [];

        foreach ($releaseTracksDTO->tracks as $releaseTrackDTO) {
            $uniqueKey = strtolower($releaseTrackDTO->title);
            if (in_array($uniqueKey, $releaseTracksAdded, true)) {
                continue;
            }

            $releaseTracksAdded[] = $uniqueKey;

            $newReleaseTrack = new ReleaseTrack()
                ->setTitle($releaseTrackDTO->title)
                ->setPosition($releaseTrackDTO->position)
                ->setDuration($releaseTrackDTO->duration);

            $this->entitySanitizer->sanitizeEntity($newReleaseTrack);

            $release->addTrack($newReleaseTrack);
        }

        $this->entityManager->flush();
    }

    public function editCredits(Release $release, ReleaseCreditsDTO $releaseCreditsDTO): void
    {
        foreach ($release->getCredits() as $releaseCredit) {
            $release->removeCredit($releaseCredit);
        }

        $this->entityManager->flush();

        $releaseCreditsAdded = [];

        foreach ($releaseCreditsDTO->credits as $releaseCreditDTO) {
            $releaseCreditType = $this->releaseCreditTypeRepository
                ->findOneBy(['creditNameKey' => $releaseCreditDTO->type]);

            if (!$releaseCreditType instanceof ReleaseCreditType) {
                continue;
            }

            $uniqueKey = $releaseCreditDTO->type.'_'.strtolower($releaseCreditDTO->fullName);
            if (in_array($uniqueKey, $releaseCreditsAdded, true)) {
                continue;
            }

            $releaseCreditsAdded[] = $uniqueKey;

            $newReleaseCredit = new ReleaseCredit();
            $newReleaseCredit->setReleaseCreditType($releaseCreditType)
                ->setFullName($releaseCreditDTO->fullName)
                ->setLink($releaseCreditDTO->link);

            $this->entitySanitizer->sanitizeEntity($newReleaseCredit);

            $release->addCredit($newReleaseCredit);
        }

        $this->entityManager->flush();
    }

    public function editLinks(Release $release, ReleaseLinksDTO $releaseLinksDTO): void
    {
        foreach ($release->getLinks() as $releaseLink) {
            $release->removeLink($releaseLink);
        }

        $this->entityManager->flush();

        $releaseLinksAdded = [];

        foreach ($releaseLinksDTO->links as $releaseLinkDTO) {
            $releaseLinkName = $this->releaseLinkNameRepository
                ->findOneBy(['linkName' => $releaseLinkDTO->name]);

            if (!$releaseLinkName instanceof ReleaseLinkName) {
                continue;
            }

            $uniqueKey = $releaseLinkDTO->category.'_'.$releaseLinkDTO->name;
            if (in_array($uniqueKey, $releaseLinksAdded, true)) {
                continue;
            }

            $releaseLinksAdded[] = $uniqueKey;

            $newReleaseLink = new ReleaseLink();
            $newReleaseLink->setCategory(ReleaseLinkCategory::tryFromName($releaseLinkDTO->category))
                ->setReleaseLinkName($releaseLinkName)
                ->setLink($releaseLinkDTO->link)
                ->setEmbedded($releaseLinkDTO->embedded);

            $this->entitySanitizer->sanitizeEntity($newReleaseLink);

            $release->addLink($newReleaseLink);
        }

        $this->entityManager->flush();
    }

    public function deleteRelease(Release $release): void
    {
        $this->entityManager->remove($release);
        $this->entityManager->flush();
    }
}

<?php

namespace App\Service\Release;

use App\Entity\Release\Release;
use App\Exception\Release\ReleaseNotFoundException;
use App\Model\Release\ReleaseType;
use App\Repository\Release\ReleaseRepository;

class ReleaseService
{
    private ReleaseRepository $releaseRepository;

    public function __construct(ReleaseRepository $releaseRepository)
    {
        $this->releaseRepository = $releaseRepository;
    }

    /**
     * @return Release[]
     */
    public function listReleases(string $locale, ReleaseType $releaseType): array
    {
        return $this->releaseRepository->findByTypeLocalized($releaseType, $locale);
    }

    public function getReleaseDetails(string $slug, string $locale): Release
    {
        $release = $this->releaseRepository->findOneBySlugLocalized($slug, $locale);
        if (!$release instanceof Release) {
            throw new ReleaseNotFoundException();
        }

        return $release;
    }
}

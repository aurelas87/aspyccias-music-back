<?php

namespace App\Tests\Service\Release;

use App\Exception\Release\ReleaseNotFoundException;
use App\Model\Release\ReleaseType;
use App\Repository\Release\ReleaseRepository;
use App\Service\Release\ReleaseService;
use App\Tests\Commons\ExpectedReleasesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ReleaseServiceTest extends KernelTestCase
{
    use ExpectedReleasesTrait;

    private ReleaseService $releaseService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->releaseService = $this->getContainer()->get(ReleaseService::class);
    }

    /**
     * @throws \Exception
     */
    public function dataProviderListReleases(): array
    {
        return $this->buildReleaseListUseCases();
    }

    /**
     * @dataProvider dataProviderListReleases
     */
    public function testListReleases(
        string $locale,
        ReleaseType $type,
        int $nbItems,
        array $items,
    ): void {
        $releaseList = $this->releaseService->listReleases($locale, $type);

        static::assertCount($nbItems, $releaseList);

        foreach ($items as $indexItem => $item) {
            $currentItem = $releaseList[$indexItem];

            static::assertSame($item['slug'], $currentItem->getSlug());
            static::assertSame($item['release_date'], $currentItem->getReleaseDate()->format(\DateTimeInterface::ATOM));
            static::assertSame($item['title'], $currentItem->getTitle());
            static::assertSame($item['artwork_front_image'], $currentItem->getArtworkFrontImage());
        }
    }

    public function testListReleasesEmpty(): void
    {
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $allSingles = $this->getContainer()->get(ReleaseRepository::class)->findBy(['type' => ReleaseType::single]);
        foreach ($allSingles as $single) {
            $manager->remove($single);
        }
        $manager->flush();

        $releaseList = $this->releaseService->listReleases('fr', ReleaseType::single);

        static::assertCount(0, $releaseList);
    }

    public function dataProviderListReleasesWithInvalidType(): array
    {
        return self::INVALID_TYPE_USE_CASES;
    }

    /**
     * @throws \Exception
     */
    public function dataProviderGetReleaseDetails(): array
    {
        return $this->buildReleaseDetailsUseCases();
    }

    /**
     * @dataProvider dataProviderGetReleaseDetails
     */
    public function testGetReleaseDetails(string $locale, array $release): void
    {
        $releaseDetails = $this->releaseService->getReleaseDetails($release['slug'], $locale);

        static::assertSame($release['slug'], $releaseDetails->getSlug());
        static::assertSame(
            $release['release_date'],
            $releaseDetails->getReleaseDate()->format(\DateTimeInterface::ATOM)
        );
        static::assertSame($release['title'], $releaseDetails->getTitle());
        static::assertSame($release['artwork_front_image'], $releaseDetails->getArtworkFrontImage());
        static::assertSame($release['artwork_back_image'], $releaseDetails->getArtworkBackImage());

        static::assertCount(1, $releaseDetails->getTranslations());
        static::assertSame($release['description'], $releaseDetails->getTranslations()->first()->getDescription());

        static::assertCount(\count($release['credits']), $releaseDetails->getCredits());
        foreach ($release['credits'] as $indexCredit => $credit) {
            $currentCredit = $releaseDetails->getCredits()->get($indexCredit);

            static::assertSame($credit['type'], $currentCredit->getReleaseCreditType()->getTranslations()->first()->getCreditName());
            static::assertSame($credit['full_name'], $currentCredit->getFullName());
            static::assertSame($credit['link'], $currentCredit->getLink());
        }

        static::assertCount(\count($release['links']), $releaseDetails->getLinks());
        foreach ($release['links'] as $indexLink => $link) {
            $currentLink = $releaseDetails->getLinks()->get($indexLink);

            static::assertSame($link['category'], $currentLink->getCategory()->name);
            static::assertSame($link['name'], $currentLink->getReleaseLinkName()->getLinkName());
            static::assertSame($link['link'], $currentLink->getLink());
            static::assertSame($link['embedded'], $currentLink->getEmbedded());
        }

        static::assertCount(\count($release['tracks']), $releaseDetails->getTracks());
        foreach ($release['tracks'] as $indexTrack => $track) {
            $currentTrack = $releaseDetails->getTracks()->get($indexTrack);

            static::assertSame($track['title'], $currentTrack->getTitle());
            static::assertSame($track['position'], $currentTrack->getPosition());
            static::assertSame($track['duration'], $currentTrack->getDuration());
        }
    }

    public function testGetReleaseDetailsNotFound(): void
    {
        static::expectException(ReleaseNotFoundException::class);
        static::expectExceptionMessageMatches('/^errors\.release\.not_found$/');

        $this->releaseService->getReleaseDetails('release-title-14', 'fr');
    }
}

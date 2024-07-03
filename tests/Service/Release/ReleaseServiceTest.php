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
        string $type,
        int $nbItems,
        array $items,
    ): void {
        $releaseList = $this->releaseService->listReleases($locale, ['type' => $type]);

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

        $releaseList = $this->releaseService->listReleases('fr', ['type' => 'single']);

        static::assertCount(0, $releaseList);
    }

    public function dataProviderListReleasesWithInvalidType(): array
    {
        return self::INVALID_TYPE_USE_CASES;
    }

    /**
     * @dataProvider dataProviderListReleasesWithInvalidType
     */
    public function testListReleasesWithInvalidType(
        array $queryParameters,
        string $expectedExceptionClass,
        string $expectedExceptionMessage
    ): void {
        try {
            $this->releaseService->listReleases('fr', $queryParameters);
        } catch (\Throwable $e) {
            static::assertTrue($e instanceof $expectedExceptionClass);
            static::assertSame($expectedExceptionMessage, $e->getMessage());
        }
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
    public function testGetNewsDetails(string $locale, array $release): void
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

            static::assertSame($credit['type'], $currentCredit->getReleaseCreditType()->getCreditName());
            static::assertSame($credit['full_name'], $currentCredit->getFullName());
            static::assertSame($credit['link'], $currentCredit->getLink());
        }
    }

    public function testGetReleaseDetailsNotFound(): void
    {
        try {
            $this->releaseService->getReleaseDetails('release-title-14', 'fr');
        } catch (ReleaseNotFoundException $e) {
            static::assertSame('errors.release.not_found', $e->getMessage());
        }
    }
}

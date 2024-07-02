<?php

namespace App\Tests\Service\Release;

use App\Entity\Release\Release;
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

        for ($itemIndex = 0; $itemIndex < \count($releaseList); $itemIndex++) {
            $currentItem = $releaseList[$itemIndex];

            static::assertTrue($currentItem instanceof Release);
            static::assertSame(
                $items[$itemIndex]['release_date'],
                $currentItem->getReleaseDate()->format(\DateTimeInterface::ATOM)
            );
            static::assertSame($items[$itemIndex]['title'], $currentItem->getTitle());
            static::assertSame($items[$itemIndex]['artwork_front_image'], $currentItem->getArtworkFrontImage());
            static::assertSame($items[$itemIndex]['artwork_back_image'], $currentItem->getArtworkBackImage());
            static::assertCount(1, $currentItem->getTranslations());
            static::assertSame(
                $items[$itemIndex]['description'],
                $currentItem->getTranslations()->first()->getDescription()
            );
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
}

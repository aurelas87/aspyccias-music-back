<?php

namespace App\Tests\Commons;

use App\DataFixtures\Release\ReleaseFixture;
use App\Exception\Release\InvalidReleaseTypeOptionException;
use App\Exception\Release\MissingReleaseTypeOptionException;
use App\Model\Release\ReleaseType;

trait ExpectedReleasesTrait
{
    private const INVALID_TYPE_USE_CASES = [
        'No type sent' => [
            'queryParameters' => [],
            'expectedException' => MissingReleaseTypeOptionException::class,
            'expectedExceptionMessage' => 'errors.release.type.missing',
        ],
        'Empty type' => [
            'queryParameters' => ['type' => ''],
            'expectedException' => InvalidReleaseTypeOptionException::class,
            'expectedExceptionMessage' => 'errors.release.type.invalid',
        ],
        'Unknown type' => [
            'queryParameters' => ['type' => 'unknown'],
            'expectedException' => InvalidReleaseTypeOptionException::class,
            'expectedExceptionMessage' => 'errors.release.type.invalid',
        ],
        'Invalid type' => [
            'queryParameters' => ['type' => 21],
            'expectedException' => InvalidReleaseTypeOptionException::class,
            'expectedExceptionMessage' => 'errors.release.type.invalid',
        ],
    ];

    /**
     * @throws \Exception
     */
    private function buildReleaseItem(int $releaseId, string $locale, bool $expectDetails = false): array
    {
        $releaseDate = new \DateTimeImmutable(ReleaseFixture::START_DATE);
        if ($releaseId > 1) {
            $releaseDate = $releaseDate->add(new \DateInterval('P'.($releaseId - 1).'M'));
        }

        $releaseSlug = "release-title-$releaseId";

        $releaseItem = [
            'slug' => $releaseSlug,
            'release_date' => $releaseDate->format(\DateTimeInterface::ATOM),
            'title' => "Release Title $releaseId",
            'artwork_front_image' => "$releaseSlug-front-cover",
        ];

        if ($expectDetails) {
            $releaseItem['artwork_back_image'] = "$releaseSlug-back-cover";

            $releaseItem['credits'] = [
                ['full_name' => 'John Composer', 'link' => 'https://www.aspyccias-music.com', 'type' => 'composer'],
                ['full_name' => 'John Producer', 'link' => null, 'type' => 'producer'],
                ['full_name' => 'John Lyricist', 'link' => null, 'type' => 'lyricist'],
                ['full_name' => 'John Editor', 'link' => null, 'type' => 'editor'],
                ['full_name' => 'John Violinist', 'link' => null, 'type' => 'violinist'],
                ['full_name' => 'John Voice', 'link' => null, 'type' => 'voice'],
            ];

            $releaseItem['links'] = [
                ['type' => 'listen', 'link' => 'https://www.youtube.com/watch?v=kQWUmBwZCKY', 'embedded' => null],
                ['type' => 'buy', 'link' => 'https://aspyccias.bandcamp.com/track/in-a-spaceship', 'embedded' => null],
                [
                    'type' => 'smart_link',
                    'link' => null,
                    'embedded' => '<div style="max-width:100%;">'.
                        '<div style="position:relative;padding-bottom:calc(56.25% + 52px);height: 0;">'.
                        '<iframe style="position:absolute;top:0;left:0;" width="100%" height="100%" '.
                        'src="https://odesli.co/embed/?url=https%3A%2F%2Fsong.link%2Fcxdvh54nmjqct&theme=light" '.
                        'frameborder="0" allowfullscreen sandbox="allow-same-origin allow-scripts allow-presentation '.
                        'allow-popups allow-popups-to-escape-sandbox" allow="clipboard-read; clipboard-write">'.
                        '</iframe></div></div>',
                ],
            ];

            $releaseItem['description'] = ($locale === 'fr' ? 'Description de la sortie ' : 'Release description ').$releaseId;
        }

        return $releaseItem;
    }

    /**
     * @throws \Exception
     */
    private function buildReleaseListUseCases(): array
    {
        $useCases = [];

        // Expect each page in "en" and "fr"
        foreach (['en', 'fr'] as $locale) {
            $useCases[ReleaseType::single->name." $locale"] = [
                'locale' => $locale,
                'type' => ReleaseType::single->name,
                'nbItems' => ReleaseFixture::TOTAL_SINGLES,
                'items' => [],
            ];
            $useCases[ReleaseType::ep->name." $locale"] = [
                'locale' => $locale,
                'type' => ReleaseType::ep->name,
                'nbItems' => ReleaseFixture::TOTAL_EPS,
                'items' => [],
            ];
            $useCases[ReleaseType::album->name." $locale"] = [
                'locale' => $locale,
                'type' => ReleaseType::album->name,
                'nbItems' => ReleaseFixture::TOTAL_ALBUMS,
                'items' => [],
            ];

            $releaseTypeName = ReleaseType::album->name;

            for ($indexRelease = ReleaseFixture::TOTAL_RELEASES; $indexRelease > 0; $indexRelease--) {
                if ($indexRelease <= (ReleaseFixture::TOTAL_SINGLES + ReleaseFixture::TOTAL_EPS)) {
                    $releaseTypeName = ReleaseType::ep->name;
                }

                if ($indexRelease <= ReleaseFixture::TOTAL_SINGLES) {
                    $releaseTypeName = ReleaseType::single->name;
                }

                $useCases["$releaseTypeName $locale"]['items'][] = $this->buildReleaseItem($indexRelease, $locale);
            }
        }

        return $useCases;
    }

    /**
     * @throws \Exception
     */
    private function buildReleaseDetailsUseCases(): array
    {
        $useCases = [];

        foreach (['en', 'fr'] as $locale) {
            foreach ([9, 6, 1] as $releaseId) {
                $useCases["Release $releaseId $locale"] = [
                    'locale' => $locale,
                    'release' => $this->buildReleaseItem($releaseId, $locale, true),
                ];
            }
        }

        return $useCases;
    }
}

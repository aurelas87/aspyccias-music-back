<?php

namespace App\Tests\Commons;

use App\DataFixtures\News\NewsFixtures;
use App\Helper\PaginationHelper;

trait ExpectedNewsTrait
{
    /**
     * @throws \Exception
     */
    private static function buildNewsItem(int $newsId, string $locale, bool $expectDetails = false): array
    {
        $newsDate = new \DateTimeImmutable(NewsFixtures::START_DATE);
        if ($newsId > 1) {
            $newsDate = $newsDate->add(new \DateInterval('P'.($newsId - 1).'D'));
        }

        $newsItem = [
            'slug' => "news-title-$newsId",
            'date' => $newsDate->format(\DateTimeInterface::ATOM),
            'title' => ($locale === 'fr' ? "Titre de l'actualité " : 'News Title ').$newsId,
        ];

        if ($expectDetails) {
            $newsItem['content'] = ($locale === 'fr' ? "Contenu de l'actualité " : 'News Content ').$newsId;
        }

        return $newsItem;
    }

    /**
     * @throws \Exception
     */
    private static function buildNewsItemsArray(string $locale, int $nbItems, ?int $offset = null): array
    {
        $newsItems = [];

        for ($indexItem = 0; $indexItem < $nbItems; $indexItem++) {
            $itemId = NewsFixtures::TOTAL_NEWS - $indexItem;
            if (!\is_null($offset)) {
                $itemId -= $offset;
            }

            $newsItems[] = self::buildNewsItem($itemId, $locale);
        }

        return $newsItems;
    }

    /**
     * @throws \Exception
     */
    private static function buildNewsListPagesUseCases(): array
    {
        $useCases = [];

        $nbPages = \ceil(NewsFixtures::TOTAL_NEWS / PaginationHelper::DEFAULT_LIMIT);

        // Expect each page in "en" and "fr"
        foreach (['en', 'fr'] as $locale) {
            for ($indexPage = 1; $indexPage <= $nbPages; $indexPage++) {
                $useCaseName = "Page $indexPage $locale";
                $offset = ($indexPage - 1) * PaginationHelper::DEFAULT_LIMIT;
                $nbItems = \min(NewsFixtures::TOTAL_NEWS - $offset, PaginationHelper::DEFAULT_LIMIT);

                $useCases[$useCaseName] = [
                    'locale' => $locale,
                    'offset' => $offset,
                    'nbItems' => $nbItems,
                    'items' => self::buildNewsItemsArray($locale, $nbItems, $offset),
                    'previousOffset' => $indexPage > 1 ? $offset - PaginationHelper::DEFAULT_LIMIT : null,
                    'nextOffset' => $indexPage < $nbPages ? $offset + PaginationHelper::DEFAULT_LIMIT : null,
                ];
            }
        }

        return $useCases;
    }

    /**
     * @throws \Exception
     */
    private static function buildLatestNewsUseCases(): array
    {
        $useCases = [];

        $nbItems = 3;

        // Expect each page in "en" and "fr"
        foreach (['en', 'fr'] as $locale) {
            $useCaseName = "Latest $locale";

            $useCases[$useCaseName] = [
                'locale' => $locale,
                'nbItems' => $nbItems,
                'items' => self::buildNewsItemsArray($locale, $nbItems),
            ];
        }

        return $useCases;
    }

    /**
     * @throws \Exception
     */
    private static function buildNewsDetailsUseCases(): array
    {
        $useCases = [];

        foreach (['en', 'fr'] as $locale) {
            foreach ([13, 6, 1] as $newsId) {
                $useCases["News $newsId $locale"] = [
                    'locale' => $locale,
                    'news' => self::buildNewsItem($newsId, $locale, true),
                ];
            }
        }

        return $useCases;
    }
}

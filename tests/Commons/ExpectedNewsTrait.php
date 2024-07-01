<?php

namespace App\Tests\Commons;

use App\DataFixtures\News\NewsFixtures;
use App\Helper\PaginationHelper;

trait ExpectedNewsTrait
{
    private function buildNewsItem(int $newsId, string $locale, bool $expectNewsContent): array
    {
        $newsDate = new \DateTimeImmutable(NewsFixtures::START_DATE);
        if ($newsId > 1) {
            $newsDate = $newsDate->add(new \DateInterval('P'.($newsId - 1).'D'));
        }

        $newsItem = [
            'slug' => "news-title-$newsId",
            'date' => $newsDate->format(\DateTimeImmutable::ATOM),
            'preview_image' => 'preview-news-'.$newsId,
            'title' => ($locale === 'fr' ? "Titre de l'actualité " : 'News Title ').$newsId,
        ];

        if ($expectNewsContent) {
            $newsItem['content'] = ($locale === 'fr' ? "Contenu de l'actualité " : 'News Content ').$newsId;
        }

        return $newsItem;
    }

    private function buildNewsItemsArray(string $locale, int $nbItems, bool $expectNewsContent, ?int $offset = null): array
    {
        $newsItems = [];

        for ($indexItem = 0; $indexItem < $nbItems; $indexItem++) {
            $itemId = NewsFixtures::TOTAL_NEWS - $indexItem;
            if (!\is_null($offset)) {
                $itemId -= $offset;
            }

            $newsItems[] = $this->buildNewsItem($itemId, $locale, $expectNewsContent);
        }

        return $newsItems;
    }

    public function buildNewsListPagesUseCases(bool $expectNewsContent = true): array
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
                    'items' => $this->buildNewsItemsArray($locale, $nbItems, $expectNewsContent, $offset),
                    'previous_offset' => $indexPage > 1 ? $offset - PaginationHelper::DEFAULT_LIMIT : null,
                    'next_offset' => $indexPage < $nbPages ? $offset + PaginationHelper::DEFAULT_LIMIT : null,
                ];
            }
        }

        return $useCases;
    }

    public function buildLatestNewsUseCases(bool $expectNewsContent = true): array
    {
        $useCases = [];

        $nbItems = 3;

        // Expect each page in "en" and "fr"
        foreach (['en', 'fr'] as $locale) {
            $useCaseName = "Latest $locale";

            $useCases[$useCaseName] = [
                'locale' => $locale,
                'nbItems' => $nbItems,
                'items' => $this->buildNewsItemsArray($locale, $nbItems, $expectNewsContent),
            ];
        }

        return $useCases;
    }

    public function buildNewsDetailsUseCases(): array
    {
        $useCases = [];

        foreach (['en', 'fr'] as $locale) {
            foreach ([13, 6, 1] as $newsId) {
                $useCases["News $newsId $locale"] = [
                    'locale' => $locale,
                    'news' => $this->buildNewsItem($newsId, $locale, true),
                ];
            }
        }

        return $useCases;
    }
}

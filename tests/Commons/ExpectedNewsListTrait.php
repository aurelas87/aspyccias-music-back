<?php

namespace App\Tests\Commons;

use App\DataFixtures\News\NewsFixtures;
use App\Helper\PaginationHelper;

trait ExpectedNewsListTrait
{
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
                    'items' => [],
                    'previous_offset' => $indexPage > 1 ? $offset - PaginationHelper::DEFAULT_LIMIT : null,
                    'next_offset' => $indexPage < $nbPages ? $offset + PaginationHelper::DEFAULT_LIMIT : null,
                ];

                for ($indexItem = 0; $indexItem < $nbItems; $indexItem++) {
                    $itemId = NewsFixtures::TOTAL_NEWS - $indexItem - $offset;

                    $newsDate = new \DateTimeImmutable(NewsFixtures::START_DATE);
                    if ($itemId > 1) {
                        $newsDate = $newsDate->add(new \DateInterval('P'.($itemId - 1).'D'));
                    }

                    $newsItem = [
                        'id' => $itemId,
                        'date' => $newsDate->format(\DateTimeImmutable::ATOM),
                        'preview_image' => 'preview-news-'.$itemId,
                        'title' => ($locale === 'fr' ? "Titre de l'actualité " : 'News Title ').$itemId,
                    ];

                    if ($expectNewsContent) {
                        $newsItem['content'] = ($locale === 'fr' ? "Contenu de l'actualité " : 'News Content ').$itemId;
                    }

                    $useCases[$useCaseName]['items'][] = $newsItem;
                }
            }
        }

        return $useCases;
    }
}

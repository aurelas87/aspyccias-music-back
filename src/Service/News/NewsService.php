<?php

namespace App\Service\News;

use App\Entity\News\News;
use App\Exception\News\NewsNotFoundException;
use App\Helper\PaginationHelper;
use App\Model\PaginatedList;
use App\Repository\News\NewsRepository;

class NewsService
{
    private NewsRepository $newsRepository;

    public function __construct(NewsRepository $newsRepository)
    {
        $this->newsRepository = $newsRepository;
    }

    public function listNews(string $locale, array $options): PaginatedList
    {
        $paginationHelper = new PaginationHelper();
        $paginationHelper->parseQueryParameters($options);

        $newsItems = $this->newsRepository->findPaginated(
            $locale,
            $paginationHelper->getOffset(),
            $paginationHelper->getLimit(),
            PaginationHelper::DEFAULT_SORT_FIELD,
            PaginationHelper::DEFAULT_SORT_ORDER,
        );

        return $paginationHelper->mapItemsToPaginatedList($newsItems);
    }

    /**
     * @return News[]
     */
    public function getLatestNews(string $locale): array
    {
        return $this->newsRepository->findLatest(
            $locale,
            3,
            PaginationHelper::DEFAULT_SORT_FIELD,
            PaginationHelper::DEFAULT_SORT_ORDER
        );
    }

    public function getNewsDetails(string $slug, string $locale): ?News
    {
        $news = $this->newsRepository->findOneBySlugAndLocale($slug, $locale);
        if (!$news instanceof News) {
            throw new NewsNotFoundException();
        }

        return $news;
    }
}

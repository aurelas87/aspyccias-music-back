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

    public function listNews(array $options, ?string $locale = null, ?bool $isAdmin = false): PaginatedList
    {
        $paginationHelper = new PaginationHelper();
        $paginationHelper->parseQueryParameters($options, $isAdmin);

        $newsItems = $this->newsRepository->findPaginatedLocalized(
            $paginationHelper->getOffset(),
            $paginationHelper->getLimit(),
            PaginationHelper::DEFAULT_SORT_FIELD,
            PaginationHelper::DEFAULT_SORT_ORDER,
            $locale,
        );

        return $paginationHelper->mapItemsToPaginatedList($newsItems);
    }

    public function listNewsForAdmin(array $options): PaginatedList
    {
        return $this->listNews($options, null, true);
    }

    /**
     * @return News[]
     */
    public function getLatestNews(string $locale): array
    {
        return $this->newsRepository->findLatestLocalized(
            3,
            PaginationHelper::DEFAULT_SORT_FIELD,
            PaginationHelper::DEFAULT_SORT_ORDER,
            $locale
        );
    }

    public function getNewsDetails(string $slug, string $locale): ?News
    {
        $news = $this->newsRepository->findOneBySlugLocalized($slug, $locale);
        if (!$news instanceof News) {
            throw new NewsNotFoundException();
        }

        return $news;
    }
}

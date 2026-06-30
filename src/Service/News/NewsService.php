<?php

namespace App\Service\News;

use App\Entity\News\News;
use App\Entity\News\NewsTranslation;
use App\Exception\News\NewsNotFoundException;
use App\Helper\PaginationHelper;
use App\Model\News\NewsDTO;
use App\Model\PaginatedList;
use App\Repository\News\NewsRepository;
use App\Service\EntitySanitizer;
use DateMalformedStringException;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class NewsService
{
    private NewsRepository $newsRepository;
    private EntitySanitizer $entitySanitizer;
    private EntityManagerInterface $entityManager;

    public function __construct(
        NewsRepository $newsRepository,
        EntitySanitizer $entitySanitizer,
        EntityManagerInterface $entityManager
    ) {
        $this->newsRepository = $newsRepository;
        $this->entitySanitizer = $entitySanitizer;
        $this->entityManager = $entityManager;
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

    public function getNewsDetails(string $slug, string $locale): News
    {
        $news = $this->newsRepository->findOneBySlugLocalized($slug, $locale);
        if (!$news instanceof News) {
            throw new NewsNotFoundException();
        }

        return $news;
    }

    /**
     * @throws DateMalformedStringException
     */
    public function addNews(NewsDTO $newsDTO): void
    {
        $news = new News();

        $news->setDate(new DateTimeImmutable($newsDTO->date));
        $news->setSlug($newsDTO->slug);
        $news->addTranslation(
            new NewsTranslation()
                ->setLocale('fr')
                ->setTitle($newsDTO->fr->title)
                ->setContent($newsDTO->fr->content)
        );
        $news->addTranslation(
            new NewsTranslation()
                ->setLocale('en')
                ->setTitle($newsDTO->en->title)
                ->setContent($newsDTO->en->content)
        );

        $this->entitySanitizer->sanitizeEntity($news);

        $this->entityManager->persist($news);
        $this->entityManager->flush();
    }

    /**
     * @throws DateMalformedStringException
     */
    public function editNews(News $news, NewsDTO $newsDTO): void
    {
        $news->setDate(new DateTimeImmutable($newsDTO->date));
        $news->setSlug($newsDTO->slug);

        foreach ($news->getTranslations() as $newsTranslation) {
            if ($newsTranslation->getLocale() === 'fr') {
                $newsTranslation->setTitle($newsDTO->fr->title);
                $newsTranslation->setContent($newsDTO->fr->content);
            } else {
                $newsTranslation->setTitle($newsDTO->en->title);
                $newsTranslation->setContent($newsDTO->en->content);
            }
        }

        $this->entitySanitizer->sanitizeEntity($news);

        $this->entityManager->flush();
    }

    public function deleteNews(News $news): void
    {
        $this->entityManager->remove($news);
        $this->entityManager->flush();
    }
}

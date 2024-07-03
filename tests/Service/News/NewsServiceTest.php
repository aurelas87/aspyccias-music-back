<?php

namespace App\Tests\Service\News;

use App\Entity\News\News;
use App\Exception\News\NewsNotFoundException;
use App\Repository\News\NewsRepository;
use App\Service\News\NewsService;
use App\Tests\Commons\ExpectedNewsTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewsServiceTest extends KernelTestCase
{
    use ExpectedNewsTrait;

    private NewsService $newsService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->newsService = $this->getContainer()->get(NewsService::class);
    }

    /**
     * @throws \Exception
     */
    public function dataProviderNewsList(): array
    {
        return $this->buildNewsListPagesUseCases();
    }

    /**
     * @dataProvider dataProviderNewsList
     */
    public function testListNews(
        string $locale,
        int $offset,
        int $nbItems,
        array $items,
        ?int $previousOffset,
        ?int $nextOffset
    ): void {
        $newsList = $this->newsService->listNews($locale, ['offset' => $offset]);

        static::assertSame($previousOffset, $newsList->getPreviousOffset());
        static::assertSame($nextOffset, $newsList->getNextOffset());
        static::assertCount($nbItems, $newsList->getItems());

        foreach ($items as $indexItem => $item) {
            /** @var News $currentItem */
            $currentItem = $newsList->getItems()[$indexItem];

            static::assertSame($item['slug'], $currentItem->getSlug());
            static::assertSame($item['date'], $currentItem->getDate()->format(\DateTimeInterface::ATOM));
            static::assertSame($item['preview_image'], $currentItem->getPreviewImage());
            static::assertCount(1, $currentItem->getTranslations());
            static::assertSame($item['title'], $currentItem->getTranslations()->first()->getTitle());
        }
    }

    public function testListNewsEmpty(): void
    {
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $allNews = $this->getContainer()->get(NewsRepository::class)->findAll();
        foreach ($allNews as $news) {
            $manager->remove($news);
        }
        $manager->flush();

        $newsList = $this->newsService->listNews('fr', []);

        static::assertNull($newsList->getPreviousOffset());
        static::assertNull($newsList->getNextOffset());
        static::assertCount(0, $newsList->getItems());
    }

    /**
     * @throws \Exception
     */
    public function dataProviderLatestNews(): array
    {
        return $this->buildLatestNewsUseCases();
    }

    /**
     * @dataProvider dataProviderLatestNews
     */
    public function testLatestNews(string $locale, int $nbItems, array $items): void
    {
        $latestNews = $this->newsService->getLatestNews($locale);

        static::assertCount($nbItems, $latestNews);

        foreach ($items as $indexItem => $item) {
            $currentItem = $latestNews[$indexItem];

            static::assertSame($item['slug'], $currentItem->getSlug());
            static::assertSame($item['date'], $currentItem->getDate()->format(\DateTimeInterface::ATOM));
            static::assertSame($item['preview_image'], $currentItem->getPreviewImage());
            static::assertCount(1, $currentItem->getTranslations());
            static::assertSame($item['title'], $currentItem->getTranslations()->first()->getTitle());
        }
    }

    public function testLatestNewsEmpty(): void
    {
        $manager = $this->getContainer()->get('doctrine')->getManager();
        $allNews = $this->getContainer()->get(NewsRepository::class)->findAll();
        foreach ($allNews as $news) {
            $manager->remove($news);
        }
        $manager->flush();

        $latestNews = $this->newsService->getLatestNews('fr');

        static::assertCount(0, $latestNews);
    }

    /**
     * @throws \Exception
     */
    public function dataProviderGetNewsDetails(): array
    {
        return $this->buildNewsDetailsUseCases();
    }

    /**
     * @dataProvider dataProviderGetNewsDetails
     */
    public function testGetNewsDetails(string $locale, array $news): void
    {
        $newsDetails = $this->newsService->getNewsDetails($news['slug'], $locale);

        static::assertSame($news['slug'], $newsDetails->getSlug());
        static::assertSame($news['date'], $newsDetails->getDate()->format(\DateTimeInterface::ATOM));
        static::assertSame($news['preview_image'], $newsDetails->getPreviewImage());
        static::assertCount(1, $newsDetails->getTranslations());
        static::assertSame($news['title'], $newsDetails->getTranslations()->first()->getTitle());
        static::assertSame($news['content'], $newsDetails->getTranslations()->first()->getContent());
    }

    public function testGetNewsDetailsNotFound(): void
    {
        try {
            $this->newsService->getNewsDetails('news-title-14', 'fr');
        } catch (NewsNotFoundException $e) {
            static::assertSame('errors.news.not_found', $e->getMessage());
        }
    }
}

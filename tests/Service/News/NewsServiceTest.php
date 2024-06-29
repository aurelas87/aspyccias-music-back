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

        $this->newsService = static::getContainer()->get(NewsService::class);
    }

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

        for ($itemIndex = 0; $itemIndex < \count($newsList->getItems()); $itemIndex++) {
            $currentItem = $newsList->getItems()[$itemIndex];

            static::assertTrue($currentItem instanceof News);
            static::assertSame($items[$itemIndex]['id'], $currentItem->getId());
            static::assertSame($items[$itemIndex]['date'], $currentItem->getDate()->format(\DateTimeImmutable::ATOM));
            static::assertSame($items[$itemIndex]['preview_image'], $currentItem->getPreviewImage());
            static::assertCount(1, $currentItem->getTranslations());
            static::assertSame($items[$itemIndex]['title'], $currentItem->getTranslations()[0]->getTitle());
            static::assertSame($items[$itemIndex]['content'], $currentItem->getTranslations()[0]->getContent());
        }
    }

    public function testListNewsEmpty(): void
    {
        $manager = static::getContainer()->get('doctrine')->getManager();
        $allNews = static::getContainer()->get(NewsRepository::class)->findAll();
        foreach ($allNews as $news) {
            $manager->remove($news);
        }
        $manager->flush();

        $newsList = $this->newsService->listNews('fr', []);

        static::assertNull($newsList->getPreviousOffset());
        static::assertNull($newsList->getNextOffset());
        static::assertCount(0, $newsList->getItems());
    }

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

        for ($itemIndex = 0; $itemIndex < \count($latestNews); $itemIndex++) {
            $currentItem = $latestNews[$itemIndex];

            static::assertTrue($latestNews[$itemIndex] instanceof News);
            static::assertSame($items[$itemIndex]['id'], $currentItem->getId());
            static::assertSame($items[$itemIndex]['date'], $currentItem->getDate()->format(\DateTimeImmutable::ATOM));
            static::assertSame($items[$itemIndex]['preview_image'], $currentItem->getPreviewImage());
            static::assertCount(1, $currentItem->getTranslations());
            static::assertSame($items[$itemIndex]['title'], $currentItem->getTranslations()[0]->getTitle());
            static::assertSame($items[$itemIndex]['content'], $currentItem->getTranslations()[0]->getContent());
        }
    }

    public function testLatestNewsEmpty(): void
    {
        $manager = static::getContainer()->get('doctrine')->getManager();
        $allNews = static::getContainer()->get(NewsRepository::class)->findAll();
        foreach ($allNews as $news) {
            $manager->remove($news);
        }
        $manager->flush();

        $latestNews = $this->newsService->getLatestNews('fr');

        static::assertCount(0, $latestNews);
    }

    public function dataProviderGetNewsDetails(): array
    {
        return $this->buildNewsDetailsUseCases();
    }

    /**
     * @dataProvider dataProviderGetNewsDetails
     */
    public function testGetNewsDetails(string $locale, array $news): void
    {
        $newsDetails = $this->newsService->getNewsDetails($news['id'], $locale);

        static::assertSame($news['id'], $newsDetails->getId());
        static::assertSame($news['date'], $newsDetails->getDate()->format(\DateTimeImmutable::ATOM));
        static::assertSame($news['preview_image'], $newsDetails->getPreviewImage());
        static::assertCount(1, $newsDetails->getTranslations());
        static::assertSame($news['title'], $newsDetails->getTranslations()[0]->getTitle());
        static::assertSame($news['content'], $newsDetails->getTranslations()[0]->getContent());
    }

    public function testGetNewsDetailsNotFound(): void
    {
        try {
            $this->newsService->getNewsDetails(14, 'fr');
        } catch (NewsNotFoundException $e) {
            static::assertSame('errors.news.not_found', $e->getMessage());
        }
    }
}

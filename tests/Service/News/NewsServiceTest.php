<?php

namespace App\Tests\Service\News;

use App\Entity\News\News;
use App\Exception\News\NewsNotFoundException;
use App\Repository\News\NewsRepository;
use App\Service\News\NewsService;
use App\Tests\Commons\ExpectedNewsTrait;
use PHPUnit\Framework\Attributes\DataProvider;
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
    public static function dataProviderNewsList(): array
    {
        return self::buildNewsListPagesUseCases();
    }

    #[DataProvider('dataProviderNewsList')]
    public function testListNews(
        string $locale,
        int $offset,
        int $nbItems,
        array $items,
        ?int $previousOffset,
        ?int $nextOffset
    ): void {
        $newsList = $this->newsService->listNews(['offset' => $offset], $locale);

        static::assertSame($previousOffset, $newsList->getPreviousOffset());
        static::assertSame($nextOffset, $newsList->getNextOffset());
        static::assertCount($nbItems, $newsList->getItems());

        foreach ($items as $indexItem => $item) {
            /** @var News $currentItem */
            $currentItem = $newsList->getItems()[$indexItem];

            static::assertSame($item['slug'], $currentItem->getSlug());
            static::assertSame($item['date'], $currentItem->getDate()->format(\DateTimeInterface::ATOM));
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

        $newsList = $this->newsService->listNews([], 'fr');

        static::assertNull($newsList->getPreviousOffset());
        static::assertNull($newsList->getNextOffset());
        static::assertCount(0, $newsList->getItems());
    }

    /**
     * @throws \Exception
     */
    public static function dataProviderLatestNews(): array
    {
        return self::buildLatestNewsUseCases();
    }

    #[DataProvider('dataProviderLatestNews')]
    public function testLatestNews(string $locale, int $nbItems, array $items): void
    {
        $latestNews = $this->newsService->getLatestNews($locale);

        static::assertCount($nbItems, $latestNews);

        foreach ($items as $indexItem => $item) {
            $currentItem = $latestNews[$indexItem];

            static::assertSame($item['slug'], $currentItem->getSlug());
            static::assertSame($item['date'], $currentItem->getDate()->format(\DateTimeInterface::ATOM));
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
    public static function dataProviderGetNewsDetails(): array
    {
        return self::buildNewsDetailsUseCases();
    }

    #[DataProvider('dataProviderGetNewsDetails')]
    public function testGetNewsDetails(string $locale, array $news): void
    {
        $newsDetails = $this->newsService->getNewsDetails($news['slug'], $locale);

        static::assertSame($news['slug'], $newsDetails->getSlug());
        static::assertSame($news['date'], $newsDetails->getDate()->format(\DateTimeInterface::ATOM));
        static::assertCount(1, $newsDetails->getTranslations());
        static::assertSame($news['title'], $newsDetails->getTranslations()->first()->getTitle());
        static::assertSame($news['content'], $newsDetails->getTranslations()->first()->getContent());
    }

    public function testGetNewsDetailsNotFound(): void
    {
        static::expectException(NewsNotFoundException::class);
        static::expectExceptionMessageMatches('/^errors\.news\.not_found$/');

        $this->newsService->getNewsDetails('news-title-14', 'fr');
    }
}

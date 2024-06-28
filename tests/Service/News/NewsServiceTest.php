<?php

namespace App\Tests\Service\News;

use App\Entity\News\News;
use App\Repository\News\NewsRepository;
use App\Service\News\NewsService;
use App\Tests\Commons\ExpectedNewsListTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class NewsServiceTest extends KernelTestCase
{
    use ExpectedNewsListTrait;

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
}

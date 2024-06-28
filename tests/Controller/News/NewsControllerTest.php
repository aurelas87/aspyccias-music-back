<?php

namespace App\Tests\Controller\News;

use App\Repository\News\NewsRepository;
use App\Service\News\NewsService;
use App\Tests\Commons\ExpectedNewsListTrait;
use App\Tests\Controller\JsonResponseTestCase;

class NewsControllerTest extends JsonResponseTestCase
{
    use ExpectedNewsListTrait;

    private NewsService $newsService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->newsService = static::getContainer()->get(NewsService::class);
    }

    public function dataProviderListNews(): array
    {
        return $this->buildNewsListPagesUseCases(false);
    }

    /**
     * @dataProvider dataProviderListNews
     */
    public function testListNews(
        string $locale,
        ?int $offset,
        int $nbItems,
        array $items,
        ?int $previousOffset,
        ?int $nextOffset
    ): void {
        $this->client->request(
            method: 'GET',
            uri: '/news',
            parameters: ['offset' => $offset],
            server: ['HTTP_ACCEPT_LANGUAGE' => $locale]
        );

        $this->serializerAndAssertJsonResponse([
            'previous_offset' => $previousOffset,
            'next_offset' => $nextOffset,
            'items' => $items,
        ]);
    }

    public function testListNewsEmpty(): void
    {
        $manager = static::getContainer()->get('doctrine')->getManager();
        $allNews = static::getContainer()->get(NewsRepository::class)->findAll();
        foreach ($allNews as $news) {
            $manager->remove($news);
        }
        $manager->flush();

        $this->client->request('GET', '/news');

        $this->serializerAndAssertJsonResponse([
            'previous_offset' => null,
            'next_offset' => null,
            'items' => [],
        ]);
    }
}

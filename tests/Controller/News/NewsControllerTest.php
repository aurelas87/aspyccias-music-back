<?php

namespace App\Tests\Controller\News;

use App\Exception\News\NewsNotFoundException;
use App\Repository\News\NewsRepository;
use App\Service\News\NewsService;
use App\Tests\Commons\ExpectedNewsTrait;
use App\Tests\Controller\JsonResponseTestCase;
use Symfony\Component\HttpFoundation\Response;

class NewsControllerTest extends JsonResponseTestCase
{
    use ExpectedNewsTrait;

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

        $this->serializerAndAssertJsonResponse(
            expectedContent: [
                'previous_offset' => $previousOffset,
                'next_offset' => $nextOffset,
                'items' => $items,
            ],
            contextGroups: ['default', 'list']
        );
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

    public function dataProviderLatestNews(): array
    {
        return $this->buildLatestNewsUseCases(false);
    }

    /**
     * @dataProvider dataProviderLatestNews
     */
    public function testLatestNews(string $locale, int $nbItems, array $items): void
    {
        $this->client->request(method: 'GET', uri: '/news/latest', server: ['HTTP_ACCEPT_LANGUAGE' => $locale]);

        $this->serializerAndAssertJsonResponse(
            expectedContent: $items,
            contextGroups: ['default', 'list']
        );
    }

    public function testLatestNewsEmpty(): void
    {
        $manager = static::getContainer()->get('doctrine')->getManager();
        $allNews = static::getContainer()->get(NewsRepository::class)->findAll();
        foreach ($allNews as $news) {
            $manager->remove($news);
        }
        $manager->flush();

        $this->client->request('GET', '/news/latest');

        $this->serializerAndAssertJsonResponse([]);
    }

    public function dataProviderNewsDetails(): array
    {
        return $this->buildNewsDetailsUseCases();
    }

    /**
     * @dataProvider dataProviderNewsDetails
     */
    public function testNewsDetails(string $locale, array $news): void
    {
        $this->client->request(method: 'GET', uri: '/news/'.$news['slug'], server: ['HTTP_ACCEPT_LANGUAGE' => $locale]);

        $this->serializerAndAssertJsonResponse(
            expectedContent: $news,
            contextGroups: ['default', 'details']
        );
    }

    /**
     * @dataProvider dataProviderNotFound
     */
    public function testNewsDetailsNotFound(string $locale): void
    {
        $this->client->request(method: 'GET', uri: '/news/news-title-14', server: ['HTTP_ACCEPT_LANGUAGE' => $locale]);

        $expectedException = new NewsNotFoundException();

        $this->serializeAndAssertJsonResponseHttpException($expectedException, $locale);
    }
}

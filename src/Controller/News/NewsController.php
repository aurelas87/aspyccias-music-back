<?php

namespace App\Controller\News;

use App\Service\News\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class NewsController extends AbstractController
{
    #[Route('/news', name: 'app_news_list')]
    public function list(Request $request, NewsService $newsService): JsonResponse
    {
        return $this->json(
            data: $newsService->listNews($request->getLocale(), $request->query->all()),
            context: ['groups' => ['default', 'list']]
        );
    }

    #[Route('/news/latest', name: 'app_news_latest')]
    public function latest(Request $request, NewsService $newsService): JsonResponse
    {
        return $this->json(
            data: $newsService->getLatestNews($request->getLocale()),
            context: ['groups' => ['default', 'list']]
        );
    }

    #[Route('/news/{slug}', name: 'app_news_details')]
    public function newsDetails(string $slug, Request $request, NewsService $newsService): JsonResponse
    {
        return $this->json(
            data: $newsService->getNewsDetails($slug, $request->getLocale()),
            context: ['groups' => ['default', 'details']]
        );
    }
}

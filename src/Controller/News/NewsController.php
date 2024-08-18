<?php

namespace App\Controller\News;

use App\Service\News\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/news')]
class NewsController extends AbstractController
{
    #[Route('', name: 'app_news_list', methods: ['GET'])]
    public function list(Request $request, NewsService $newsService): JsonResponse
    {
        return $this->json(
            data: $newsService->listNews($request->getLocale(), $request->query->all()),
            context: ['groups' => ['default', 'list']]
        );
    }

    #[Route('/latest', name: 'app_news_latest', methods: ['GET'])]
    public function latest(Request $request, NewsService $newsService): JsonResponse
    {
        return $this->json(
            data: $newsService->getLatestNews($request->getLocale()),
            context: ['groups' => ['default', 'list']]
        );
    }

    #[Route('/{slug}', name: 'app_news_details', methods: ['GET'])]
    public function newsDetails(string $slug, Request $request, NewsService $newsService): JsonResponse
    {
        return $this->json(
            data: $newsService->getNewsDetails($slug, $request->getLocale()),
            context: ['groups' => ['default', 'details']]
        );
    }
}

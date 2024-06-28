<?php

namespace App\Controller\News;

use App\Service\News\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class NewsController extends AbstractController
{
    #[Route('/news', name: 'app_news')]
    public function list(Request $request, NewsService $newsService): JsonResponse
    {
        return $this->json(
            data: $newsService->listNews($request->getLocale(), $request->query->all()),
            context: ['groups' => ['default', 'list']]
        );
    }
}

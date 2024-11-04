<?php

namespace App\Controller\Admin\News;

use App\Service\News\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/news')]
class AdminNewsController extends AbstractController
{
    #[Route(path: '', name: 'app_admin_news_list', methods: ['GET'])]
    public function list(Request $request, NewsService $newsService): JsonResponse
    {
        return $this->json(
            data: $newsService->listNewsForAdmin($request->query->all()),
            context: ['groups' => ['default', 'list', 'admin']]
        );
    }
}

<?php

namespace App\Controller\Admin\News;

use App\Entity\News\News;
use App\Model\News\NewsDTO;
use App\Service\ImageService;
use App\Service\News\NewsService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/news')]
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

    /**
     * @throws \DateMalformedStringException
     */
    #[Route(path: '', name: 'app_admin_news_add', methods: ['POST'])]
    public function add(
        #[MapRequestPayload(acceptFormat: 'json')] NewsDTO $newsDTO,
        NewsService $newsService
    ): JsonResponse {
        $newsService->addNews($newsDTO);

        return $this->json(null, Response::HTTP_CREATED);
    }

    #[Route(path: '/{news}', name: 'app_admin_news_details', methods: ['GET'])]
    public function get(#[ValueResolver('news')] News $news): JsonResponse
    {
        return $this->json(
            data: $news,
            context: ['groups' => ['default', 'admin', 'details']]
        );
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[Route(path: '/{news}', name: 'app_admin_news_edit', methods: ['PUT'])]
    public function edit(
        #[ValueResolver('news')] News $news,
        #[MapRequestPayload(acceptFormat: 'json')] NewsDTO $newsDTO,
        NewsService $newsService
    ): JsonResponse {
        $newsService->editNews($news, $newsDTO);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{news}', name: 'app_admin_news_delete', methods: ['DELETE'])]
    public function delete(
        #[ValueResolver('news')] News $news,
        ImageService $imageService,
        NewsService $newsService
    ): JsonResponse {
        $imageService->deleteImageFile('news', $news->getSlug());
        $newsService->deleteNews($news);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}

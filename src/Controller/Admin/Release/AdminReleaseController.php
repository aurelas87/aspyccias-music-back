<?php

namespace App\Controller\Admin\Release;

use App\Entity\Release\Release;
use App\Model\Image\ResourceType;
use App\Model\Release\ReleaseCreditsDTO;
use App\Model\Release\ReleaseDTO;
use App\Model\Release\ReleaseImageType;
use App\Model\Release\ReleaseLinksDTO;
use App\Model\Release\ReleaseTracksDTO;
use App\Service\ImageService;
use App\Service\Release\ReleaseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Attribute\ValueResolver;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/admin/releases')]
class AdminReleaseController extends AbstractController
{
    #[Route(path: '', name: 'app_admin_releases_list', methods: ['GET'])]
    public function list(Request $request, ReleaseService $releaseService): JsonResponse
    {
        return $this->json(
            data: $releaseService->listReleasesForAdmin($request->query->all()),
            context: ['groups' => ['default', 'list', 'admin']]
        );
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[Route(path: '', name: 'app_admin_release_add', methods: ['POST'])]
    public function add(
        #[MapRequestPayload(acceptFormat: 'json')] ReleaseDTO $releaseDTO,
        ReleaseService $releaseService
    ): JsonResponse {
        $releaseService->addRelease($releaseDTO);

        return $this->json(null, Response::HTTP_CREATED);
    }

    #[Route(path: '/{release}', name: 'app_admin_release_details', methods: ['GET'])]
    public function get(#[ValueResolver('release')] Release $release): JsonResponse
    {
        return $this->json(
            data: $release,
            context: ['groups' => ['default', 'admin', 'details']]
        );
    }

    #[Route(path: '/{release}/tracks', name: 'app_admin_release_tracks', methods: ['GET'])]
    public function getTracks(#[ValueResolver('release')] Release $release): JsonResponse
    {
        return $this->json(
            data: $release,
            context: ['groups' => ['admin-tracks']]
        );
    }

    #[Route(path: '/{release}/tracks', name: 'app_admin_release_tracks_update', methods: ['POST'])]
    public function editTracks(
        #[ValueResolver('release')] Release $release,
        #[MapRequestPayload(acceptFormat: 'json')] ReleaseTracksDTO $releaseTracksDTO,
        ReleaseService $releaseService
    ): JsonResponse {
        $releaseService->editTracks($release, $releaseTracksDTO);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{release}/credits', name: 'app_admin_release_credits', methods: ['GET'])]
    public function getCredits(#[ValueResolver('release')] Release $release): JsonResponse
    {
        return $this->json(
            data: $release,
            context: ['groups' => ['admin-credits']]
        );
    }

    #[Route(path: '/{release}/credits', name: 'app_admin_release_credits_update', methods: ['POST'])]
    public function editCredits(
        #[ValueResolver('release')] Release $release,
        #[MapRequestPayload(acceptFormat: 'json')] ReleaseCreditsDTO $releaseCreditsDTO,
        ReleaseService $releaseService
    ): JsonResponse {
        $releaseService->editCredits($release, $releaseCreditsDTO);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{release}/links', name: 'app_admin_release_links', methods: ['GET'])]
    public function getLinks(#[ValueResolver('release')] Release $release): JsonResponse
    {
        return $this->json(
            data: $release,
            context: ['groups' => ['admin-links']]
        );
    }

    #[Route(path: '/{release}/links', name: 'app_admin_release_links_update', methods: ['POST'])]
    public function editLinks(
        #[ValueResolver('release')] Release $release,
        #[MapRequestPayload(acceptFormat: 'json')] ReleaseLinksDTO $releaseLinksDTO,
        ReleaseService $releaseService
    ): JsonResponse {
        $releaseService->editLinks($release, $releaseLinksDTO);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws \DateMalformedStringException
     */
    #[Route(path: '/{release}', name: 'app_admin_release_edit', methods: ['PUT'])]
    public function edit(
        #[ValueResolver('release')] Release $release,
        #[MapRequestPayload(acceptFormat: 'json')] ReleaseDTO $releaseDTO,
        ReleaseService $releaseService,
        ImageService $imageService
    ): JsonResponse {
        $oldArtworkFrontImagePath = null;
        $oldArtworkBackImagePath = null;

        if (!$releaseDTO->artworkBackImage) {
            $imageService->deleteImageFile(ResourceType::releases, $release->getSlug(), ReleaseImageType::back->value);
        }

        if ($release->getReleaseDate() !== $releaseDTO->releaseDate || $release->getSlug() !== $releaseDTO->slug) {
            try {
                $oldArtworkFrontImagePath = $imageService->getImageFilePath(
                    ResourceType::releases,
                    $release->getSlug(),
                    ReleaseImageType::front->value
                );
            } catch (NotFoundHttpException) {
                // Keep empty so release update can work without a front image
            }

            if ($releaseDTO->artworkBackImage && $release->getArtworkBackImage()) {
                $oldArtworkBackImagePath = $imageService->getImageFilePath(
                    ResourceType::releases,
                    $release->getSlug(),
                    ReleaseImageType::back->value
                );
            }
        }

        $releaseService->editRelease($release, $releaseDTO);

        if ($oldArtworkFrontImagePath) {
            $imageService->moveImageFile(
                $oldArtworkFrontImagePath,
                ResourceType::releases,
                $release->getSlug(),
                ReleaseImageType::front->value
            );
        }

        if ($oldArtworkBackImagePath) {
            $imageService->moveImageFile(
                $oldArtworkBackImagePath,
                ResourceType::releases,
                $release->getSlug(),
                ReleaseImageType::back->value
            );
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    #[Route(path: '/{release}', name: 'app_admin_release_delete', methods: ['DELETE'])]
    public function delete(
        #[ValueResolver('release')] Release $release,
        ImageService $imageService,
        ReleaseService $releaseService
    ): JsonResponse {
        $imageService->deleteImageFile(ResourceType::releases, $release->getSlug(), ReleaseImageType::front->value);
        $imageService->deleteImageFile(ResourceType::releases, $release->getSlug(), ReleaseImageType::back->value);

        $releaseService->deleteRelease($release);

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }
}

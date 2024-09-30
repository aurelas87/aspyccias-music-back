<?php

namespace App\Controller\Admin;

use App\Entity\User\User;
use App\Service\User\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route(path: '/admin')]
class LoginController extends AbstractController
{
    /**
     * @throws \Exception
     */
    #[Route('/login', name: 'app_admin_login', methods: ['POST'])]
    public function login(#[CurrentUser] ?User $user, UserService $userService): JsonResponse
    {
        if (null === $user) {
            return $this->json([
                'message' => 'Invalid credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $userService->checkAndCreateUserToken($user);

        return $this->json([
            'user' => $user->getUserIdentifier(),
            'token' => $user->getToken(),
        ]);
    }

    #[Route('/logout', name: 'app_admin_logout', methods: ['POST'])]
    public function logout(#[CurrentUser] ?User $user, Security $security, UserService $userService): JsonResponse
    {
        if ($user) {
            $security->logout(false);

            $userService->resetUserToken($user);
        }

        return $this->json(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @throws \Exception
     */
    #[Route('/token/refresh', name: 'app_admin_token_refresh', methods: ['POST'])]
    public function tokenRefresh(#[CurrentUser] ?User $user, UserService $userService): JsonResponse
    {
        $userService->checkAndRefreshUserToken($user);

        return $this->json([
            'user' => $user->getUserIdentifier(),
            'token' => $user->getToken(),
        ]);
    }
}

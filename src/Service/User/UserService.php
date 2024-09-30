<?php

namespace App\Service\User;

use App\Entity\User\User;
use App\Helper\TokenHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class UserService
{
    private TokenHelper $tokenHelper;
    private EntityManagerInterface $entityManager;

    public function __construct(TokenHelper $tokenHelper, EntityManagerInterface $entityManager)
    {
        $this->tokenHelper = $tokenHelper;
        $this->entityManager = $entityManager;
    }

    /**
     * @throws \Exception
     */
    public function checkAndCreateUserToken(User $user): void
    {
        if (!$this->tokenHelper->isAccessTokenValid($user->getToken())) {
            $userToken = $this->tokenHelper->generateToken($user->getToken());

            $user->setToken($userToken);

            $this->entityManager->flush();
        }
    }

    public function resetUserToken(User $user): void
    {
        $this->tokenHelper->resetToken($user->getToken());

        $this->entityManager->flush();
    }

    /**
     * @throws \Exception
     */
    public function checkAndRefreshUserToken(User $user): void
    {
        if ($this->tokenHelper->isAccessTokenValid($user->getToken())) {
            throw new UnauthorizedHttpException('Cannot refresh token');
        }

        $this->resetUserToken($user);
        $this->checkAndCreateUserToken($user);
    }
}

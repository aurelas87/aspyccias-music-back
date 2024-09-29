<?php

namespace App\Service\User;

use App\Entity\User\User;
use App\Helper\TokenHelper;
use Doctrine\ORM\EntityManagerInterface;

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
    public function checkAndCreateUserAccessToken(User $user): void
    {
        if (!$this->tokenHelper->isTokenValid($user->getToken())) {
            $userToken = $this->tokenHelper->generateToken($user->getToken());

            $user->setToken($userToken);

            $this->entityManager->flush();
        }
    }

    public function resetUserToken(User $user): void
    {
        $user->getToken()
            ->setAccessToken(null)
            ->setAccessTokenExpirationDate(null);

        $this->entityManager->flush();
    }
}

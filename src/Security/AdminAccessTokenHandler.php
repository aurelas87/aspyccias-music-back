<?php

namespace App\Security;

use App\Entity\User\UserToken;
use App\Helper\TokenHelper;
use App\Repository\User\UserTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AdminAccessTokenHandler implements AccessTokenHandlerInterface
{
    private UserTokenRepository $userTokenRepository;
    private TokenHelper $tokenHelper;

    public function __construct(UserTokenRepository $userTokenRepository, TokenHelper $tokenHelper)
    {
        $this->userTokenRepository = $userTokenRepository;
        $this->tokenHelper = $tokenHelper;
    }

    /**
     * @inheritDoc
     */
    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        $userToken = $this->userTokenRepository->findOneBy(['accessToken' => $accessToken]);
        if (!$this->isTokenValid($userToken)) {
            throw new BadCredentialsException('Invalid token');
        }

        return new UserBadge($userToken->getUser()->getEmail());
    }

    protected function isTokenValid(?UserToken $userToken): bool
    {
        return $this->tokenHelper->isTokenValid($userToken);
    }
}

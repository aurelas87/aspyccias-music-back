<?php

namespace App\Helper;

use App\Entity\User\UserToken;

class TokenHelper
{
    public const ACCESS_TOKEN_LIVE_TIME = 3600; // 1 hour

    public function isTokenValid(?UserToken $token): bool
    {
        return !\is_null($token)
            && !\is_null($token->getUser())
            && !\is_null($token->getAccessToken())
            && !\is_null($token->getAccessTokenExpirationDate())
            && $token->getAccessTokenExpirationDate() >= new \DateTimeImmutable();
    }

    public function generateToken(?UserToken $userToken): UserToken
    {
        try {
            if (\is_null($userToken)) {
                $userToken = new UserToken();
            }

            $userToken->setAccessToken(\bin2hex(\random_bytes(64)));
            $userToken->setAccessTokenExpirationDate(
                (new \DateTimeImmutable())
                    ->add(new \DateInterval('PT'.self::ACCESS_TOKEN_LIVE_TIME.'S'))
            );

            return $userToken;
        } catch (\Throwable $exception) {
            throw new \RuntimeException('Unable to generate token: ' . $exception->getMessage());
        }
    }
}

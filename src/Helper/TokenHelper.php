<?php

namespace App\Helper;

use App\Entity\User\UserToken;

class TokenHelper
{
    public const ACCESS_TOKEN_LIVE_TIME = 3600; // 1 hour
    public const REFRESH_TOKEN_LIVE_TIME = self::ACCESS_TOKEN_LIVE_TIME + 1800; // 1 hour and 30 minutes

    public function isAccessTokenValid(?UserToken $token): bool
    {
        return !\is_null($token)
            && !\is_null($token->getUser())
            && !\is_null($token->getAccessToken())
            && !\is_null($token->getAccessTokenExpirationDate())
            && $token->getAccessTokenExpirationDate() >= new \DateTimeImmutable();
    }

    public function isRefreshTokenValid(?UserToken $token): bool
    {
        return !\is_null($token)
            && !\is_null($token->getUser())
            && !\is_null($token->getRefreshToken())
            && !\is_null($token->getRefreshTokenExpirationDate())
            && $token->getRefreshTokenExpirationDate() >= new \DateTimeImmutable();
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

            $userToken->setRefreshToken(\bin2hex(\random_bytes(64)));
            $userToken->setRefreshTokenExpirationDate(
                (new \DateTimeImmutable())
                    ->add(new \DateInterval('PT'.self::REFRESH_TOKEN_LIVE_TIME.'S'))
            );

            return $userToken;
        } catch (\Throwable $exception) {
            throw new \RuntimeException('Unable to generate token: ' . $exception->getMessage());
        }
    }

    public function resetToken(UserToken $userToken): void
    {
        $userToken
            ->setAccessToken(null)
            ->setAccessTokenExpirationDate(null)
            ->setRefreshToken(null)
            ->setRefreshTokenExpirationDate(null);
    }
}

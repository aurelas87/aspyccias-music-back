<?php

namespace App\Security;

use App\Entity\User\UserToken;

class LogoutAccessTokenHandler extends AdminAccessTokenHandler
{
    public function isTokenValid(?UserToken $userToken): bool
    {
        return !\is_null($userToken);
    }
}

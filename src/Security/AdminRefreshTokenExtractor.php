<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;

class AdminRefreshTokenExtractor extends AdminAccessTokenExtractor
{
    public function isRouteSupported(Request $request): bool
    {
        return true;
    }
}

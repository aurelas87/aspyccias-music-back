<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenExtractorInterface;

class AdminAccessTokenExtractor implements AccessTokenExtractorInterface
{
    private readonly string $headerParameter;
    private readonly string $tokenType;
    private string $regex;

    private RouterInterface $router;

    public function __construct(
        RouterInterface $router,
        string $headerParameter = 'Authorization',
        string $tokenType = 'Bearer',
    ) {
        $this->router = $router;

        $this->tokenType = $tokenType;
        $this->headerParameter = $headerParameter;
        $this->regex = sprintf(
            '/^%s([a-zA-Z0-9\-_\+~\/\.]+=*)$/',
            '' === $this->tokenType ? '' : preg_quote($this->tokenType).'\s+'
        );
    }

    public function extractAccessToken(Request $request): ?string
    {
        $routeAdminLogin = $this->router->getRouteCollection()->get('app_admin_login');
        if ($routeAdminLogin && $request->getPathInfo() === $routeAdminLogin->getPath()) {
            return null;
        }

        if (!$request->headers->has($this->headerParameter) || !\is_string($header = $request->headers->get($this->headerParameter))) {
            throw new BadCredentialsException('Invalid credentials');
        }

        if (preg_match($this->regex, $header, $matches)) {
            return $matches[1];
        }

        throw new BadCredentialsException('Invalid credentials');
    }
}

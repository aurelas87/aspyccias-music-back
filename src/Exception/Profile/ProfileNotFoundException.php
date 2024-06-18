<?php

namespace App\Exception\Profile;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfileNotFoundException extends NotFoundHttpException
{
    public function __construct(string $profileName)
    {
        parent::__construct('Profile with name "'.$profileName.'" not found');
    }
}

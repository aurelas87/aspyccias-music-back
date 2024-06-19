<?php

namespace App\Exception\Profile;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfileNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('errors.profile.not_found');
    }
}

<?php

namespace App\Exception\Profile;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProfileLinkNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('errors.profile.link.not_found');
    }
}

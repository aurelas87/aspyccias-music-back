<?php

namespace App\Exception\Release;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReleaseNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('errors.release.not_found');
    }
}

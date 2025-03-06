<?php

namespace App\Exception\Release;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReleaseCreditTypeNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('errors.release.credit_type.not_found');
    }
}

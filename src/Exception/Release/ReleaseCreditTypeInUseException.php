<?php

namespace App\Exception\Release;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReleaseCreditTypeInUseException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('errors.release.credit_type.in_use');
    }
}

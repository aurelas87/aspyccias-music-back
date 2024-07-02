<?php

namespace App\Exception\Release;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MissingReleaseTypeOptionException extends BadRequestHttpException
{
    public function __construct()
    {
        parent::__construct('errors.release.type.missing');
    }
}

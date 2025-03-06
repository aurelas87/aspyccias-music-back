<?php

namespace App\Exception\Release;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReleaseLinkNameInUseException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('errors.release.link_name.in_use');
    }
}

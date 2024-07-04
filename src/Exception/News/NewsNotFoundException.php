<?php

namespace App\Exception\News;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NewsNotFoundException extends NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct('errors.news.not_found');
    }
}

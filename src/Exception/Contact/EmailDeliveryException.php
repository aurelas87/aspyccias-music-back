<?php

namespace App\Exception\Contact;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EmailDeliveryException extends HttpException
{
    public function __construct()
    {
        parent::__construct(Response::HTTP_INTERNAL_SERVER_ERROR, 'errors.contact.email_delivery');
    }
}

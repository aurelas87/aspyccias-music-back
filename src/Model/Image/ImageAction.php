<?php

namespace App\Model\Image;

enum ImageAction: string
{
    case get = 'get';
    case create = 'create';
    case move = 'move';
    case delete = 'delete';
}

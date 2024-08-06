<?php

namespace App\Model\Release;

enum ReleaseLinkCategory: int
{
    case listen = 1;
    case buy = 2;
    case smart_link = 3;
}

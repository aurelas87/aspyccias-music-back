<?php

namespace App\Model\Release;

enum ReleaseTypeString: string
{
    case single = 'single';
    case ep = 'ep';
    case album = 'album';
}

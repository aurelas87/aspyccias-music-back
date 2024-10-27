<?php

namespace App\Model\Image;

enum ResourceType: string
{
    case profile = 'profile';
    case news = 'news';
    case release = 'release';

    public static function enumValues(): array
    {
        return \array_column(self::cases(), 'value');
    }
}

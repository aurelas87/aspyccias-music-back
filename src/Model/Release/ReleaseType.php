<?php

namespace App\Model\Release;

enum ReleaseType: int
{
    case single = 1;
    case ep = 2;
    case album = 3;

    public static function tryFromName(string $typeName): ?static
    {
        return match ($typeName) {
            self::single->name => self::single,
            self::ep->name => self::ep,
            self::album->name => self::album,
            default => null,
        };
    }
}

<?php

namespace App\Model\Release;

enum ReleaseLinkCategory: int
{
    case listen = 1;
    case buy = 2;
    case smart_link = 3;

    public static function enumNames(): array
    {
        return \array_column(self::cases(), 'name');
    }

    public static function tryFromName(string $typeName): ?self
    {
        return match ($typeName) {
            self::listen->name => self::listen,
            self::buy->name => self::buy,
            self::smart_link->name => self::smart_link,
            default => null,
        };
    }
}

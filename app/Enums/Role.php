<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'admin';
    case BOUTIQUIER = 'boutiquier';
    case CLIENT = 'client';

    public static function default(): self
    {
        return self::CLIENT;
    }
}

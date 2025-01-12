<?php

namespace Liamtseva\Cinema\Enums;

enum Role: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case MODERATOR = 'moderator';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

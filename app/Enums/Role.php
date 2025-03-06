<?php

namespace Liamtseva\Cinema\Enums;

enum Role: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case MODERATOR = 'moderator';

    public static function getLabels(): array
    {
        return [
            self::USER->value => __('role.user'),
            self::ADMIN->value => __('role.admin'),
            self::MODERATOR->value => __('role.moderator'),
        ];
    }
}

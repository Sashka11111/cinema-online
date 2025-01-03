<?php

namespace Liamtseva\Cinema\Enums;

enum Role: string
{
    case USER = 'user';
    case ADMIN = 'admin';
    case MODERATOR = 'moderator';
}

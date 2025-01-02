<?php

namespace Liamtseva\Cinema\Enums;

enum VideoPlayerName: string
{
    case KODIK = 'kodik';
    case ALOHA = 'aloha';

    public function name(): string
    {
        return match ($this) {
            self::KODIK => 'Kodik',
            self::ALOHA => 'Aloha',
        };
    }
}

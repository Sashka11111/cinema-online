<?php

namespace Liamtseva\Cinema\Enums;

enum VideoPlayerName: string
{
    case KODIK = 'kodik';
    case ALOHA = 'aloha';

    public static function getLabels(): array
    {
        return [
            self::KODIK->value => __('video_player_name.kodik'),
            self::ALOHA->value => __('video_player_name.aloha'),
        ];
    }
}

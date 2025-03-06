<?php

namespace Liamtseva\Cinema\Enums;

enum AttachmentType: string
{
    case PICTURE = 'picture';
    case TRAILER = 'trailer';
    case TEASER = 'teaser';
    case CLIP = 'clip';
    case BEHIND_THE_SCENES = 'behind_the_scenes';
    case BAD_TAKES = 'bad_takes';
    case SHORT_FILMS = 'short_films';

    public static function getLabels(): array
    {
        return [
            self::PICTURE->value => __('attachment.picture'),
            self::TRAILER->value => __('attachment.trailer'),
            self::TEASER->value => __('attachment.teaser'),
            self::CLIP->value => __('attachment.clip'),
            self::BEHIND_THE_SCENES->value => __('attachment.behind_the_scenes'),
            self::BAD_TAKES->value => __('attachment.bad_takes'),
            self::SHORT_FILMS->value => __('attachment.short_films'),
        ];
    }
}

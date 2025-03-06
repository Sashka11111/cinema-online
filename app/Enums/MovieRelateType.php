<?php

namespace Liamtseva\Cinema\Enums;

enum MovieRelateType: string
{
    case SEASON = 'season';
    case SOURCE = 'source';
    case SEQUEL = 'sequel';
    case SIDE_STORY = 'side_story';
    case SUMMARY = 'summary';
    case OTHER = 'other';
    case ADAPTATION = 'adaptation';
    case ALTERNATIVE = 'alternative';
    case PREQUEL = 'prequel';

    public static function getLabels(): array
    {
        return [
            self::SEASON->value => __('movie_relate_type.season'),
            self::SOURCE->value => __('movie_relate_type.source'),
            self::SEQUEL->value => __('movie_relate_type.sequel'),
            self::SIDE_STORY->value => __('movie_relate_type.side_story'),
            self::SUMMARY->value => __('movie_relate_type.summary'),
            self::OTHER->value => __('movie_relate_type.other'),
            self::ADAPTATION->value => __('movie_relate_type.adaptation'),
            self::ALTERNATIVE->value => __('movie_relate_type.alternative'),
            self::PREQUEL->value => __('movie_relate_type.prequel'),
        ];
    }
}

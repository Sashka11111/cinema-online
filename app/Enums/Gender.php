<?php

namespace Liamtseva\Cinema\Enums;

enum Gender: string
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';

    public static function getLabels(): array
    {
        return [
            self::MALE->value => __('gender.male'),
            self::FEMALE->value => __('gender.female'),
            self::OTHER->value => __('gender.other'),
        ];
    }

}

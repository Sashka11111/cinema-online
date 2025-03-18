<?php

namespace Liamtseva\Cinema\Enums;

enum VideoQuality: string
{
    case SD = 'sd';
    case HD = 'hd';
    case FULL_HD = 'full_hd';
    case UHD = 'uhd';

    public static function getLabels(): array
    {
        return [
            self::SD->value => __('video_quality.sd'),
            self::HD->value => __('video_quality.hd'),
            self::FULL_HD->value => __('video_quality.full_hd'),
            self::UHD->value => __('video_quality.uhd'),
        ];
    }

    public static function getMetaTitles(): array
    {
        return [
            self::SD->value => __('video_quality.meta_title.sd'),
            self::HD->value => __('video_quality.meta_title.hd'),
            self::FULL_HD->value => __('video_quality.meta_title.full_hd'),
            self::UHD->value => __('video_quality.meta_title.uhd'),
        ];
    }

    public static function getMetaDescriptions(): array
    {
        return [
            self::SD->value => __('video_quality.meta_description.sd'),
            self::HD->value => __('video_quality.meta_description.hd'),
            self::FULL_HD->value => __('video_quality.meta_description.full_hd'),
            self::UHD->value => __('video_quality.meta_description.uhd'),
        ];
    }

    public static function getMetaImages(): array
    {
        return [
            self::SD->value => __('video_quality.meta_image.sd'),
            self::HD->value => __('video_quality.meta_image.hd'),
            self::FULL_HD->value => __('video_quality.meta_image.full_hd'),
            self::UHD->value => __('video_quality.meta_image.uhd'),
        ];
    }
}

<?php

namespace Liamtseva\Cinema\Enums;

use Carbon\Carbon;

enum Period: string
{
    case WINTER = 'winter';
    case SPRING = 'spring';
    case SUMMER = 'summer';
    case AUTUMN = 'autumn';

    public static function fromDate(mixed $releaseDate): Period
    {
        $releaseDate = $releaseDate instanceof Carbon ? $releaseDate : Carbon::parse($releaseDate);
        $month = $releaseDate->month;

        return match (true) {
            $month >= 3 && $month <= 5 => self::SPRING,
            $month >= 6 && $month <= 8 => self::SUMMER,
            $month >= 9 && $month <= 11 => self::AUTUMN,
            default => self::WINTER,
        };
    }

    public function name(): string
    {
        return match ($this) {
            self::SPRING => __('period.spring'),
            self::SUMMER => __('period.summer'),
            self::AUTUMN => __('period.autumn'),
            self::WINTER => __('period.winter'),
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SPRING => __('period.spring_description'),
            self::SUMMER => __('period.summer_description'),
            self::AUTUMN => __('period.autumn_description'),
            self::WINTER => __('period.winter_description'),
        };
    }

    public function metaTitle(): string
    {
        return match ($this) {
            self::SPRING => __('period.spring_meta_title'),
            self::SUMMER => __('period.summer_meta_title'),
            self::AUTUMN => __('period.autumn_meta_title'),
            self::WINTER => __('period.winter_meta_title'),
        };
    }

    public function metaDescription(): string
    {
        return match ($this) {
            self::SPRING => __('period.spring_meta_description'),
            self::SUMMER => __('period.summer_meta_description'),
            self::AUTUMN => __('period.autumn_meta_description'),
            self::WINTER => __('period.winter_meta_description'),
        };
    }

    public function metaImage(): string
    {
        return match ($this) {
            self::SPRING => '/images/seo/spring-movies.jpg',
            self::SUMMER => '/images/seo/summer-blockbusters.jpg',
            self::AUTUMN => '/images/seo/autumn-movies.jpg',
            self::WINTER => '/images/seo/winter-holidays-movies.jpg',
        };
    }

    public static function getLabels(): array
    {
        return [
            self::WINTER->value => __('period.winter'),
            self::SPRING->value => __('period.spring'),
            self::SUMMER->value => __('period.summer'),
            self::AUTUMN->value => __('period.autumn'),
        ];
    }
}

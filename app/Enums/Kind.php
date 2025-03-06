<?php

namespace Liamtseva\Cinema\Enums;

enum Kind: string
{
    case MOVIE = 'movie';
    case TV_SERIES = 'tv_series';
    case ANIMATED_MOVIE = 'animated_movie';
    case ANIMATED_SERIES = 'animated_series';
    case ANIME = 'anime';

    public static function getLabels(): array
    {
        return [
            self::MOVIE->value => __('kind.movie'),
            self::TV_SERIES->value => __('kind.tv_series'),
            self::ANIMATED_MOVIE->value => __('kind.animated_movie'),
            self::ANIMATED_SERIES->value => __('kind.animated_series'),
            self::ANIME->value => __('kind.anime'),
        ];
    }

    public function name(): string
    {
        return match ($this) {
            self::MOVIE => __('kind.movie_name'),
            self::TV_SERIES => __('kind.tv_series_name'),
            self::ANIMATED_MOVIE => __('kind.animated_movie_name'),
            self::ANIMATED_SERIES => __('kind.animated_series_name'),
            self::ANIME => __('kind.anime_name'),
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::MOVIE => __('kind.movie_description'),
            self::TV_SERIES => __('kind.tv_series_description'),
            self::ANIMATED_MOVIE => __('kind.animated_movie_description'),
            self::ANIMATED_SERIES => __('kind.animated_series_description'),
            self::ANIME => __('kind.anime_description'),
        };
    }

    public function metaTitle(): string
    {
        return match ($this) {
            self::MOVIE => __('kind.movie_meta_title'),
            self::TV_SERIES => __('kind.tv_series_meta_title'),
            self::ANIMATED_MOVIE => __('kind.animated_movie_meta_title'),
            self::ANIMATED_SERIES => __('kind.animated_series_meta_title'),
            self::ANIME => __('kind.anime_meta_title'),
        };
    }

    public function metaDescription(): string
    {
        return match ($this) {
            self::MOVIE => __('kind.movie_meta_description'),
            self::TV_SERIES => __('kind.tv_series_meta_description'),
            self::ANIMATED_MOVIE => __('kind.animated_movie_meta_description'),
            self::ANIMATED_SERIES => __('kind.animated_series_meta_description'),
            self::ANIME => __('kind.anime_meta_description'),
        };
    }

    public function metaImage(): string
    {
        return match ($this) {
            self::MOVIE => '/images/seo/movie.jpg',
            self::TV_SERIES => '/images/seo/tv-series.jpg',
            self::ANIMATED_MOVIE => '/images/seo/animated-movie.jpg',
            self::ANIMATED_SERIES => '/images/seo/animated-series.jpg',
            self::ANIME => '/images/seo/anime.jpg',
        };
    }
}

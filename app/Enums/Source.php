<?php

namespace Liamtseva\Cinema\Enums;

enum Source: string
{
    case DORAMA = 'dorama';
    case MANGA = 'manga';
    case GAME = 'game';
    case NOVEL = 'novel';
    case COMIC = 'comic';
    case LIGHT_NOVEL = 'light_novel';
    case WEBTOON = 'webtoon';
    case TV_SHOW = 'tv_show';
    case MOVIE = 'movie';

    // Статичний метод для міток
    public static function getLabels(): array
    {
        return [
            self::DORAMA->value => __('source.dorama.name'),
            self::MANGA->value => __('source.manga.name'),
            self::GAME->value => __('source.game.name'),
            self::NOVEL->value => __('source.novel.name'),
            self::COMIC->value => __('source.comic.name'),
            self::LIGHT_NOVEL->value => __('source.light_novel.name'),
            self::WEBTOON->value => __('source.webtoon.name'),
            self::TV_SHOW->value => __('source.tv_show.name'),
            self::MOVIE->value => __('source.movie.name'),
        ];
    }

    // Метод для отримання опису
    public function description(): string
    {
        return __("source.{$this->value}.description");
    }

    // Метод для отримання meta title
    public function metaTitle(): string
    {
        return __("source.{$this->value}.meta_title");
    }

    // Метод для отримання meta description
    public function metaDescription(): string
    {
        return __("source.{$this->value}.meta_description");
    }

    // Метод для отримання meta image
    public function metaImage(): string
    {
        return __("source.{$this->value}.meta_image");
    }
}

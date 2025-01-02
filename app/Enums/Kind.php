<?php

namespace Liamtseva\Cinema\Enums;

enum Kind: string
{
    case MOVIE = 'movie';
    case TV_SERIES = 'tv_series';
    case ANIMATED_MOVIE = 'animated_movie';
    case ANIMATED_SERIES = 'animated_series';
    case ANIME = 'anime';

    public function name(): string
    {
        return match ($this) {
            self::MOVIE => 'Фільм',
            self::TV_SERIES => 'ТВ серіал',
            self::ANIMATED_MOVIE => 'Мультфільм',
            self::ANIMATED_SERIES => 'Мультсеріал',
            self::ANIME => 'Аніме',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::MOVIE => 'Повнометражний фільм, який триває від 1 до кількох годин.',
            self::TV_SERIES => 'Телекінематографічний серіал, який складається з кількох сезонів.',
            self::ANIMATED_MOVIE => 'Мультфільм, який представляє собою анімацію у вигляді повнометражного фільму.',
            self::ANIMATED_SERIES => 'Мультсеріал, що складається з кількох епізодів, де основна історія розгортається в анімаційному форматі.',
            self::ANIME => 'Японська анімація, що включає як серіали, так і фільми, із характерним стилем та сюжетом.',
        };
    }

    public function metaTitle(): string
    {
        return match ($this) {
            self::MOVIE => 'Фільми онлайн | Кінопортал',
            self::TV_SERIES => 'ТВ серіали онлайн | Кінопортал',
            self::ANIMATED_MOVIE => 'Мультфільми онлайн | Кінопортал',
            self::ANIMATED_SERIES => 'Мультсеріали онлайн | Кінопортал',
            self::ANIME => 'Аніме онлайн | Кінопортал',
        };
    }

    public function metaDescription(): string
    {
        return match ($this) {
            self::MOVIE => 'Перегляньте найкращі фільми онлайн, від класики до новинок кіноіндустрії.',
            self::TV_SERIES => 'Ознайомтеся з найкращими ТВ серіалами онлайн, від комедій до драм.',
            self::ANIMATED_MOVIE => 'Перегляньте анімаційні фільми, що захоплюють своєю графікою та сюжетами.',
            self::ANIMATED_SERIES => 'Дивіться мультсеріали для всіх вікових категорій онлайн.',
            self::ANIME => 'Огляньте кращі аніме серіали та фільми онлайн, від популярних до рідкісних тайтлів.',
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

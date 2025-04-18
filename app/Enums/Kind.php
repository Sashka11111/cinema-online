<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Kind: string implements HasColor, HasIcon, HasLabel
{
    case MOVIE = 'movie';
    case TV_SERIES = 'tv_series';
    case ANIMATED_MOVIE = 'animated_movie';
    case ANIMATED_SERIES = 'animated_series';
    case ANIME = 'anime';

    /**
     * Повертає перекладену назву типу для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __('kind.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::MOVIE => 'info',         // Блакитний для фільмів
            self::TV_SERIES => 'success',  // Зелений для серіалів
            self::ANIMATED_MOVIE => 'primary', // Фіолетовий для мультфільмів
            self::ANIMATED_SERIES => 'warning', // Жовтий для мультсеріалів
            self::ANIME => 'danger',         // Рожевий для аніме
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::MOVIE => 'heroicon-o-film',
            self::TV_SERIES => 'heroicon-o-tv',
            self::ANIMATED_MOVIE => 'heroicon-o-sparkles',
            self::ANIMATED_SERIES => 'heroicon-o-play-circle',
            self::ANIME => 'heroicon-o-sun',
        };
    }

    /**
     * Повертає перекладену назву типу (альтернативна версія).
     */
    public function getName(): string
    {
        return __("kind.{$this->value}_name");
    }

    /**
     * Повертає опис типу з файлу локалізації.
     */
    public function getDescription(): string
    {
        return __("kind.{$this->value}_description");
    }

    /**
     * Повертає мета-заголовок для SEO з файлу локалізації.
     */
    public function getMetaTitle(): string
    {
        return __("kind.{$this->value}_meta_title");
    }

    /**
     * Повертає мета-опис для SEO з файлу локалізації.
     */
    public function getMetaDescription(): string
    {
        return __("kind.{$this->value}_meta_description");
    }

    /**
     * Повертає шлях до мета-зображення для SEO.
     */
    public function getMetaImage(): string
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

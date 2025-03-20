<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Source: string implements HasColor, HasIcon, HasLabel
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

    /**
     * Повертає перекладену назву джерела для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __('source.'.$this->value.'.name');
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::DORAMA => 'info',       // Блакитний для дорам
            self::MANGA => 'success',     // Зелений для манги
            self::GAME => 'warning',      // Жовтий для ігор
            self::NOVEL => 'gray',        // Сірий для новел
            self::COMIC => 'primary',      // Фіолетовий для коміксів
            self::LIGHT_NOVEL => 'pink',  // Рожевий для легких новел
            self::WEBTOON => 'cyan',      // Бірюзовий для вебтунів
            self::TV_SHOW => 'orange',    // Помаранчевий для ТВ-шоу
            self::MOVIE => 'primary',     // Синій для фільмів
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::DORAMA => 'heroicon-o-tv',
            self::MANGA => 'heroicon-o-book-open',
            self::GAME => 'heroicon-o-cube',
            self::NOVEL => 'heroicon-o-document-text',
            self::COMIC => 'heroicon-o-newspaper',
            self::LIGHT_NOVEL => 'heroicon-o-document',
            self::WEBTOON => 'heroicon-o-device-phone-mobile',
            self::TV_SHOW => 'heroicon-o-play-circle',
            self::MOVIE => 'heroicon-o-film',
        };
    }

    /**
     * Повертає опис джерела з файлу локалізації.
     */
    public function getDescription(): string
    {
        return __("source.{$this->value}.description");
    }

    /**
     * Повертає мета-заголовок для SEO з файлу локалізації.
     */
    public function getMetaTitle(): string
    {
        return __("source.{$this->value}.meta_title");
    }

    /**
     * Повертає мета-опис для SEO з файлу локалізації.
     */
    public function getMetaDescription(): string
    {
        return __("source.{$this->value}.meta_description");
    }

    /**
     * Повертає шлях до мета-зображення для SEO з файлу локалізації.
     */
    public function getMetaImage(): string
    {
        return __("source.{$this->value}.meta_image");
    }
}

<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ApiSourceName: string implements HasColor, HasIcon, HasLabel
{
    case TMDB = 'tmdb';
    case SHIKI = 'shiki';
    case IMDB = 'imdb';
    case ANILIST = 'anilist';

    /**
     * Повертає перекладену назву джерела API для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __('api_source_name.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::TMDB => 'success',  // Зелений для TMDB
            self::SHIKI => 'primary',  // Фіолетовий для Shiki
            self::IMDB => 'warning',  // Жовтий для IMDB
            self::ANILIST => 'info',  // Блакитний для Anilist
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::TMDB => 'heroicon-o-film',
            self::SHIKI => 'heroicon-o-sun',
            self::IMDB => 'heroicon-o-star',
            self::ANILIST => 'heroicon-o-book-open',
        };
    }
}

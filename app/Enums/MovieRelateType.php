<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum MovieRelateType: string implements HasColor, HasIcon, HasLabel
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

    /**
     * Повертає перекладену назву типу зв’язку для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __('movie_relate_type.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::SEASON => 'info',       // Блакитний для сезону
            self::SOURCE => 'gray',       // Сірий для джерела
            self::SEQUEL => 'success',    // Зелений для сиквелу
            self::SIDE_STORY => 'primary', // Фіолетовий для побічної історії
            self::SUMMARY => 'warning',   // Жовтий для підсумку
            self::OTHER => 'gray',        // Сірий для іншого
            self::ADAPTATION => 'orange', // Помаранчевий для адаптації
            self::ALTERNATIVE => 'pink',  // Рожевий для альтернативи
            self::PREQUEL => 'danger',    // Червоний для приквелу
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::SEASON => 'heroicon-o-calendar',
            self::SOURCE => 'heroicon-o-book-open',
            self::SEQUEL => 'heroicon-o-arrow-right',
            self::SIDE_STORY => 'heroicon-o-arrow-path',
            self::SUMMARY => 'heroicon-o-document-text',
            self::OTHER => 'heroicon-o-ellipsis-horizontal',
            self::ADAPTATION => 'heroicon-o-film',
            self::ALTERNATIVE => 'heroicon-o-arrows-right-left',
            self::PREQUEL => 'heroicon-o-arrow-left',
        };
    }
}

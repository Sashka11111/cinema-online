<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserListType: string implements HasColor, HasIcon, HasLabel
{
    case FAVORITE = 'favorite';
    case NOT_WATCHING = 'not_watching';
    case WATCHING = 'watching';
    case PLANNED = 'planned';
    case STOPPED = 'stopped';
    case REWATCHING = 'rewatching';
    case WATCHED = 'watched';

    /**
     * Повертає перекладену назву для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __('user_list_type.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::FAVORITE => 'warning',     // Жовтий для улюбленого
            self::NOT_WATCHING => 'gray', // Сірий для "не дивлюся"
            self::WATCHING => 'success',  // Зелений для "дивлюся"
            self::PLANNED => 'warning',   // Жовтий для "в планах"
            self::STOPPED => 'danger',    // Червоний для "перестав"
            self::REWATCHING => 'info',   // Блакитний для "передивляюсь"
            self::WATCHED => 'primary',    // Фіолетовий для "переглянуто"
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::FAVORITE => 'heroicon-o-heart',
            self::NOT_WATCHING => 'heroicon-o-eye-slash',
            self::WATCHING => 'heroicon-o-eye',
            self::PLANNED => 'heroicon-o-clock',
            self::STOPPED => 'heroicon-o-pause',
            self::REWATCHING => 'heroicon-o-arrow-path',
            self::WATCHED => 'heroicon-o-check-circle',
        };
    }
}

<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum RoomStatus: string implements HasColor, HasIcon, HasLabel
{
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case NOT_STARTED = 'not_started';

    /**
     * Повертає перекладену назву статусу для Filament.
     */
    public function getLabel(): ?string
    {
        return __('room_status.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ACTIVE => 'success',     // Зелений для активних
            self::COMPLETED => 'danger',   // Червоний для завершених
            self::NOT_STARTED => 'warning', // Жовтий для не розпочатих
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'heroicon-o-play',
            self::COMPLETED => 'heroicon-o-check-circle',
            self::NOT_STARTED => 'heroicon-o-clock',
        };
    }
}

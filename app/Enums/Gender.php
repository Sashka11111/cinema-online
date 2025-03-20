<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Gender: string implements HasColor, HasIcon, HasLabel
{
    case MALE = 'male';
    case FEMALE = 'female';
    case OTHER = 'other';

    /**
     * Повертає перекладену назву гендеру для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __('gender.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::MALE => 'info',    // Блакитний для чоловіків
            self::FEMALE => 'warning',  // Рожевий для жінок
            self::OTHER => 'gray',   // Сірий для інших
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::MALE => 'fas-male',  // Іконка для чоловіків
            self::FEMALE => 'fas-female',       // Іконка для жінок
            self::OTHER => 'bx-male-female', // Іконка для інших
        };
    }
}

<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Role: string implements HasColor, HasIcon, HasLabel
{
    case USER = 'user';
    case MODERATOR = 'moderator';
    case ADMIN = 'admin';

    // Локалізовані мітки для Filament
    public function getLabel(): ?string
    {
        return __('role.'.$this->value);
    }

    // Кольори для відображення у Filament
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::USER => 'success',      // Сірий для звичайних користувачів
            self::MODERATOR => 'info', // Блакитний для модераторів
            self::ADMIN => 'primary',
        };
    }

    // Іконки для Filament
    public function getIcon(): ?string
    {
        return match ($this) {
            self::USER => 'heroicon-o-user',
            self::MODERATOR => 'heroicon-o-shield-check',
            self::ADMIN => 'heroicon-o-key',
        };
    }
}

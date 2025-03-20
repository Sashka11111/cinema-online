<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum VideoPlayerName: string implements HasColor, HasIcon, HasLabel
{
    case KODIK = 'kodik';
    case ALOHA = 'aloha';

    /**
     * Повертає перекладену назву відеоплеєра для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __('video_player_name.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::KODIK => 'info',   // Блакитний для Kodik
            self::ALOHA => 'primary', // Фіолетовий для Aloha
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::KODIK => 'heroicon-o-play-circle',
            self::ALOHA => 'heroicon-o-video-camera',
        };
    }
}

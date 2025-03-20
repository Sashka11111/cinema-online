<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum AttachmentType: string implements HasColor, HasIcon, HasLabel
{
    case PICTURE = 'picture';
    case TRAILER = 'trailer';
    case TEASER = 'teaser';
    case CLIP = 'clip';
    case BEHIND_THE_SCENES = 'behind_the_scenes';
    case BAD_TAKES = 'bad_takes';
    case SHORT_FILMS = 'short_films';

    /**
     * Повертає перекладену назву типу вкладення для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __('attachment.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::PICTURE => 'gray',         // Сірий для зображень
            self::TRAILER => 'success',      // Зелений для трейлерів
            self::TEASER => 'info',          // Блакитний для тизерів
            self::CLIP => 'warning',         // Жовтий для кліпів
            self::BEHIND_THE_SCENES => 'primary', // Фіолетовий для закулісся
            self::BAD_TAKES => 'danger',     // Червоний для невдалих дублів
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::PICTURE => 'heroicon-o-photo',
            self::TRAILER => 'heroicon-o-film',
            self::TEASER => 'heroicon-o-play-circle',
            self::CLIP => 'heroicon-o-scissors',
            self::BEHIND_THE_SCENES => 'heroicon-o-video-camera',
            self::BAD_TAKES => 'heroicon-o-x-circle',
            self::SHORT_FILMS => 'heroicon-o-clock',
        };
    }
}

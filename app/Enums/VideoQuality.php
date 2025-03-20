<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum VideoQuality: string implements HasColor, HasIcon, HasLabel
{
    case SD = 'sd';
    case HD = 'hd';
    case FULL_HD = 'full_hd';
    case UHD = 'uhd';

    /**
     * Повертає перекладену мітку для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __('video_quality.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::SD => 'gray',      // Сірий для SD
            self::HD => 'info',      // Блакитний для HD
            self::FULL_HD => 'success', // Зелений для Full HD
            self::UHD => 'primary',   // Фіолетовий для UHD
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::SD => 'heroicon-o-eye',
            self::HD => 'heroicon-o-eye',
            self::FULL_HD => 'heroicon-o-eye',
            self::UHD => 'heroicon-o-eye',
        };
    }

    /**
     * Повертає мета-заголовок для SEO з файлу локалізації.
     */
    public function getMetaTitle(): string
    {
        return __("video_quality.meta_title.{$this->value}");
    }

    /**
     * Повертає мета-опис для SEO з файлу локалізації.
     */
    public function getMetaDescription(): string
    {
        return __("video_quality.meta_description.{$this->value}");
    }

    /**
     * Повертає шлях до мета-зображення для SEO з файлу локалізації.
     */
    public function getMetaImage(): string
    {
        return __("video_quality.meta_image.{$this->value}");
    }
}

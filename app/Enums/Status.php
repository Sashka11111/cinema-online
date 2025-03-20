<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Status: string implements HasColor, HasIcon, HasLabel
{
    case ANONS = 'anons';
    case ONGOING = 'ongoing';
    case RELEASED = 'released';
    case CANCELED = 'canceled';
    case RUMORED = 'rumored';

    /**
     * Повертає перекладену назву статусу для Filament.
     */
    public function getLabel(): ?string
    {
        return __('status.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ANONS => 'warning',   // Жовтий для анонсів
            self::ONGOING => 'success', // Зелений для поточних
            self::RELEASED => 'info',   // Блакитний для випущених
            self::CANCELED => 'danger', // Червоний для скасованих
            self::RUMORED => 'gray',    // Сірий для чуток
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::ANONS => 'heroicon-o-megaphone',
            self::ONGOING => 'heroicon-o-play',
            self::RELEASED => 'heroicon-o-check-circle',
            self::CANCELED => 'heroicon-o-x-circle',
            self::RUMORED => 'heroicon-o-question-mark-circle',
        };
    }

    /**
     * Повертає опис статусу.
     */
    public function getDescription(): string
    {
        return __("status.{$this->value}_description");
    }

    /**
     * Повертає мета-заголовок для SEO.
     */
    public function getMetaTitle(): string
    {
        return __("status.{$this->value}_meta_title");
    }

    /**
     * Повертає мета-опис для SEO.
     */
    public function getMetaDescription(): string
    {
        return __("status.{$this->value}_meta_description");
    }

    /**
     * Повертає шлях до мета-зображення для SEO.
     */
    public function getMetaImage(): string
    {
        return match ($this) {
            self::ANONS => '/images/seo/anons-movies.jpg',
            self::ONGOING => '/images/seo/ongoing-series.jpg',
            self::RELEASED => '/images/seo/released-movies.jpg',
            self::CANCELED => '/images/seo/canceled-projects.jpg',
            self::RUMORED => '/images/seo/rumored-projects.jpg',
        };
    }
}

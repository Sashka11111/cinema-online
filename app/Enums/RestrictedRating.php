<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum RestrictedRating: string implements HasColor, HasLabel
{
    case G = 'g';
    case PG = 'pg';
    case PG_13 = 'pg_13';
    case R = 'r';
    case NC_17 = 'nc_17';

    /**
     * Повертає перекладену мітку для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __('restricted_rating.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::G => 'success',    // Зелене — без обмежень
            self::PG => 'info',      // Блакитне — м’які обмеження
            self::PG_13 => 'warning', // Жовте — помірні обмеження
            self::R => 'danger',     // Червоне — серйозні обмеження
            self::NC_17 => 'gray',   // Сіре — найвищий рівень
        };
    }

    /**
     * Повертає підказку для користувача з файлу локалізації.
     */
    public function getHint(): string
    {
        return __("restricted_rating.{$this->value}_hint");
    }

    /**
     * Повертає мета-заголовок для SEO з файлу локалізації.
     */
    public function getMetaTitle(): string
    {
        return __("restricted_rating.{$this->value}_meta_title");
    }

    /**
     * Повертає мета-опис для SEO з файлу локалізації.
     */
    public function getMetaDescription(): string
    {
        return __("restricted_rating.{$this->value}_meta_description");
    }

    /**
     * Повертає шлях до мета-зображення для SEO.
     */
    public function getMetaImage(): string
    {
        return match ($this) {
            self::G => '/images/seo/g.jpg',
            self::PG => '/images/seo/pg.jpg',
            self::PG_13 => '/images/seo/pg-13.jpg',
            self::R => '/images/seo/r.jpg',
            self::NC_17 => '/images/seo/nc-17.jpg',
        };
    }
}

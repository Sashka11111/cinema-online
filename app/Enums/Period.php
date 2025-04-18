<?php

namespace Liamtseva\Cinema\Enums;

use Carbon\Carbon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Period: string implements HasColor, HasIcon, HasLabel
{
    case WINTER = 'winter';
    case SPRING = 'spring';
    case SUMMER = 'summer';
    case AUTUMN = 'autumn';

    /**
     * Визначає період за датою випуску.
     */
    public static function fromDate(mixed $releaseDate): Period
    {
        $releaseDate = $releaseDate instanceof Carbon ? $releaseDate : Carbon::parse($releaseDate);
        $month = $releaseDate->month;

        return match (true) {
            $month >= 3 && $month <= 5 => self::SPRING,
            $month >= 6 && $month <= 8 => self::SUMMER,
            $month >= 9 && $month <= 11 => self::AUTUMN,
            default => self::WINTER,
        };
    }

    /**
     * Повертає перекладену назву періоду для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __('period.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::WINTER => 'gray',    // Сірий для зими
            self::SPRING => 'success', // Зелений для весни
            self::SUMMER => 'warning', // Жовтий для літа
            self::AUTUMN => 'info',
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::WINTER => 'clarity-snowflake-line',
            self::SPRING => 'heroicon-o-sun',
            self::SUMMER => 'heroicon-o-fire',
            self::AUTUMN => 'bx-cloud-rain',
        };
    }

    /**
     * Повертає перекладену назву періоду (альтернатива для зворотної сумісності).
     */
    public function getName(): string
    {
        return __('period.'.$this->value);
    }

    /**
     * Повертає опис періоду з файлу локалізації.
     */
    public function getDescription(): string
    {
        return __("period.{$this->value}_description");
    }

    /**
     * Повертає мета-заголовок для SEO з файлу локалізації.
     */
    public function getMetaTitle(): string
    {
        return __("period.{$this->value}_meta_title");
    }

    /**
     * Повертає мета-опис для SEO з файлу локалізації.
     */
    public function getMetaDescription(): string
    {
        return __("period.{$this->value}_meta_description");
    }

    /**
     * Повертає шлях до мета-зображення для SEO.
     */
    public function getMetaImage(): string
    {
        return match ($this) {
            self::WINTER => '/images/seo/winter-holidays-movies.jpg',
            self::SPRING => '/images/seo/spring-movies.jpg',
            self::SUMMER => '/images/seo/summer-blockbusters.jpg',
            self::AUTUMN => '/images/seo/autumn-movies.jpg',
        };
    }
}

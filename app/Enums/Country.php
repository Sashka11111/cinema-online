<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum Country: string implements HasColor, HasIcon, HasLabel
{
    case UKRAINE = 'ua';
    case USA = 'us';
    case JAPAN = 'jp';
    case CHINA = 'cn';
    case FRANCE = 'fr';
    case INDIA = 'in';
    case SPAIN = 'es';
    case UNITED_KINGDOM = 'gb';
    case CANADA = 'ca';
    case GERMANY = 'de';
    case ITALY = 'it';
    case AUSTRALIA = 'au';
    case BRAZIL = 'br';
    case MEXICO = 'mx';
    case SOUTH_KOREA = 'kr';
    case TURKEY = 'tr';
    case ARGENTINA = 'ar';
    case SWEDEN = 'se';
    case BELGIUM = 'be';

    /**
     * Повертає перекладену назву країни для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __("countries.{$this->value}.name");
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::UKRAINE => 'warning', // Жовтий
            self::USA => 'danger',      // Червоний
            self::JAPAN => 'info',      // Блакитний
            default => 'gray',          // Сірий для інших
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return "flag-{$this->value}";
    }

    /**
     * Повертає опис країни.
     */
    public function getDescription(): string
    {
        return __("countries.{$this->value}.description");
    }

    /**
     * Повертає мета-заголовок для SEO.
     */
    public function getMetaTitle(): string
    {
        return __("countries.{$this->value}.meta_title");
    }
}

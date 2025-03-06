<?php

namespace Liamtseva\Cinema\Enums;

enum Country: string
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

    public function name(): string
    {
        return __("countries.{$this->value}.name");
    }

    public function description(): string
    {
        return __("countries.{$this->value}.description");
    }

    public function icon(): string
    {
        return asset("icons/countries/{$this->value}.png");
    }

    public function metaTitle(): string
    {
        return __("countries.{$this->value}.meta_title");
    }
}

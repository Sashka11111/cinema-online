<?php

namespace Liamtseva\Cinema\Enums;

enum VideoQuality: string
{
    case SD = 'sd'; // Standard Definition
    case HD = 'hd'; // High Definition
    case FULL_HD = 'full_hd'; // Full High Definition
    case UHD = 'uhd'; // Ultra High Definition

    public function name(): string
    {
        return match ($this) {
            self::SD => 'Стандартне визначення (SD)',
            self::HD => 'Висока якість (HD)',
            self::FULL_HD => 'Повне HD (Full HD)',
            self::UHD => 'Ультрависока якість (UHD)',
        };
    }

    public function metaTitle(): string
    {
        return match ($this) {
            self::SD => 'Стандартне визначення (SD) - Погляньте на якість відео',
            self::HD => 'Висока якість (HD) - Насолоджуйтеся чітким відео',
            self::FULL_HD => 'Повне HD (Full HD) - Відео з найкращою якістю',
            self::UHD => 'Ультрависока якість (UHD) - Чіткість без компромісів',
        };
    }

    public function metaDescription(): string
    {
        return match ($this) {
            self::SD => 'Відео з якістю стандартного визначення, ідеально підходить для старих пристроїв.',
            self::HD => 'Висока якість відео для кращих деталей і чіткості.',
            self::FULL_HD => 'Відео в Full HD роздільній здатності для найкращого перегляду.',
            self::UHD => 'Ультрависока якість з чіткістю до 4K для неймовірно реалістичного зображення.',
        };
    }

    public function metaImage(): string
    {
        return match ($this) {
            self::SD => 'url_to_sd_image.jpg',
            self::HD => 'url_to_hd_image.jpg',
            self::FULL_HD => 'url_to_full_hd_image.jpg',
            self::UHD => 'url_to_uhd_image.jpg',
        };
    }
}

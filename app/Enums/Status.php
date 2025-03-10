<?php

namespace Liamtseva\Cinema\Enums;

enum Status: string
{
    case ANONS = 'anons';
    case ONGOING = 'ongoing';
    case RELEASED = 'released';
    case CANCELED = 'canceled';
    case RUMORED = 'rumored';

    /**
     * Повертає масив із перекладеними назвами для використання у фільтрах чи списках.
     */
    public static function getLabels(): array
    {
        return [
            self::ANONS->value => __('status.anons'),
            self::ONGOING->value => __('status.ongoing'),
            self::RELEASED->value => __('status.released'),
            self::CANCELED->value => __('status.canceled'),
            self::RUMORED->value => __('status.rumored'),
        ];
    }

    /**
     * Повертає перекладену назву статусу.
     */
    public function name(): string
    {
        return __(sprintf('status.%s', $this->value));
    }

    /**
     * Повертає опис статусу.
     */
    public function description(): string
    {
        return __(sprintf('status.%s_description', $this->value));
    }

    /**
     * Повертає мета-заголовок для SEO.
     */
    public function metaTitle(): string
    {
        return __(sprintf('status.%s_meta_title', $this->value));
    }

    /**
     * Повертає мета-опис для SEO.
     */
    public function metaDescription(): string
    {
        return __(sprintf('status.%s_meta_description', $this->value));
    }

    /**
     * Повертає шлях до мета-зображення для SEO.
     */
    public function metaImage(): string
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

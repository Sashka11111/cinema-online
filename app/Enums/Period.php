<?php

namespace Liamtseva\Cinema\Enums;

use Carbon\Carbon;

enum Period: string
{
    case WINTER = 'winter';
    case SPRING = 'spring';
    case SUMMER = 'summer';
    case AUTUMN = 'autumn';

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

    public function name(): string
    {
        return match ($this) {
            self::SPRING => 'Весна',
            self::SUMMER => 'Літо',
            self::AUTUMN => 'Осінь',
            self::WINTER => 'Зима',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SPRING => 'Сезон нових початків, коли природа оживає, цвітуть квіти та збільшується кількість кіноновинок.',
            self::SUMMER => 'Час відпочинку і блокбастерів, коли кінозали наповнюються глядачами, що шукають розваг і свіжих прем\'єр.',
            self::AUTUMN => 'Пора для глибоких історій та фільмів, коли погода охолоджується, і люди частіше звертаються до кінозалів.',
            self::WINTER => 'Сезон святкових фільмів і кіно для всієї родини, ідеальний для перегляду в затишному залі кінотеатру.',
        };
    }

    public function metaTitle(): string
    {
        return match ($this) {
            self::SPRING => 'Весняні прем\'єри та кіноновинки | '.config('app.name'),
            self::SUMMER => 'Літні блокбастери та найкращі фільми | '.config('app.name'),
            self::AUTUMN => 'Осінні кінопрем\'єри та кінохіти | '.config('app.name'),
            self::WINTER => 'Зимові фільми та святкові прем\'єри | '.config('app.name'),
        };
    }

    public function metaDescription(): string
    {
        return match ($this) {
            self::SPRING => 'Дізнайтеся про весняні прем\'єри та нові кінофільми, які відображають настрій пробудження та відновлення природи.',
            self::SUMMER => 'Огляньте літні кінопрем\'єри: блокбастери, комедії та новинки кіно, які ідеально підійдуть для відпочинку.',
            self::AUTUMN => 'Відкрийте для себе осінні кінопрем\'єри: драматичні історії, кінофестивалі та нові фільми, що приносять теплоту восени.',
            self::WINTER => 'Пориньте у світ зимових прем\'єр: святкові фільми, сімейне кіно та класика для затишних вечорів у кінотеатрі.',
        };
    }

    public function metaImage(): string
    {
        return match ($this) {
            self::SPRING => '/images/seo/spring-movies.jpg',
            self::SUMMER => '/images/seo/summer-blockbusters.jpg',
            self::AUTUMN => '/images/seo/autumn-movies.jpg',
            self::WINTER => '/images/seo/winter-holidays-movies.jpg',
        };
    }
}

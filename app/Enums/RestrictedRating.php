<?php

namespace Liamtseva\Cinema\Enums;

enum RestrictedRating: string
{
    case PG = 'pg';
    case PG_13 = 'pg_13';
    case R = 'r';
    case NC_17 = 'nc_17';
    case G = 'g';

    public function value(): int
    {
        return match ($this) {
            self::PG => 0,
            self::PG_13 => 13,
            self::R => 17,
            self::NC_17 => 18,
            self::G => 0,
        };
    }

    public function hint(): string
    {
        return match ($this) {
            self::PG => 'Рекомендовано для загальної аудиторії.',
            self::PG_13 => 'Дозволено для глядачів від 13 років і старше.',
            self::R => 'Обмеження для глядачів старше 17 років.',
            self::NC_17 => 'Тільки для глядачів старше 18 років.',
            self::G => 'Підходить для всіх вікових груп.',
        };
    }

    public function icon(): ?string
    {
        return match ($this) {
            self::PG => '/icons/ratings/pg.png',
            self::PG_13 => '/icons/ratings/pg-13.png',
            self::R => '/icons/ratings/r.png',
            self::NC_17 => '/icons/ratings/nc-17.png',
            self::G => '/icons/ratings/g.png',
        };
    }

    public function metaTitle(): string
    {
        return match ($this) {
            self::PG => 'Фільми і серіали з рейтингом PG',
            self::PG_13 => 'Фільми і серіали з рейтингом PG-13',
            self::R => 'Фільми і серіали з рейтингом R',
            self::NC_17 => 'Фільми і серіали з рейтингом NC-17',
            self::G => 'Фільми і серіали з рейтингом G',
        };
    }

    public function metaDescription(): string
    {
        return match ($this) {
            self::PG => 'Фільми, які підходять для загальної аудиторії без обмежень.',
            self::PG_13 => 'Фільми, дозволені для глядачів від 13 років.',
            self::R => 'Фільми для глядачів старше 17 років через вміст, що містить насильство або інші обмеження.',
            self::NC_17 => 'Фільми для глядачів старше 18 років, що містять інтенсивний вміст.',
            self::G => 'Фільми, які підходять для всіх вікових категорій.',
        };
    }

    public function metaImage(): string
    {
        return match ($this) {
            self::PG => '/images/seo/pg.jpg',
            self::PG_13 => '/images/seo/pg-13.jpg',
            self::R => '/images/seo/r.jpg',
            self::NC_17 => '/images/seo/nc-17.jpg',
            self::G => '/images/seo/g.jpg',
        };
    }
}

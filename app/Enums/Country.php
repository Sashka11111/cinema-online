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
    case RUSSIA = 'ru';
    case TURKEY = 'tr';
    case ARGENTINA = 'ar';
    case SWEDEN = 'se';
    case BELGIUM = 'be';

    public function name(): string
    {
        return match ($this) {
            self::UKRAINE => 'Україна',
            self::USA => 'США',
            self::JAPAN => 'Японія',
            self::CHINA => 'Китай',
            self::FRANCE => 'Франція',
            self::INDIA => 'Індія',
            self::SPAIN => 'Іспанія',
            self::UNITED_KINGDOM => 'Велика Британія',
            self::CANADA => 'Канада',
            self::GERMANY => 'Німеччина',
            self::ITALY => 'Італія',
            self::AUSTRALIA => 'Австралія',
            self::BRAZIL => 'Бразилія',
            self::MEXICO => 'Мексика',
            self::SOUTH_KOREA => 'Південна Корея',
            self::RUSSIA => 'Росія',
            self::TURKEY => 'Туреччина',
            self::ARGENTINA => 'Аргентина',
            self::SWEDEN => 'Швеція',
            self::BELGIUM => 'Бельгія',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::UKRAINE => 'Україна — держава у Східній Європі, відома своєю культурною спадщиною та природними красотами.',
            self::USA => 'США — країна в Північній Америці, одна з найбільших економік світу та лідер в технологічних інноваціях.',
            self::JAPAN => 'Японія — острівна країна в Східній Азії, відома своєю технологією, культурою та унікальними традиціями.',
            self::CHINA => 'Китай — найбільша країна за чисельністю населення та одна з провідних економік світу.',
            self::FRANCE => 'Франція — країна в Західній Європі, відома своєю культурною спадщиною, вином та мистецтвом.',
            self::INDIA => 'Індія — велика країна в Південній Азії, що має багатий культурний спадок та величезне населення.',
            self::SPAIN => 'Іспанія — країна на південному заході Європи, відома своєю історією, культурою та гастрономією.',
            self::UNITED_KINGDOM => 'Велика Британія — країна в Південно-Західній Європі, історично одна з найбільших колоніальних імперій.',
            self::CANADA => 'Канада — країна в Північній Америці, відома своєю величезною природною красою та високим рівнем життя.',
            self::GERMANY => 'Німеччина — країна в Центральній Європі, одна з найбільших економік світу та лідер у технологіях.',
            self::ITALY => 'Італія — країна в Південній Європі, відома своєю історією, культурою та кулінарією.',
            self::AUSTRALIA => 'Австралія — країна на південному континенті, знаменита своєю природною різноманітністю та культурною спадщиною.',
            self::BRAZIL => 'Бразилія — найбільша країна в Південній Америці, відома своєю природною красою та карнавальними традиціями.',
            self::MEXICO => 'Мексика — країна в Північній Америці з багатою культурною спадщиною та унікальними традиціями.',
            self::SOUTH_KOREA => 'Південна Корея — технологічно розвинена країна в Східній Азії, відома своєю культурою та індустрією розваг.',
            self::RUSSIA => 'Росія — найбільша країна за територією, з багатою історією та величезними природними ресурсами.',
            self::TURKEY => 'Туреччина — країна на стику Європи та Азії, відома своєю історією, культурою та гастрономією.',
            self::ARGENTINA => 'Аргентина — країна в Південній Америці, відома своєю культурною спадщиною, природними красотами та футболом.',
            self::SWEDEN => 'Швеція — країна в Скандинавії, відома своєю інноваційністю, природою та високим рівнем життя.',
            self::BELGIUM => 'Бельгія — невелика країна в Західній Європі, з великою історією та культурним спадком.',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::UKRAINE => '/icons/countries/ua.png',
            self::USA => '/icons/countries/us.png',
            self::JAPAN => '/icons/countries/jp.png',
            self::CHINA => '/icons/countries/cn.png',
            self::FRANCE => '/icons/countries/fr.png',
            self::INDIA => '/icons/countries/in.png',
            self::SPAIN => '/icons/countries/es.png',
            self::UNITED_KINGDOM => '/icons/countries/gb.png',
            self::CANADA => '/icons/countries/ca.png',
            self::GERMANY => '/icons/countries/de.png',
            self::ITALY => '/icons/countries/it.png',
            self::AUSTRALIA => '/icons/countries/au.png',
            self::BRAZIL => '/icons/countries/br.png',
            self::MEXICO => '/icons/countries/mx.png',
            self::SOUTH_KOREA => '/icons/countries/kr.png',
            self::RUSSIA => '/icons/countries/ru.png',
            self::TURKEY => '/icons/countries/tr.png',
            self::ARGENTINA => '/icons/countries/ar.png',
            self::SWEDEN => '/icons/countries/se.png',
            self::BELGIUM => '/icons/countries/be.png',
        };
    }

    public function metaTitle(): string
    {
        return match ($this) {
            self::UKRAINE => 'Фільми з України',
            self::USA => 'Фільми зі США',
            self::JAPAN => 'Фільми з Японії',
            self::CHINA => 'Фільми з Китаю',
            self::FRANCE => 'Фільми з Франції',
            self::INDIA => 'Фільми з Індії',
            self::SPAIN => 'Фільми з Іспанії',
            self::UNITED_KINGDOM => 'Фільми з Великої Британії',
            self::CANADA => 'Фільми з Канади',
            self::GERMANY => 'Фільми з Німеччини',
            self::ITALY => 'Фільми з Італії',
            self::AUSTRALIA => 'Фільми з Австралії',
            self::BRAZIL => 'Фільми з Бразилії',
            self::MEXICO => 'Фільми з Мексики',
            self::SOUTH_KOREA => 'Фільми з Південної Кореї',
            self::RUSSIA => 'Фільми з Росії',
            self::TURKEY => 'Фільми з Туреччини',
            self::ARGENTINA => 'Фільми з Аргентини',
            self::SWEDEN => 'Фільми зі Швеції',
            self::BELGIUM => 'Фільми з Бельгії',
        };
    }
}

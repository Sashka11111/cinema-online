<?php

namespace Liamtseva\Cinema\Enums;

enum UserListType: string
{
    case FAVORITE = 'Улюблене';
    case NOT_WATCHING = 'Не дивлюся';
    case WATCHING = 'Дивлюся';
    case PLANNED = 'В планах';
    case STOPPED = 'Перестав';
    case REWATCHING = 'Передивляюсь';
    case WATCHED = 'Переглянуто';
}

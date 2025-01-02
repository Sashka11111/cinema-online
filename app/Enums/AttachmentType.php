<?php

namespace Liamtseva\Cinema\Enums;

enum AttachmentType: string
{
    case PICTURE = 'picture'; // Зображення
    case TRAILER = 'trailer'; // Трейлер
    case TEASER = 'teaser'; // Тизер
    case CLIP = 'clip'; // Кліп
    case BEHIND_THE_SCENES = 'behind_the_scenes'; // За лаштунками
    case BAD_TAKES = 'bad_takes'; // Невдалі дублі
    case SHORT_FILMS = 'short_films'; // Короткометражні фільми
}

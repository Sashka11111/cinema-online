<?php

namespace Liamtseva\Cinema\Enums;

enum CommentReportType: string
{
    case INSULT = 'осквернення користувачів';
    case FLOOD_OFFTOP_MEANINGLESS = 'флуд / оффтоп / коментар без змісту';
    case AD_SPAM = 'реклама / спам';
    case SPOILER = 'спойлер';
    case PROVOCATION_CONFLICT = 'провокації / конфлікти';
    case INAPPROPRIATE_LANGUAGE = 'ненормативна лексика';
    case FORBIDDEN_UNNECESSARY_CONTENT = 'заборонений / непотрібний контент';
    case MEANINGLESS_EMPTY_TOPIC = 'безглузда / порожня тема';
    case DUPLICATE_TOPIC = 'дублікат теми';
}

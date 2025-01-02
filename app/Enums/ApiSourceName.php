<?php

namespace Liamtseva\Cinema\Enums;

enum ApiSourceName: string
{
    case TMDB = 'tmdb';
    case SHIKI = 'shiki';
    case IMDB = 'imdb';
    case ANILIST = 'anilist';
}

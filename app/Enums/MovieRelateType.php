<?php

namespace Liamtseva\Cinema\Enums;

enum MovieRelateType: string
{
    case SEASON = 'season';                // Сезон
    case SOURCE = 'source';                // Джерело
    case SEQUEL = 'sequel';                // Продовження
    case SIDE_STORY = 'side_story';        // Побічна історія
    case SUMMARY = 'summary';              // Резюме
    case OTHER = 'other';                  // Інше
    case ADAPTATION = 'adaptation';        // Адаптація
    case ALTERNATIVE = 'alternative';      // Альтернатива
    case PREQUEL = 'prequel';              // Передісторія
}

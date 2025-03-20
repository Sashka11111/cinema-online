<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum CommentReportType: string implements HasColor, HasIcon, HasLabel
{
    case INSULT = 'insult';
    case FLOOD_OFFTOP_MEANINGLESS = 'flood_offtop_meaningless';
    case AD_SPAM = 'ad_spam';
    case SPOILER = 'spoiler';
    case PROVOCATION_CONFLICT = 'provocation_conflict';
    case INAPPROPRIATE_LANGUAGE = 'inappropriate_language';
    case FORBIDDEN_UNNECESSARY_CONTENT = 'forbidden_unnecessary_content';
    case MEANINGLESS_EMPTY_TOPIC = 'meaningless_empty_topic';
    case DUPLICATE_TOPIC = 'duplicate_topic';

    /**
     * Повертає перекладену назву типу звіту для Filament із файлу локалізації.
     */
    public function getLabel(): ?string
    {
        return __('comment_report.'.$this->value);
    }

    /**
     * Повертає колір для відображення у Filament.
     */
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::INSULT => 'danger',               // Червоний для образ
            self::FLOOD_OFFTOP_MEANINGLESS => 'gray', // Сірий для флуду/оффтопу
            self::AD_SPAM => 'warning',             // Жовтий для реклами/спаму
            self::SPOILER => 'info',                // Блакитний для спойлерів
            self::PROVOCATION_CONFLICT => 'danger',  // Червоний для провокацій
            self::INAPPROPRIATE_LANGUAGE => 'warning', // Жовтий для ненормативної лексики
            self::FORBIDDEN_UNNECESSARY_CONTENT => 'danger', // Червоний для забороненого контенту
            self::MEANINGLESS_EMPTY_TOPIC => 'gray',  // Сірий для беззмістовних тем
            self::DUPLICATE_TOPIC => 'primary',       // Фіолетовий для дублікатів
        };
    }

    /**
     * Повертає іконку для Filament.
     */
    public function getIcon(): ?string
    {
        return match ($this) {
            self::INSULT => 'heroicon-o-face-frown',
            self::FLOOD_OFFTOP_MEANINGLESS => 'heroicon-o-chat-bubble-oval-left-ellipsis',
            self::AD_SPAM => 'heroicon-o-megaphone',
            self::SPOILER => 'heroicon-o-eye-slash',
            self::PROVOCATION_CONFLICT => 'heroicon-o-fire',
            self::INAPPROPRIATE_LANGUAGE => 'heroicon-o-no-symbol',
            self::FORBIDDEN_UNNECESSARY_CONTENT => 'heroicon-o-shield-exclamation',
            self::MEANINGLESS_EMPTY_TOPIC => 'heroicon-o-document-text',
            self::DUPLICATE_TOPIC => 'heroicon-o-document-duplicate',
        };
    }
}

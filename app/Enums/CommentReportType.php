<?php

namespace Liamtseva\Cinema\Enums;

enum CommentReportType: string
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

    public static function getLabels(): array
    {
        return [
            self::INSULT->value => __('comment_report.insult'),
            self::FLOOD_OFFTOP_MEANINGLESS->value => __('comment_report.flood_offtop_meaningless'),
            self::AD_SPAM->value => __('comment_report.ad_spam'),
            self::SPOILER->value => __('comment_report.spoiler'),
            self::PROVOCATION_CONFLICT->value => __('comment_report.provocation_conflict'),
            self::INAPPROPRIATE_LANGUAGE->value => __('comment_report.inappropriate_language'),
            self::FORBIDDEN_UNNECESSARY_CONTENT->value => __('comment_report.forbidden_unnecessary_content'),
            self::MEANINGLESS_EMPTY_TOPIC->value => __('comment_report.meaningless_empty_topic'),
            self::DUPLICATE_TOPIC->value => __('comment_report.duplicate_topic'),
        ];
    }
}

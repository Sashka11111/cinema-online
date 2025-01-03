<?php

namespace Liamtseva\Cinema\Enums;

enum PersonType: string
{
    case ACTOR = 'actor';
    case CHARACTER = 'character';
    case DIRECTOR = 'director';
    case PRODUCER = 'producer';
    case WRITER = 'writer';
    case EDITOR = 'editor';
    case CINEMATOGRAPHER = 'cinematographer';
    case COMPOSER = 'composer';
    case ART_DIRECTOR = 'art_director';
    case SOUND_DESIGNER = 'sound_designer';
    case COSTUME_DESIGNER = 'costume_designer';
    case MAKEUP_ARTIST = 'makeup_artist';
    case VOICE_ACTOR = 'voice_actor';
    case STUNT_PERFORMER = 'stunt_performer';
    case ASSISTANT_DIRECTOR = 'assistant_director';
    case PRODUCER_ASSISTANT = 'producer_assistant';
    case SCRIPT_SUPERVISOR = 'script_supervisor';
    case PRODUCTION_DESIGNER = 'production_designer';
    case VISUAL_EFFECTS_SUPERVISOR = 'visual_effects_supervisor';

    public function name(): string
    {
        return match ($this) {
            self::ACTOR => 'Актор',
            self::CHARACTER => 'Персонаж',
            self::DIRECTOR => 'Режисер',
            self::PRODUCER => 'Продюсер',
            self::WRITER => 'Сценарист',
            self::EDITOR => 'Монтажер',
            self::CINEMATOGRAPHER => 'Оператор',
            self::COMPOSER => 'Композитор',
            self::ART_DIRECTOR => 'Художник-постановник',
            self::SOUND_DESIGNER => 'Звуковий дизайнер',
            self::COSTUME_DESIGNER => 'Художник з костюмів',
            self::MAKEUP_ARTIST => 'Візажист',
            self::VOICE_ACTOR => 'Актор дубляжу',
            self::STUNT_PERFORMER => 'Каскадер',
            self::ASSISTANT_DIRECTOR => 'Помічник режисера',
            self::PRODUCER_ASSISTANT => 'Помічник продюсера',
            self::SCRIPT_SUPERVISOR => 'Супервайзер сценарію',
            self::PRODUCTION_DESIGNER => 'Продакшн-дизайнер',
            self::VISUAL_EFFECTS_SUPERVISOR => 'Супервайзер візуальних ефектів',
        };
    }
}

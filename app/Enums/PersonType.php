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

    public static function getLabels(): array
    {
        return [
            self::ACTOR->value => __('person_type.actor'),
            self::CHARACTER->value => __('person_type.character'),
            self::DIRECTOR->value => __('person_type.director'),
            self::PRODUCER->value => __('person_type.producer'),
            self::WRITER->value => __('person_type.writer'),
            self::EDITOR->value => __('person_type.editor'),
            self::CINEMATOGRAPHER->value => __('person_type.cinematographer'),
            self::COMPOSER->value => __('person_type.composer'),
            self::ART_DIRECTOR->value => __('person_type.art_director'),
            self::SOUND_DESIGNER->value => __('person_type.sound_designer'),
            self::COSTUME_DESIGNER->value => __('person_type.costume_designer'),
            self::MAKEUP_ARTIST->value => __('person_type.makeup_artist'),
            self::VOICE_ACTOR->value => __('person_type.voice_actor'),
            self::STUNT_PERFORMER->value => __('person_type.stunt_performer'),
            self::ASSISTANT_DIRECTOR->value => __('person_type.assistant_director'),
            self::PRODUCER_ASSISTANT->value => __('person_type.producer_assistant'),
            self::SCRIPT_SUPERVISOR->value => __('person_type.script_supervisor'),
            self::PRODUCTION_DESIGNER->value => __('person_type.production_designer'),
            self::VISUAL_EFFECTS_SUPERVISOR->value => __('person_type.visual_effects_supervisor'),
        ];
    }
}

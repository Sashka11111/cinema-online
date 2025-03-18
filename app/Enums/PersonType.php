<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum PersonType: string implements HasColor, HasLabel
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

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACTOR => __('person_type.actor'),
            self::CHARACTER => __('person_type.character'),
            self::DIRECTOR => __('person_type.director'),
            self::PRODUCER => __('person_type.producer'),
            self::WRITER => __('person_type.writer'),
            self::EDITOR => __('person_type.editor'),
            self::CINEMATOGRAPHER => __('person_type.cinematographer'),
            self::COMPOSER => __('person_type.composer'),
            self::ART_DIRECTOR => __('person_type.art_director'),
            self::SOUND_DESIGNER => __('person_type.sound_designer'),
            self::COSTUME_DESIGNER => __('person_type.costume_designer'),
            self::MAKEUP_ARTIST => __('person_type.makeup_artist'),
            self::VOICE_ACTOR => __('person_type.voice_actor'),
            self::STUNT_PERFORMER => __('person_type.stunt_performer'),
            self::ASSISTANT_DIRECTOR => __('person_type.assistant_director'),
            self::PRODUCER_ASSISTANT => __('person_type.producer_assistant'),
            self::SCRIPT_SUPERVISOR => __('person_type.script_supervisor'),
            self::PRODUCTION_DESIGNER => __('person_type.production_designer'),
            self::VISUAL_EFFECTS_SUPERVISOR => __('person_type.visual_effects_supervisor'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            PersonType::ACTOR => 'success',
            PersonType::DIRECTOR => 'info',
            PersonType::WRITER => 'warning',
            default => 'primary',
        };
    }
}

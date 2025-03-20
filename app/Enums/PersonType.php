<?php

namespace Liamtseva\Cinema\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum PersonType: string implements HasColor, HasIcon, HasLabel
{
    // Визначення типів персон
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

    // Локалізовані мітки
    public function getLabel(): ?string
    {
        return __('person_type.'.$this->value);
    }

    // Кольори для відображення у Filament
    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ACTOR => 'success',
            self::DIRECTOR => 'info',
            self::WRITER => 'warning',
            self::PRODUCER => 'primary',
            self::CHARACTER => 'gray',
            default => 'primary',
        };
    }

    // Іконки для відображення у Filament
    public function getIcon(): ?string
    {
        return match ($this) {
            self::ACTOR => 'heroicon-o-user',
            self::CHARACTER => 'heroicon-o-face-smile',
            self::DIRECTOR => 'heroicon-o-video-camera',
            self::PRODUCER => 'heroicon-o-banknotes',
            self::WRITER => 'heroicon-o-pencil',
            self::EDITOR => 'heroicon-o-scissors',
            self::CINEMATOGRAPHER => 'heroicon-o-camera',
            self::COMPOSER => 'heroicon-o-musical-note',
            self::ART_DIRECTOR => 'heroicon-o-paint-brush',
            self::SOUND_DESIGNER => 'heroicon-o-speaker-wave',
            self::COSTUME_DESIGNER => 'heroicon-o-paint-brush',
            self::MAKEUP_ARTIST => 'heroicon-o-sparkles',
            self::VOICE_ACTOR => 'heroicon-o-microphone',
            self::STUNT_PERFORMER => 'heroicon-o-fire',
            self::ASSISTANT_DIRECTOR => 'heroicon-o-clipboard',
            self::PRODUCER_ASSISTANT => 'heroicon-o-briefcase',
            self::SCRIPT_SUPERVISOR => 'heroicon-o-document-text',
            self::PRODUCTION_DESIGNER => 'heroicon-o-building-office',
            self::VISUAL_EFFECTS_SUPERVISOR => 'heroicon-o-cpu-chip',
        };
    }
}

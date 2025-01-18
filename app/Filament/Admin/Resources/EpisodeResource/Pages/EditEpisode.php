<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEpisode extends EditRecord
{
    protected static string $resource = EpisodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

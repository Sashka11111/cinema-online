<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource;

class EditEpisode extends EditRecord
{
    protected static string $resource = EpisodeResource::class;

    protected static ?string $title = 'Редагувати епізод';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

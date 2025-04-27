<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource;

class CreateEpisode extends CreateRecord
{
    protected static string $resource = EpisodeResource::class;

    protected static ?string $title = 'Створити епізод';
}

<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource;

class CreateMovie extends CreateRecord
{
    protected static string $resource = MovieResource::class;

    protected static ?string $title = 'Створити фільм';
}

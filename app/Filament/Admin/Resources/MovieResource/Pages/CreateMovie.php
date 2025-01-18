<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMovie extends CreateRecord
{
    protected static string $resource = MovieResource::class;
}

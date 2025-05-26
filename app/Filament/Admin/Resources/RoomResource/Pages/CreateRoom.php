<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\RoomResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\RoomResource;

class CreateRoom extends CreateRecord
{
    protected static string $resource = RoomResource::class;

    protected static ?string $title = 'Створити кімнату';
}

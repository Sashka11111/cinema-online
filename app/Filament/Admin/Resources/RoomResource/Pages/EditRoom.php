<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\RoomResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\RoomResource;

class EditRoom extends EditRecord
{
    protected static string $resource = RoomResource::class;

    protected static ?string $title = 'Редагувати кімнату';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

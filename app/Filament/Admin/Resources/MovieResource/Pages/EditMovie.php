<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource;

class EditMovie extends EditRecord
{
    protected static string $resource = MovieResource::class;

    protected static ?string $title = 'Редагувати фільм';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

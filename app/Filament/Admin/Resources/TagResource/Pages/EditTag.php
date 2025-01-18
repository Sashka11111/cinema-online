<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\TagResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTag extends EditRecord
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

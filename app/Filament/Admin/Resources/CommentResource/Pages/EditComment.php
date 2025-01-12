<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComment extends EditRecord
{
    protected static string $resource = CommentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

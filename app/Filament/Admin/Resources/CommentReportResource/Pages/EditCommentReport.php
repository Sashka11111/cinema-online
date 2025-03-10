<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Pages;

use Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommentReport extends EditRecord
{
    protected static string $resource = CommentReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

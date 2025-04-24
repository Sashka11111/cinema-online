<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource;

class EditCommentReport extends EditRecord
{
    protected static string $resource = CommentReportResource::class;

    protected static ?string $title = 'Редагувати скаргу на коментар';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

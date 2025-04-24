<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentLikeResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentLikeResource;

class EditCommentLike extends EditRecord
{
    protected static string $resource = CommentLikeResource::class;

    protected static ?string $title = 'Редагувати реакцію на коментар';

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}

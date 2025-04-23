<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentLikeResource\Pages;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentLikeResource;
use Liamtseva\Cinema\Models\CommentLike;

class CreateCommentLike extends CreateRecord
{
    protected static string $resource = CommentLikeResource::class;

    protected static ?string $title = 'Залишити реакцію на коментар';

    protected function beforeCreate(): void
    {
        $data = $this->form->getState();

        $existingLike = CommentLike::where('user_id', $data['user_id'])
            ->where('comment_id', $data['comment_id'])
            ->first();

        if ($existingLike) {
            Notification::make()
                ->title('Помилка')
                ->body('Цей користувач вже залишив реакцію на даний коментар')
                ->danger()
                ->send();

            $this->halt();
        }
    }
}

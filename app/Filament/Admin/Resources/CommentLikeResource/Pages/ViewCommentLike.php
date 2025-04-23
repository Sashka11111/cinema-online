<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentLikeResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentLikeResource;

class ViewCommentLike extends ViewRecord
{
    protected static string $resource = CommentLikeResource::class;

    protected static ?string $title = 'Перегляд реакції на коментар';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Основна інформація')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Користувач')
                            ->icon('heroicon-o-user'),

                        IconEntry::make('is_liked')
                            ->label('Тип реакції')
                            ->boolean()
                            ->trueIcon('heroicon-o-hand-thumb-up')
                            ->falseIcon('heroicon-o-hand-thumb-down')
                            ->trueColor('success')
                            ->falseColor('danger'),

                        TextEntry::make('created_at')
                            ->label('Дата створення')
                            ->dateTime('d.m.Y H:i')
                            ->icon('heroicon-o-calendar'),

                        TextEntry::make('updated_at')
                            ->label('Дата оновлення')
                            ->dateTime('d.m.Y H:i')
                            ->icon('heroicon-o-clock'),
                    ])
                    ->columns(2),

                Section::make('Інформація про коментар')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->schema([
                        TextEntry::make('comment.body')
                            ->label('Текст коментаря')
                            ->html()
                            ->columnSpanFull(),

                        TextEntry::make('comment.user.name')
                            ->label('Автор коментаря')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('comment.commentable_type')
                            ->label('Тип контенту')
                            ->formatStateUsing(fn ($record) => $record->comment?->translated_type ?? 'Немає даних'),

                        TextEntry::make('comment.commentable.name')
                            ->label('Контент')
                            ->formatStateUsing(fn ($record) => $record->comment?->commentable?->name ?? 'Немає даних'),

                        IconEntry::make('comment.is_spoiler')
                            ->label('Спойлер')
                            ->boolean()
                            ->trueIcon('heroicon-o-eye-slash')
                            ->falseIcon('heroicon-o-eye')
                            ->visible(fn ($record) => $record->comment !== null),
                    ])
                    ->columns(2),
            ]);
    }
}

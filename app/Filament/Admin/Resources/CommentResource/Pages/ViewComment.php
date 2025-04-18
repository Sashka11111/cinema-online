<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource;

class ViewComment extends ViewRecord
{
    protected static ?string $title = 'Перегляд коментаря';

    protected static string $resource = CommentResource::class;

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
                            ->label('Автор')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('commentable_type')
                            ->label('Тип контенту')
                            ->formatStateUsing(fn ($record) => $record->translated_type),

                        TextEntry::make('commentable.name')
                            ->label('Контент')
                            ->formatStateUsing(fn ($record) => $record->commentable?->name ?? 'Немає даних'),

                        IconEntry::make('is_spoiler')
                            ->label('Спойлер')
                            ->boolean()
                            ->trueIcon('heroicon-o-eye-slash')
                            ->falseIcon('heroicon-o-eye'),

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

                Section::make('Текст коментаря')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->schema([
                        TextEntry::make('body')
                            ->label('Текст')
                            ->html()
                            ->columnSpanFull(),
                    ]),

                Section::make('Батьківський коментар')
                    ->icon('heroicon-o-arrow-up-on-square')
                    ->schema([
                        TextEntry::make('parent.body')
                            ->label('Текст батьківського коментаря')
                            ->html()
                            ->columnSpanFull()
                            ->visible(fn ($record) => $record->parent_id !== null),

                        TextEntry::make('parent.user.name')
                            ->label('Автор батьківського коментаря')
                            ->icon('heroicon-o-user')
                            ->visible(fn ($record) => $record->parent_id !== null),
                    ])
                    ->visible(fn ($record) => $record->parent_id !== null),

                Section::make('Статистика')
                    ->icon('heroicon-o-chart-bar')
                    ->schema([
                        TextEntry::make('likes_count')
                            ->label('Кількість лайків')
                            ->getStateUsing(fn ($record) => $record->likes()->count())
                            ->icon('heroicon-o-heart'),

                        TextEntry::make('reports_count')
                            ->label('Кількість скарг')
                            ->getStateUsing(fn ($record) => $record->reports()->count())
                            ->icon('heroicon-o-flag'),
                    ])
                    ->columns(2),
            ]);
    }
}

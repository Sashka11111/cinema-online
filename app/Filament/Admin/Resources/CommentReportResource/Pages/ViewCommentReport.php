<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource;

class ViewCommentReport extends ViewRecord
{
    protected static string $resource = CommentReportResource::class;

    protected static ?string $title = 'Перегляд скарги';

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
                Section::make('Інформація про скаргу')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextEntry::make('comment.body')
                            ->label('Коментар')
                            ->markdown()
                            ->columnSpanFull(),

                        TextEntry::make('user.name')
                            ->label('Користувач')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('type')
                            ->label('Тип скарги')
                            ->badge(),

                        IconEntry::make('is_viewed')
                            ->label('Переглянуто')
                            ->boolean(),

                        TextEntry::make('created_at')
                            ->label('Створено')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('updated_at')
                            ->label('Оновлено')
                            ->dateTime('d.m.Y H:i'),

                        TextEntry::make('body')
                            ->label('Текст скарги')
                            ->markdown()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}

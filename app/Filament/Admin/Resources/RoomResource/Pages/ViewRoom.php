<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\RoomResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\RoomResource;

class ViewRoom extends ViewRecord
{
    protected static string $resource = RoomResource::class;

    protected static ?string $title = 'Перегляд кімнати';

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
                        TextEntry::make('name')
                            ->label('Назва')
                            ->icon('clarity-text-line'),

                        TextEntry::make('slug')
                            ->label('Slug')
                            ->icon('heroicon-o-link'),

                        TextEntry::make('user.name')
                            ->label('Власник')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('episode.name')
                            ->label('Епізод')
                            ->icon('heroicon-o-film'),

                        IconEntry::make('is_private')
                            ->label('Приватна кімната')
                            ->boolean()
                            ->trueIcon('heroicon-o-lock-closed')
                            ->falseIcon('heroicon-o-globe-alt'),

                        TextEntry::make('max_viewers')
                            ->label('Максимальна кількість глядачів')
                            ->icon('heroicon-o-users'),

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
            ]);
    }
}

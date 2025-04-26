<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SearchHistoryResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\SearchHistoryResource;

class ViewSearchHistory extends ViewRecord
{
    protected static string $resource = SearchHistoryResource::class;

    protected static ?string $title = 'Перегляд історії пошуку';

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Інформація про пошук')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Користувач')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('query')
                            ->label('Пошуковий запит')
                            ->icon('heroicon-o-magnifying-glass'),

                        TextEntry::make('created_at')
                            ->label('Дата пошуку')
                            ->dateTime('d.m.Y H:i')
                            ->icon('heroicon-o-calendar'),
                    ])
                    ->columns(2),
            ]);
    }
}
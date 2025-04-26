<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\WatchHistoryResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\WatchHistoryResource;

class ViewWatchHistory extends ViewRecord
{
    protected static string $resource = WatchHistoryResource::class;

    protected static ?string $title = 'Перегляд історії перегляду';

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
                Section::make('Інформація про перегляд')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Користувач')
                            ->icon('heroicon-o-user'),

                        TextEntry::make('episode.name')
                            ->label('Епізод')
                            ->icon('heroicon-o-film'),

                        TextEntry::make('progress_time')
                            ->label('Час перегляду')
                            ->formatStateUsing(fn (int $state) => gmdate('H:i:s', $state))
                            ->icon('heroicon-o-clock'),

                        TextEntry::make('created_at')
                            ->label('Дата початку перегляду')
                            ->dateTime('d.m.Y H:i')
                            ->icon('heroicon-o-calendar'),

                        TextEntry::make('updated_at')
                            ->label('Дата останнього оновлення')
                            ->dateTime('d.m.Y H:i')
                            ->icon('heroicon-o-arrow-path'),
                    ])
                    ->columns(2),
            ]);
    }
}
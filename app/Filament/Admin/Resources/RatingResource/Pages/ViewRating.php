<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\RatingResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\RatingResource;

class ViewRating extends ViewRecord
{
    protected static string $resource = RatingResource::class;

    protected static ?string $title = 'Перегляд рейтингу';

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

                        TextEntry::make('movie.name')
                            ->label('Фільм')
                            ->icon('heroicon-o-film'),

                        TextEntry::make('review')
                            ->label('Відгук')
                            ->html(),

                        TextEntry::make('number')
                            ->label('Оцінка')
                            ->icon('heroicon-o-star')
                            ->badge()
                            ->color(fn ($record): string => match (true) {
                                $record->number >= 8 => 'success',
                                $record->number >= 5 => 'warning',
                                default => 'danger',
                            }),

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

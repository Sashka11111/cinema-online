<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource;

class ViewSelection extends ViewRecord
{
    protected static ?string $title = 'Перегляд підбірки';

    protected static string $resource = SelectionResource::class;

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
                            ->label('Назва'),

                        TextEntry::make('slug')
                            ->label('Slug')
                            ->icon('heroicon-o-link'),

                        TextEntry::make('user.name')
                            ->label('Автор')
                            ->icon('heroicon-o-user'),

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

                Section::make('Опис')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Опис')
                            ->html()
                            ->columnSpanFull(),
                    ]),

                Section::make('SEO налаштування')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        TextEntry::make('meta_title')
                            ->label('Meta назва')
                            ->icon('heroicon-o-tag'),

                        ImageEntry::make('meta_image')
                            ->label('Meta зображення'),

                        TextEntry::make('meta_description')
                            ->label('Meta опис')
                            ->html()
                            ->columnSpanFull(),
                    ])
                    ->collapsed()
                    ->columns(2),
            ]);
    }
}

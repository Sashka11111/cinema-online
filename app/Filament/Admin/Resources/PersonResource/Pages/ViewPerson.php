<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\PersonResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\PersonResource;

class ViewPerson extends ViewRecord
{
    protected static ?string $title = 'Перегляд персони';

    protected static string $resource = PersonResource::class;

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
                            ->label('Ім\'я'),

                        TextEntry::make('original_name')
                            ->label('Оригінальне ім\'я'),

                        TextEntry::make('slug')
                            ->label('Slug')
                            ->icon('heroicon-o-link'),

                        TextEntry::make('type')
                            ->label('Тип')
                            ->badge(),

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

                Section::make('Особисті дані')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextEntry::make('birthday')
                            ->label('Дата народження')
                            ->date('d.m.Y')
                            ->icon('heroicon-o-calendar'),

                        TextEntry::make('birthplace')
                            ->label('Місце народження')
                            ->icon('heroicon-o-map-pin'),
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

                Section::make('Зображення')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        ImageEntry::make('image')
                            ->label('Фото')
                            ->disk('public'),
                    ]),

                Section::make('SEO налаштування')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        TextEntry::make('meta_title')
                            ->label('Meta назва')
                            ->icon('heroicon-o-tag'),

                        ImageEntry::make('meta_image')
                            ->label('Meta зображення')
                            ->disk('public'),

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

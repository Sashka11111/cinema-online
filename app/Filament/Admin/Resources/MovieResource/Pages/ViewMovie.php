<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource;

class ViewMovie extends ViewRecord
{
    protected static ?string $title = 'Перегляд фільму';

    protected static string $resource = MovieResource::class;

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

                        TextEntry::make('kind')
                            ->label('Тип')
                            ->badge(),

                        TextEntry::make('status')
                            ->label('Статус')
                            ->badge(),

                        TextEntry::make('period')
                            ->label('Період')
                            ->badge(),

                        TextEntry::make('source')
                            ->label('Джерело')
                            ->badge(),

                        TextEntry::make('studio.name')
                            ->label('Студія')
                            ->icon('heroicon-o-building-office'),

                        IconEntry::make('is_published')
                            ->label('Опубліковано')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle')
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

                Section::make('Опис')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Опис')
                            ->html()
                            ->columnSpanFull(),
                    ]),

                Section::make('Медіа')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        ImageEntry::make('image_name')
                            ->label('Зображення назви фільму')
                            ->disk('public'),

                        ImageEntry::make('poster')
                            ->label('Постер')
                            ->disk('public'),
                    ])
                    ->columns(2),

                Section::make('Додаткова інформація')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextEntry::make('countries')
                            ->label('Країни')
                            ->formatStateUsing(function ($state) {
                                if (! is_array($state)) {
                                    return $state;
                                }

                                return collect($state)->join(', ');
                            }),

                        TextEntry::make('aliases')
                            ->label('Аліаси')
                            ->bulleted(),

                        TextEntry::make('duration')
                            ->label('Тривалість')
                            ->suffix(' хв.'),

                        TextEntry::make('episodes_count')
                            ->label('Кількість епізодів'),

                        TextEntry::make('first_air_date')
                            ->label('Дата першого показу')
                            ->date(),

                        TextEntry::make('last_air_date')
                            ->label('Дата останнього показу')
                            ->date(),

                        TextEntry::make('imdb_score')
                            ->label('IMDb рейтинг')
                            ->numeric(),

                        TextEntry::make('restricted_rating')
                            ->label('Віковий рейтинг')
                            ->badge(),
                    ])
                    ->columns(2)
                    ->collapsed(),

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

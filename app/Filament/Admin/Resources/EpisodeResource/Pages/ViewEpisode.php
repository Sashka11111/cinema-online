<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource;

class ViewEpisode extends ViewRecord
{
    protected static string $resource = EpisodeResource::class;

    protected static ?string $title = 'Перегляд епізоду';

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
                        TextEntry::make('movie.name')
                            ->label('Медіа')
                            ->icon('heroicon-o-film'),

                        TextEntry::make('number')
                            ->label('Номер епізоду')
                            ->icon('heroicon-o-hashtag'),

                        TextEntry::make('name')
                            ->label('Назва')
                            ->icon('clarity-text-line'),

                        TextEntry::make('slug')
                            ->label('Slug'),

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

                Section::make('Деталі епізоду')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        TextEntry::make('description')
                            ->label('Опис')
                            ->html()
                            ->columnSpanFull(),

                        TextEntry::make('duration')
                            ->label('Тривалість')
                            ->suffix(' хв'),

                        TextEntry::make('air_date')
                            ->label('Дата виходу')
                            ->date('d.m.Y'),

                        IconEntry::make('is_filler')
                            ->label('Філер')
                            ->boolean()
                            ->trueIcon('heroicon-o-check-circle')
                            ->falseIcon('heroicon-o-x-circle'),
                    ])
                    ->columns(2),

                Section::make('Медіа')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        RepeatableEntry::make('pictures')
                            ->label('Зображення')
                            ->schema([
                                TextEntry::make('url')
                                    ->label('URL зображення'),
                            ]),

                        RepeatableEntry::make('video_players')
                            ->label('Відеоплеєри')
                            ->schema([
                                TextEntry::make('name')
                                    ->label('Назва'),
                                TextEntry::make('url')
                                    ->label('URL плеєра'),
                                TextEntry::make('file_url')
                                    ->label('URL файлу'),
                                TextEntry::make('dubbing')
                                    ->label('Озвучка'),
                                TextEntry::make('quality')
                                    ->label('Якість'),
                                TextEntry::make('locale_code')
                                    ->label('Код локалі'),
                            ])
                            ->columns(3),
                    ]),

                Section::make('SEO налаштування')
                    ->icon('heroicon-o-globe-alt')
                    ->collapsed()
                    ->schema([
                        TextEntry::make('meta_title')
                            ->label('Meta назва'),

                        TextEntry::make('meta_image')
                            ->label('Meta зображення'),

                        TextEntry::make('meta_description')
                            ->label('Meta опис'),
                    ])
                    ->columns(2),
            ]);
    }
}

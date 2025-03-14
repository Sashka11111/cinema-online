<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Filament\Admin\Resources\EpisodeResource\Pages;
use Liamtseva\Cinema\Models\Episode;

class EpisodeResource extends Resource
{
    protected static ?string $model = Episode::class;

    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static ?string $navigationLabel = 'Епізоди';

    protected static ?string $modelLabel = 'епізод';

    protected static ?string $pluralModelLabel = 'Епізоди';

    protected static ?string $navigationGroup = 'Контент';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return (string) Episode::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Основна інформація')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    Select::make('movie_id')
                        ->label('Фільм/Серіал')
                        ->relationship('movie', 'name')
                        ->required()
                        ->prefixIcon('heroicon-o-film')
                        ->searchable(),

                    TextInput::make('number')
                        ->label('Номер епізоду')
                        ->required()
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(65535)
                        ->prefixIcon('heroicon-o-hashtag'),

                    TextInput::make('name')
                        ->label('Назва')
                        ->required()
                        ->maxLength(128)
                        ->prefixIcon('clarity-text-line')
                        ->reactive()
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                            if ($operation == 'edit' || empty($state)) {
                                return;
                            }
                            $set('slug', str($state)->slug().'-'.str(str()->random(6))->lower());
                            $set('meta_title', $state.' | Cinema');
                        }),

                    TextInput::make('slug')
                        ->label('URL-посилання')
                        ->required()
                        ->maxLength(128)
                        ->prefixIcon('heroicon-o-link')
                        ->unique(ignoreRecord: true),
                ])
                ->columns(2),

            Section::make('Деталі епізоду')
                ->icon('heroicon-o-document-text')
                ->schema([
                    Textarea::make('description')
                        ->label('Опис')
                        ->maxLength(512)
                        ->nullable()
                        ->rows(3),

                    TextInput::make('duration')
                        ->label('Тривалість (хв)')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(65535)
                        ->nullable()
                        ->suffix('хв'),

                    DatePicker::make('air_date')
                        ->label('Дата виходу')
                        ->native(false)
                        ->nullable(),

                    Toggle::make('is_filler')
                        ->label('Філер')
                        ->default(false),
                ])
                ->columns(2),

            Section::make('Медіа')
                ->icon('heroicon-o-photo')
                ->schema([
                    TagsInput::make('pictures')
                        ->label('Зображення')
                        ->placeholder('Додайте URL зображень'),

                    TagsInput::make('video_players')
                        ->label('Відеоплеєри')
                        ->placeholder('Додайте URL плеєрів'),
                ])
                ->columns(2),

            Section::make('SEO налаштування')
                ->icon('heroicon-o-globe-alt')
                ->collapsed()
                ->schema([
                    TextInput::make('meta_title')
                        ->label('SEO Заголовок')
                        ->maxLength(128)
                        ->nullable(),

                    Textarea::make('meta_description')
                        ->label('SEO Опис')
                        ->maxLength(376)
                        ->rows(3)
                        ->nullable(),

                    FileUpload::make('meta_image')
                        ->label('SEO Зображення')
                        ->image()
                        ->imagePreviewHeight('100')
                        ->maxSize(2048)
                        ->directory('episode-meta-images')
                        ->nullable(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')
                    ->label('Номер епізоду')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('movie.name')
                    ->label('Назва контенту')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('duration')
                    ->label('Тривалість')
                    ->formatStateUsing(fn ($state) => $state ? "$state хв" : '-')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_filler')
                    ->label('Філер')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('air_date')
                    ->label('Дата виходу')
                    ->date('d-m-Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('movie')
                    ->label('Назва контенту')
                    ->relationship('movie', 'name')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_filler')
                    ->label('Тип епізоду')
                    ->trueLabel('Філерні')
                    ->falseLabel('Не філерні'),

                Filter::make('air_date')
                    ->form([
                        DatePicker::make('air_date_from')
                            ->label('Дата виходу від'),
                        DatePicker::make('air_date_to')
                            ->label('Дата виходу до'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['air_date_from'], fn ($query, $date) => $query->where('air_date', '>=', $date))
                            ->when($data['air_date_to'], fn ($query, $date) => $query->where('air_date', '<=', $date));
                    }),

                Filter::make('duration')
                    ->form([
                        TextInput::make('duration_from')
                            ->label('Тривалість від (хв)')
                            ->numeric()
                            ->minValue(1),
                        TextInput::make('duration_to')
                            ->label('Тривалість до (хв)')
                            ->numeric()
                            ->maxValue(65535),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['duration_from'], fn ($query, $duration) => $query->where('duration', '>=', $duration))
                            ->when($data['duration_to'], fn ($query, $duration) => $query->where('duration', '<=', $duration));
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEpisodes::route('/'),
            'create' => Pages\CreateEpisode::route('/create'),
            'edit' => Pages\EditEpisode::route('/{record}/edit'),
        ];
    }
}

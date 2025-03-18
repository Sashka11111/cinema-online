<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
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
use Liamtseva\Cinema\Enums\VideoPlayerName;
use Liamtseva\Cinema\Enums\VideoQuality;
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
                        ->label('Медіа')
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

                    DateTimePicker::make('created_at')
                        ->label('Дата створення')
                        ->prefixIcon('heroicon-o-calendar')
                        ->displayFormat('d.m.Y H:i')
                        ->disabled()
                        ->default(now())
                        ->hiddenOn('create'),

                    DateTimePicker::make('updated_at')
                        ->label('Дата оновлення')
                        ->prefixIcon('heroicon-o-clock')
                        ->displayFormat('d.m.Y H:i')
                        ->disabled()
                        ->default(now())
                        ->hiddenOn('create'),
                ])
                ->columns(2),

            Section::make('Деталі епізоду')
                ->icon('heroicon-o-document-text')
                ->schema([
                    RichEditor::make('description')
                        ->label('Опис')
                        ->required()
                        ->maxLength(512)
                        ->columnSpanFull()
                        ->disableToolbarButtons(['attachFiles'])
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                            if ($operation == 'edit' || empty($state)) {
                                return;
                            }
                            $plainText = strip_tags($state);
                            $set('meta_description', Episode::makeMetaDescription($plainText));
                        }),

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
                    Repeater::make('pictures')
                        ->label('Зображення')
                        ->schema([
                            TextInput::make('name')
                                ->label('Назва')
                                ->maxLength(128)
                                ->nullable(),
                            TextInput::make('url')
                                ->label('URL зображення')
                                ->required()
                                ->url(),
                            TextInput::make('file_url')
                                ->label('URL файлу')
                                ->url()
                                ->nullable(),
                            Select::make('quality')
                                ->label('Якість')
                                ->options(VideoQuality::getLabels())
                                ->nullable(),
                            TextInput::make('locale_code')
                                ->label('Код локалі')
                                ->maxLength(2)
                                ->nullable(),
                        ])
                        ->columns(3),

                    Repeater::make('video_players')
                        ->label('Відеоплеєри')
                        ->schema([
                            Select::make('name')
                                ->label('Назва')
                                ->options(VideoPlayerName::getLabels())
                                ->required(),
                            TextInput::make('url')
                                ->label('URL плеєра')
                                ->required()
                                ->url(),
                            TextInput::make('file_url')
                                ->label('URL файлу')
                                ->url()
                                ->nullable(),
                            Select::make('quality')
                                ->label('Якість')
                                ->options(VideoQuality::getLabels())
                                ->nullable(),
                            TextInput::make('locale_code')
                                ->label('Код локалі')
                                ->maxLength(2)
                                ->nullable(),
                        ])
                        ->columns(3),
                ])
                ->columns(2),

            Section::make('SEO налаштування')
                ->icon('heroicon-o-globe-alt')
                ->collapsed()
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta назва')
                        ->maxLength(128)
                        ->nullable(),

                    FileUpload::make('meta_image')
                        ->label('Meta зображення')
                        ->image()
                        ->maxSize(2048)
                        ->directory('people/meta')
                        ->nullable(),

                    Textarea::make('meta_description')
                        ->label('Meta опис')
                        ->maxLength(376)
                        ->rows(3)
                        ->nullable(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

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

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->tooltip(fn (Episode $record): ?string => $record->description)
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('duration')
                    ->label('Тривалість')
                    ->formatStateUsing(fn ($state) => $state ? "$state хв" : '-')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('pictures')
                    ->label('Зображення')
                    ->formatStateUsing(fn ($state) => collect($state)->pluck('url')->implode(', '))
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('video_players')
                    ->label('Відеоплеєри')
                    ->formatStateUsing(fn ($state) => collect($state)->pluck('url')->implode(', '))
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),

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

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Дата оновлення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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

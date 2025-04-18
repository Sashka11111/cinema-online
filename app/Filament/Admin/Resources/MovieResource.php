<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Enums\Period;
use Liamtseva\Cinema\Enums\RestrictedRating;
use Liamtseva\Cinema\Enums\Source;
use Liamtseva\Cinema\Enums\Status;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Pages;
use Liamtseva\Cinema\Models\Movie;

class MovieResource extends Resource
{
    protected static ?string $model = Movie::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationLabel = 'Медіа';

    protected static ?string $modelLabel = 'медіа';

    protected static ?string $pluralModelLabel = 'Медіа';

    protected static ?string $navigationGroup = 'Контент';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'name',
            'slug',
            'description',
            'aliases',
            'countries',
            'studio.name',
        ];
    }

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Студія' => $record->studio?->name,
            'Тип' => $record->kind,
            'Статус' => $record->status,
        ];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return static::getUrl('edit', ['record' => $record]);
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Movie::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Основна інформація')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    TextInput::make('name')
                        ->label('Назва')
                        ->required()
                        ->maxLength(248)
                        ->prefixIcon('clarity-text-line')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                            if ($operation == 'edit' || empty($state)) {
                                return;
                            }
                            $set('slug', str($state)->slug().'-'.str(str()->random(6))->lower());
                            $set('meta_title', $state.' | Cinema');
                        }),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(128)
                        ->unique(Movie::class, 'slug', ignoreRecord: true)
                        ->helperText('Автоматично генерується з імені')
                        ->prefixIcon('heroicon-o-link'),

                    Select::make('studio_id')
                        ->label('Студія')
                        ->relationship('studio', 'name')
                        ->required()
                        ->preload()
                        ->prefixIcon('heroicon-o-building-office')
                        ->searchable(),

                    Select::make('kind')
                        ->label('Тип')
                        ->options(Kind::class)
                        ->required()
                        ->prefixIcon('heroicon-o-video-camera'),
                    Select::make('status')
                        ->label('Статус')
                        ->options(Status::class)
                        ->required()
                        ->prefixIcon('clarity-shield-check-line'),

                    Select::make('period')
                        ->label('Період')
                        ->options(Period::class)
                        ->nullable()
                        ->prefixIcon('heroicon-o-calendar-days'),

                    Select::make('restricted_rating')
                        ->label('Вікове обмеження')
                        ->options(RestrictedRating::class)
                        ->required()
                        ->prefixIcon('heroicon-o-shield-exclamation'),

                    Select::make('source')
                        ->label('Джерело')
                        ->options(Source::class)
                        ->required()
                        ->prefixIcon('heroicon-o-cloud'),
                ])
                ->columns(2),

            Section::make('Додаткові відомості')
                ->icon('heroicon-o-document-text')
                ->schema([
                    RichEditor::make('description')
                        ->required()
                        ->columnSpanFull()
                        ->label('Опис')
                        ->disableToolbarButtons(['attachFiles'])
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                            if ($operation == 'edit' || empty($state)) {
                                return;
                            }
                            $plainText = strip_tags($state);
                            $set('meta_description', Movie::makeMetaDescription($plainText));
                        }),

                    TextInput::make('duration')
                        ->label('Тривалість (хв)')
                        ->numeric()
                        ->minValue(1)
                        ->nullable()
                        ->suffix('хв'),

                    TextInput::make('episodes_count')
                        ->label('Кількість епізодів')
                        ->numeric()
                        ->minValue(1)
                        ->nullable(),

                    DatePicker::make('first_air_date')
                        ->label('Дата початку ефіру')
                        ->native(false)
                        ->nullable(),

                    DatePicker::make('last_air_date')
                        ->label('Дата завершення ефіру')
                        ->native(false)
                        ->nullable(),

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

                    TextInput::make('imdb_score')
                        ->label('Оцінка IMDb')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(10)
                        ->step(0.1)
                        ->nullable(),

                    Toggle::make('is_published')
                        ->label('Опубліковано')
                        ->default(false),
                ])
                ->columns(2),

            Section::make('Медіа')
                ->icon('heroicon-o-photo')
                ->schema([
                    FileUpload::make('image_name')
                        ->label('Головне зображення')
                        ->image()
                        ->maxSize(2048)
                        ->directory('movie-images')
                        ->required(),

                    FileUpload::make('poster')
                        ->label('Постер')
                        ->image()
                        ->maxSize(2048)
                        ->directory('movie-posters')
                        ->nullable(),

                    TagsInput::make('aliases')
                        ->label('Аліаси')
                        ->placeholder('Додайте аліаси'),

                    TagsInput::make('countries')
                        ->label('Країни')
                        ->placeholder('Додайте країни'),
                ])
                ->columns(2),

            Section::make('Пов’язаний контент')
                ->icon('heroicon-o-link')
                ->schema([
                    Repeater::make('api_sources')
                        ->label('Джерела API')
                        ->schema([
                            TextInput::make('name')
                                ->label('Назва джерела')
                                ->required()
                                ->maxLength(255),
                            TextInput::make('id')
                                ->label('ID')
                                ->required()
                                ->maxLength(255),
                        ])
                        ->defaultItems(0)
                        ->collapsible()
                        ->columns(2)
                        ->columnSpanFull(),

                    TagsInput::make('attachments')
                        ->label('Прикріплення')
                        ->placeholder('Додайте прикріплення'),

                    Repeater::make('related')
                        ->label('Пов’язані')
                        ->schema([
                            TextInput::make('title')
                                ->label('Назва')
                                ->required()
                                ->maxLength(255),
                            Select::make('movie_id')
                                ->label('Медіа')
                                ->relationship('relatedMovies', 'name')
                                ->searchable()
                                ->preload()
                                ->nullable(),
                        ])
                        ->defaultItems(0)
                        ->collapsible()
                        ->columns(2)
                        ->columnSpanFull(),

                    TagsInput::make('similars')
                        ->label('Схожі')
                        ->placeholder('Додайте схожі'),
                ])
                ->columns(2),

            Section::make('SEO налаштування')
                ->icon('heroicon-o-globe-alt')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta назва')
                        ->maxLength(128)
                        ->nullable()
                        ->prefixIcon('heroicon-o-tag'),

                    FileUpload::make('meta_image')
                        ->label('Meta зображення')
                        ->image()
                        ->imagePreviewHeight('100')
                        ->maxSize(2048)
                        ->directory('studios/meta')
                        ->nullable(),

                    RichEditor::make('meta_description')
                        ->label('Meta опис')
                        ->maxLength(500)
                        ->columnSpanFull(),
                ])
                ->collapsed()
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

                ImageColumn::make('image_name')
                    ->label('Зображення')
                    ->disk('public')
                    ->width(50)
                    ->height(50)
                    ->circular()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Назва')
                    ->description(fn (Movie $movie): string => $movie->slug)
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('studio.name')
                    ->label('Студія')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->color('gray'),

                TextColumn::make('countries')
                    ->label('Країни')
                    ->formatStateUsing(fn ($state) => $state ? implode(', ', json_decode($state, true) ?: [$state]) : '—')
                    ->toggleable(),

                ImageColumn::make('poster')
                    ->label('Постер')
                    ->disk('public')
                    ->width(50)
                    ->height(50)
                    ->circular()
                    ->toggleable(),

                TextColumn::make('duration')
                    ->label('Тривалість')
                    ->formatStateUsing(fn ($state) => $state ? "$state хв" : '—')
                    ->sortable()
                    ->toggleable()
                    ->alignCenter(),

                TextColumn::make('episodes_count')
                    ->label('Епізоди')
                    ->formatStateUsing(fn ($state) => $state ?? '—')
                    ->sortable()
                    ->toggleable()
                    ->alignCenter(),

                TextColumn::make('first_air_date')
                    ->label('Початок ефіру')
                    ->date('d-m-Y')
                    ->sortable()
                    ->toggleable()
                    ->color('gray')
                    ->alignCenter(),

                TextColumn::make('last_air_date')
                    ->label('Завершення ефіру')
                    ->date('d-m-Y')
                    ->sortable()
                    ->toggleable()
                    ->color('gray')
                    ->alignCenter(),

                TextColumn::make('imdb_score')
                    ->label('Рейтинг IMDb')
                    ->sortable()
                    ->toggleable()
                    ->formatStateUsing(fn ($state) => $state ? number_format($state, 1) : '—')
                    ->color(fn ($state) => $state >= 7 ? 'success' : ($state >= 5 ? 'warning' : 'danger'))
                    ->alignCenter(),

                TextColumn::make('similars')
                    ->label('Схожі')
                    ->formatStateUsing(fn ($state) => $state ? implode(', ', json_decode($state, true) ?: [$state]) : '—')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_published')
                    ->label('Опубліковано')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable()
                    ->toggleable()
                    ->alignCenter(),

                TextColumn::make('kind')
                    ->label('Тип')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Статус')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('period')
                    ->label('Період')
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('restricted_rating')
                    ->label('Вікове обмеження')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('source')
                    ->label('Джерело')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Оновлено')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kind')
                    ->options(Kind::class)
                    ->label('Тип'),

                SelectFilter::make('status')
                    ->options(Status::class)
                    ->label('Статус'),

                SelectFilter::make('studio')
                    ->relationship('studio', 'name')
                    ->label('Студія')
                    ->searchable()
                    ->preload(),

                TernaryFilter::make('is_published')
                    ->label('Опубліковано'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListMovies::route('/'),
            'create' => Pages\CreateMovie::route('/create'),
            'view' => Pages\ViewMovie::route('/{record}'),
            'edit' => Pages\EditMovie::route('/{record}/edit'),
        ];
    }
}

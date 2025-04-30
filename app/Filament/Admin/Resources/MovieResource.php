<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Liamtseva\Cinema\Enums\ApiSourceName;
use Liamtseva\Cinema\Enums\AttachmentType;
use Liamtseva\Cinema\Enums\Country;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Enums\MovieRelateType;
use Liamtseva\Cinema\Enums\Period;
use Liamtseva\Cinema\Enums\RestrictedRating;
use Liamtseva\Cinema\Enums\Source;
use Liamtseva\Cinema\Enums\Status;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\Pages;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers\CommentsRelationManager;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers\EpisodesRelationManager;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers\PersonsRelationManager;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers\RatingsRelationManager;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers\SelectionsRelationManager;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers\TagsRelationManager;
use Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers\UserListsRelationManager;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Scopes\PublishedScope;

class MovieResource extends Resource
{
    protected static ?string $model = Movie::class;

    protected static ?string $navigationIcon = 'heroicon-o-film';

    protected static ?string $navigationLabel = 'Фільми';

    protected static ?string $modelLabel = 'фільм';

    protected static ?string $pluralModelLabel = 'фільми';

    protected static ?string $navigationGroup = 'Контент';

    protected static ?int $navigationSort = 1;

    public static function getNavigationBadge(): ?string
    {
        return (string) Movie::whereIn('status', [Status::ANONS, Status::ONGOING])->count();
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([PublishedScope::class]);
    }

    public static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
            ->withoutGlobalScopes([PublishedScope::class]);
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

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Основна інформація')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    TextInput::make('name')
                        ->label('Назва')
                        ->required()
                        ->minLength(2)
                        ->maxLength(248)
                        ->prefixIcon('clarity-text-line')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                            if ($operation == 'edit' || empty($state)) {
                                return;
                            }
                            $set('slug', Movie::generateSlug($state));
                            $set('meta_title', Movie::makeMetaTitle($state));
                        }),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->minLength(2)
                        ->maxLength(128)
                        ->unique(Movie::class, 'slug', ignoreRecord: true)
                        ->prefixIcon('heroicon-o-link'),

                    Repeater::make('api_sources')
                        ->label('Джерела API')
                        ->schema([
                            Select::make('name')
                                ->label('Назва джерела')
                                ->required()
                                ->options(ApiSourceName::class)
                                ->native(false)
                                ->searchable(),

                            TextInput::make('id')
                                ->label('ID')
                                ->required()
                                ->minLength(1)
                                ->maxLength(255),
                        ])
                        ->maxItems(5)
                        ->defaultItems(0)
                        ->collapsible()
                        ->columnSpanFull(),

                    TagsInput::make('aliases')
                        ->label('Аліаси')
                        ->placeholder('Додайте аліаси'),

                    Select::make('studio_id')
                        ->label('Студія')
                        ->relationship('studio', 'name')
                        ->required()
                        ->preload()
                        ->searchable()
                        ->prefixIcon('heroicon-o-building-office'),

                    Select::make('kind')
                        ->label('Тип')
                        ->options(Kind::class)
                        ->required()
                        ->prefixIcon('heroicon-o-video-camera')
                        ->native(false)
                        ->searchable(),

                    Select::make('status')
                        ->label('Статус')
                        ->options(Status::class)
                        ->required()
                        ->prefixIcon('clarity-shield-check-line')
                        ->native(false)
                        ->searchable(),

                    Select::make('period')
                        ->label('Період')
                        ->options(Period::class)
                        ->nullable()
                        ->prefixIcon('heroicon-o-calendar-days')
                        ->native(false)
                        ->searchable(),

                    Select::make('restricted_rating')
                        ->label('Вікове обмеження')
                        ->options(RestrictedRating::class)
                        ->required()
                        ->prefixIcon('heroicon-o-shield-exclamation')
                        ->native(false)
                        ->searchable(),

                    Select::make('source')
                        ->label('Джерело')
                        ->options(Source::class)
                        ->required()
                        ->prefixIcon('heroicon-o-cloud')
                        ->native(false)
                        ->searchable(),
                ])
                ->columns(2),

            Section::make('Додаткові відомості')
                ->icon('heroicon-o-document-text')
                ->schema([
                    RichEditor::make('description')
                        ->required()
                        ->minLength(50)
                        ->maxLength(5000)
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
                        ->maxValue(1000)
                        ->nullable()
                        ->suffix('хв'),

                    TextInput::make('episodes_count')
                        ->label('Кількість епізодів')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(10000)
                        ->nullable(),

                    DatePicker::make('first_air_date')
                        ->label('Дата початку ефіру')
                        ->native(false)
                        ->nullable()
                        ->before('last_air_date')
                        ->afterOrEqual('1895-12-28')
                        ->beforeOrEqual(now()->addYears(10))
                        ->helperText('Не раніше 28.12.1895 (перший кінопоказ)'),

                    DatePicker::make('last_air_date')
                        ->label('Дата завершення ефіру')
                        ->native(false)
                        ->nullable()
                        ->after('first_air_date')
                        ->beforeOrEqual(now()->addYears(10)),

                    TextInput::make('imdb_score')
                        ->label('Оцінка IMDb')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(10)
                        ->step(0.1)
                        ->nullable(),

                    Toggle::make('is_published')
                        ->label('Опубліковано')
                        ->default(false)
                        ->helperText('Увімкніть для публікації'),

                    Select::make('countries')
                        ->label('Країни')
                        ->multiple()
                        ->native(false)
                        ->searchable()
                        ->preload()
                        ->options(Country::class),
                    Select::make('similars')
                        ->label('Схожі фільми')
                        ->options(function (?Movie $record) {
                            if (! $record) {
                                return Movie::all()->pluck('name', 'id');
                            }

                            return Movie::where('id', '!=', $record->id)
                                ->get()
                                ->pluck('name', 'id');
                        })
                        ->multiple()
                        ->preload()
                        ->searchable(),
                ])
                ->columns(2),

            Section::make('Медіа')
                ->icon('heroicon-o-photo')
                ->schema([
                    FileUpload::make('image_name')
                        ->label('Зображення назви фільму')
                        ->image()
                        ->maxSize(2048)
                        ->minSize(50)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                        ->directory('movies/images')
                        ->required(),

                    FileUpload::make('poster')
                        ->label('Постер')
                        ->image()
                        ->maxSize(2048)
                        ->minSize(50)
                        ->directory('movie/posters')
                        ->nullable(),
                ])
                ->columns(2),

            Section::make('Пов\'язаний контент')
                ->icon('heroicon-o-link')
                ->schema([
                    Repeater::make('attachments')
                        ->label('Вкладення')
                        ->schema([
                            Select::make('type')
                                ->label('Тип')
                                ->options(AttachmentType::class)
                                ->required()
                                ->reactive(),
                            TextInput::make('src')
                                ->label('Джерело')
                                ->required()
                                ->url(),
                        ])
                        ->columns(2)
                        ->default([])
                        ->collapsible(),

                    Repeater::make('related')
                        ->label('Пов\'язані фільми')
                        ->columns(2)
                        ->schema([
                            Select::make('movie_id')
                                ->label('Фільм')
                                ->options(function (?Movie $record) {
                                    if (! $record) {
                                        return Movie::all()->pluck('name', 'id');
                                    }

                                    return Movie::where('id', '!=', $record->id)
                                        ->get()
                                        ->pluck('name', 'id');
                                })
                                ->searchable()
                                ->preload()
                                ->required(),

                            Select::make('type')
                                ->label('Тип зв\'язку')
                                ->native(false)
                                ->searchable()
                                ->preload()
                                ->options(MovieRelateType::class)
                                ->required(),
                        ])
                        ->defaultItems(0)
                        ->collapsible()
                        ->addActionLabel('Додати зв\'язок')
                        ->columnSpanFull(),
                ])
                ->columns(1),

            Section::make('SEO налаштування')
                ->icon('heroicon-o-globe-alt')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta назва')
                        ->minLength(10)
                        ->maxLength(128)
                        ->nullable()
                        ->prefixIcon('heroicon-o-tag'),

                    FileUpload::make('meta_image')
                        ->label('Meta зображення')
                        ->image()
                        ->maxSize(2048)
                        ->minSize(50)
                        ->directory('movies/meta')
                        ->nullable(),

                    Textarea::make('meta_description')
                        ->label('Meta опис')
                        ->minLength(50)
                        ->maxLength(376)
                        ->rows(3)
                        ->nullable(),
                ])
                ->collapsed()
                ->columns(2),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            EpisodesRelationManager::class,
            CommentsRelationManager::class,
            RatingsRelationManager::class,
            PersonsRelationManager::class,
            SelectionsRelationManager::class,
            UserListsRelationManager::class,
            TagsRelationManager::class,
        ];
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

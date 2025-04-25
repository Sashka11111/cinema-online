<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\RelationManagers;

use Filament\Forms\Components\DatePicker;
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
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Enums\Period;
use Liamtseva\Cinema\Enums\RestrictedRating;
use Liamtseva\Cinema\Enums\Source;
use Liamtseva\Cinema\Enums\Status;
use Liamtseva\Cinema\Models\Movie;

class MoviesRelationManager extends RelationManager
{
    protected static string $relationship = 'movies';

    protected static ?string $title = 'Фільми';

    protected static ?string $modelLabel = 'фільм';

    protected static ?string $pluralModelLabel = 'фільми';

    public function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Основна інформація')
                ->icon('heroicon-o-film')
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
                            $set('slug', Movie::generateSlug($state));
                            $set('meta_title', Movie::makeMetaTitle($state));
                        }),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(128)
                        ->unique(table: 'movies', column: 'slug', ignoreRecord: true)
                        ->prefixIcon('heroicon-o-link')
                        ->helperText('Автоматично генерується з назви'),

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
                        ->columnSpanFull(),

                    RichEditor::make('description')
                        ->label('Опис')
                        ->required()
                        ->maxLength(65535)
                        ->columnSpanFull()
                        ->disableToolbarButtons(['attachFiles'])
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                            if ($operation == 'edit' || empty($state)) {
                                return;
                            }
                            $plainText = strip_tags($state);
                            $set('meta_description', Movie::makeMetaDescription($plainText));
                        }),

                    Select::make('kind')
                        ->label('Тип')
                        ->options(Kind::class)
                        ->enum(Kind::class)
                        ->required()
                        ->prefixIcon('heroicon-o-video-camera'),

                    Select::make('status')
                        ->label('Статус')
                        ->options(Status::class)
                        ->enum(Status::class)
                        ->required()
                        ->prefixIcon('clarity-shield-check-line'),

                    Select::make('period')
                        ->label('Період')
                        ->options(Period::class)
                        ->enum(Period::class)
                        ->nullable()
                        ->prefixIcon('heroicon-o-clock'),

                    Select::make('restricted_rating')
                        ->label('Вікове обмеження')
                        ->options(RestrictedRating::class)
                        ->enum(RestrictedRating::class)
                        ->required()
                        ->prefixIcon('heroicon-o-shield-exclamation'),

                    Select::make('source')
                        ->label('Джерело')
                        ->options(Source::class)
                        ->enum(Source::class)
                        ->required()
                        ->prefixIcon('heroicon-o-globe-alt'),

                    TagsInput::make('aliases')
                        ->label('Аліаси')
                        ->placeholder('Додайте аліаси'),

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

                    Toggle::make('is_published')
                        ->label('Опубліковано')
                        ->default(false),
                ])
                ->columns(2),

            Section::make('Медіа')
                ->icon('heroicon-o-photo')
                ->schema([
                    FileUpload::make('poster')
                        ->label('Постер')
                        ->image()
                        ->imagePreviewHeight('100')
                        ->maxSize(2048)
                        ->directory('movies/posters')
                        ->disk('public')
                        ->nullable(),

                    FileUpload::make('image_name')
                        ->label('Зображення назви фільму')
                        ->image()
                        ->imagePreviewHeight('100')
                        ->maxSize(2048)
                        ->directory('movies/images')
                        ->disk('public')
                        ->required(),
                ])
                ->columns(2),

            Section::make('Додаткові параметри')
                ->icon('heroicon-o-cog')
                ->schema([
                    TextInput::make('duration')
                        ->label('Тривалість (хвилини)')
                        ->numeric()
                        ->minValue(0)
                        ->nullable()
                        ->prefixIcon('heroicon-o-clock'),

                    TextInput::make('episodes_count')
                        ->label('Кількість епізодів')
                        ->numeric()
                        ->minValue(0)
                        ->nullable()
                        ->prefixIcon('heroicon-o-list-bullet'),

                    DatePicker::make('first_air_date')
                        ->label('Дата початку ефіру')
                        ->displayFormat('d.m.Y')
                        ->nullable()
                        ->prefixIcon('heroicon-o-calendar'),

                    DatePicker::make('last_air_date')
                        ->label('Дата завершення ефіру')
                        ->displayFormat('d.m.Y')
                        ->nullable()
                        ->prefixIcon('heroicon-o-calendar'),

                    TextInput::make('imdb_score')
                        ->label('Оцінка IMDB')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(10)
                        ->step(0.1)
                        ->nullable()
                        ->prefixIcon('heroicon-o-star'),
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
                        ->directory('movies/meta')
                        ->disk('public')
                        ->nullable(),

                    RichEditor::make('meta_description')
                        ->label('Meta опис')
                        ->maxLength(376)
                        ->columnSpanFull()
                        ->nullable(),
                ])
                ->collapsed()
                ->columns(2),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('poster')
                    ->label('Постер')
                    ->disk('public')
                    ->width(50)
                    ->height(50)
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('original_name')
                    ->label('Оригінальна назва')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                ToggleColumn::make('is_published')
                    ->label('Опубліковано')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('release_date')
                    ->label('Дата виходу')
                    ->date('d.m.Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_published')
                    ->label('Опубліковано'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Створити фільм'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Редагувати'),
                Tables\Actions\DeleteAction::make()
                    ->label('Видалити'),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->label('Видалити вибрані'),
            ]);
    }
}

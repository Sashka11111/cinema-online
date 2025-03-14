<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TagsColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
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

    public static function getNavigationBadge(): ?string
    {
        return (string) Movie::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('основна інформація')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    TextInput::make('name')
                        ->label('назва')
                        ->required()
                        ->maxLength(248)
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
                        ->label('url-посилання')
                        ->required()
                        ->maxLength(128)
                        ->prefixIcon('heroicon-o-link')
                        ->unique(ignoreRecord: true),

                    Select::make('studio_id')
                        ->label('студія')
                        ->relationship('studio', 'name')
                        ->required()
                        ->prefixIcon('heroicon-o-building-office')
                        ->searchable(),

                    Select::make('kind')
                        ->label('тип')
                        ->options(Kind::getLabels())
                        ->required()
                        ->prefixIcon('heroicon-o-video-camera'),
                ])
                ->columns(2),

            Section::make('додаткові деталі')
                ->icon('heroicon-o-document-text')
                ->schema([
                    RichEditor::make('description')
                        ->required()
                        ->columnSpanFull()
                        ->label('Опис')
                        ->toolbarButtons([
                            'bold', 'italic', 'underline', 'strike',
                            'h2', 'h3', 'h4', 'bulletList', 'orderedList',
                            'link', 'blockquote', 'codeBlock', 'undo', 'redo',
                        ])
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                            if ($operation == 'edit' || empty($state)) {
                                return;
                            }
                            $plainText = strip_tags($state);
                            $set('meta_description', Movie::makeMetaDescription($plainText));
                        }),

                    TextInput::make('duration')
                        ->label('тривалість (хв)')
                        ->numeric()
                        ->minValue(1)
                        ->nullable()
                        ->suffix('хв'),

                    TextInput::make('episodes_count')
                        ->label('кількість епізодів')
                        ->numeric()
                        ->minValue(1)
                        ->nullable(),

                    DatePicker::make('first_air_date')
                        ->label('дата початку ефіру')
                        ->native(false)
                        ->nullable(),

                    DatePicker::make('last_air_date')
                        ->label('дата завершення ефіру')
                        ->native(false)
                        ->nullable(),

                    TextInput::make('imdb_score')
                        ->label('оцінка imdb')
                        ->numeric()
                        ->minValue(0)
                        ->maxValue(10)
                        ->step(0.1)
                        ->nullable(),
                ])
                ->columns(2),

            Section::make('медіа')
                ->icon('heroicon-o-photo')
                ->schema([
                    FileUpload::make('image_name')
                        ->label('головне зображення')
                        ->image()
                        ->maxSize(2048)
                        ->directory('movie-images')
                        ->required(),

                    FileUpload::make('poster')
                        ->label('постер')
                        ->image()
                        ->maxSize(2048)
                        ->directory('movie-posters')
                        ->nullable(),

                    TagsInput::make('aliases')
                        ->label('аліаси')
                        ->placeholder('додайте аліаси')
                        ->nested(),

                    TagsInput::make('countries')
                        ->label('країни')
                        ->placeholder('додайте країни')
                        ->nested(),
                ])
                ->columns(2),

            Section::make('пов’язаний контент')
                ->icon('heroicon-o-link')
                ->schema([
                    TagsInput::make('api_sources')
                        ->label('джерела api')
                        ->placeholder('додайте api джерела')
                        ->nested(),

                    TagsInput::make('attachments')
                        ->label('прикріплення')
                        ->placeholder('додайте прикріплення')
                        ->nested(),

                    TagsInput::make('related')
                        ->label('пов’язані')
                        ->placeholder('додайте пов’язані')
                        ->nested(),

                    TagsInput::make('similars')
                        ->label('схожі')
                        ->placeholder('додайте схожі')
                        ->nested(),
                ])
                ->columns(2),

            Section::make('seo налаштування')
                ->icon('heroicon-o-globe-alt')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('seo заголовок')
                        ->maxLength(128)
                        ->nullable(),

                    Textarea::make('meta_description')
                        ->label('seo опис')
                        ->maxLength(376)
                        ->rows(3)
                        ->nullable(),

                    FileUpload::make('meta_image')
                        ->label('seo зображення')
                        ->image()
                        ->maxSize(2048)
                        ->directory('movie-meta-images')
                        ->nullable(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_name')
                    ->label('Зображення')
                    ->disk('public')
                    ->width(50)
                    ->height(50)
                    ->circular()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Назва')
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
                    ->formatStateUsing(function ($state) {
                        $decoded = json_decode($state, true);

                        return is_array($decoded) ? implode(', ', $decoded) : $state;
                    })
                    ->searchable()
                    ->toggleable()
                    ->wrap(),

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

                TagsColumn::make('attachments')
                    ->label('Прикріплення')
                    ->formatStateUsing(fn ($state) => is_array($state) ? $state : json_decode($state ?? '[]', true))
                    ->toggleable(isToggledHiddenByDefault: true),

                TagsColumn::make('related')
                    ->label('Пов’язані')
                    ->formatStateUsing(fn ($state) => is_array($state) ? $state : json_decode($state ?? '[]', true))
                    ->toggleable(isToggledHiddenByDefault: true),

                TagsColumn::make('similars')
                    ->label('Схожі')
                    ->formatStateUsing(fn ($state) => is_array($state) ? $state : json_decode($state ?? '[]', true))
                    ->toggleable(isToggledHiddenByDefault: true),

                BooleanColumn::make('is_published')
                    ->label('Опубліковано')
                    ->sortable()
                    ->toggleable()
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->alignCenter(),

                TextColumn::make('meta_title')
                    ->label('SEO Заголовок')
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->meta_title)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('meta_description')
                    ->label('SEO Опис')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->meta_description)
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('meta_image')
                    ->label('SEO Зображення')
                    ->disk('public')
                    ->width(50)
                    ->height(50)
                    ->circular()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('kind')
                    ->label('Тип')
                    ->formatStateUsing(fn (Kind $state) => Kind::getLabels()[$state->value])
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        Kind::MOVIE => 'primary',
                        Kind::TV_SERIES => 'success',
                        Kind::ANIME => 'warning',
                        default => 'gray',
                    })
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Статус')
                    ->formatStateUsing(fn (Status $state) => Status::getLabels()[$state->value])
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        Status::ONGOING => 'success',
                        Status::RELEASED => 'info',
                        Status::ANONS => 'warning',
                        default => 'gray',
                    })
                    ->toggleable(),

                TextColumn::make('period')
                    ->label('Період')
                    ->formatStateUsing(fn (?Period $state) => $state ? Period::getLabels()[$state->value] : '—')
                    ->badge()
                    ->color('info')
                    ->toggleable(),

                TextColumn::make('restricted_rating')
                    ->label('Вікове обмеження')
                    ->formatStateUsing(fn (RestrictedRating $state) => RestrictedRating::getLabels()[$state->value])
                    ->badge()
                    ->color('warning')
                    ->toggleable(),

                TextColumn::make('source')
                    ->label('Джерело')
                    ->formatStateUsing(fn (Source $state) => Source::getLabels()[$state->value])
                    ->badge()
                    ->color('gray')
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
                    ->options(Kind::getLabels())
                    ->label('Тип'),

                SelectFilter::make('status')
                    ->options(Status::getLabels())
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
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('gray'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('first_air_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMovies::route('/'),
            'create' => Pages\CreateMovie::route('/create'),
            'edit' => Pages\EditMovie::route('/{record}/edit'),
        ];
    }
}

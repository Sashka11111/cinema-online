<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
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
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Filament\Admin\Resources\TagResource\Pages;
use Liamtseva\Cinema\Filament\Admin\Resources\TagResource\RelationManagers\MoviesRelationManager;
use Liamtseva\Cinema\Models\Tag;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $navigationLabel = 'Теги';

    protected static ?string $modelLabel = 'тег';

    protected static ?string $pluralModelLabel = 'Теги';

    protected static ?string $navigationGroup = 'Контент';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('image')
                    ->label('Зображення')
                    ->disk('public')
                    ->width(50)
                    ->height(50)
                    ->circular()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Назва та slug')
                    ->description(fn (Tag $tag): string => $tag->slug)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->tooltip(fn (Tag $record): ?string => $record->description)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('aliases')
                    ->label('Аліаси')
                    ->formatStateUsing(function ($state) {
                        $decoded = json_decode($state, true);

                        return is_array($decoded) ? implode(', ', $decoded) : $state;
                    })
                    ->searchable()
                    ->toggleable()
                    ->wrap(),

                IconColumn::make('is_genre')
                    ->label('Жанр')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Дата оновлення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('is_genre')
                    ->label('Жанр')
                    ->options([
                        '1' => 'Так',
                        '0' => 'Ні',
                    ]),

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Дата створення від')
                            ->placeholder('Виберіть дату'),
                        DatePicker::make('created_until')
                            ->label('До')
                            ->placeholder('Виберіть дату'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($query) => $query->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($query) => $query->whereDate('created_at', '<=', $data['created_until']));
                    }),
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
        return $form
            ->schema([
                Section::make('Основна інформація')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        TextInput::make('name')
                            ->label('Назва')
                            ->required()
                            ->maxLength(128)
                            ->prefixIcon('heroicon-o-tag')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                                if ($operation == 'edit' || empty($state)) {
                                    return;
                                }
                                $set('slug', Tag::generateSlug($state));
                                $set('meta_title', Tag::makeMetaTitle($state));
                            }),

                        TextInput::make('slug')
                            ->label('Slug')
                            ->required()
                            ->maxLength(128)
                            ->unique(Tag::class, 'slug', ignoreRecord: true)
                            ->helperText('Автоматично генерується з імені')
                            ->prefixIcon('heroicon-o-link'),

                        FileUpload::make('image')
                            ->label('Зображення')
                            ->image()
                            ->imagePreviewHeight('100')
                            ->maxSize(2048)
                            ->directory('tags')
                            ->nullable(),

                        Toggle::make('is_genre')
                            ->label('Позначити як жанр')
                            ->default(false),

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

                        RichEditor::make('description')
                            ->label('Опис')
                            ->maxLength(512)
                            ->required()
                            ->columnSpanFull()
                            ->disableToolbarButtons(['attachFiles'])
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, string $state, Set $set) {
                                if ($operation == 'edit' || empty($state)) {
                                    return;
                                }
                                $plainText = strip_tags($state);
                                $set('meta_description', Tag::makeMetaDescription($plainText));
                            }),
                    ])
                    ->columns(2),

                Section::make('Аліаси')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        TagsInput::make('aliases')
                            ->label('Аліаси')
                            ->placeholder('Додайте аліаси')
                            ->columnSpanFull(),
                    ]),

                Section::make('SEO налаштування')
                    ->icon('heroicon-o-globe-alt')
                    ->collapsed()
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Мета-заголовок')
                            ->maxLength(128)
                            ->prefixIcon('heroicon-o-document-text'),

                        FileUpload::make('meta_image')
                            ->label('Мета-зображення')
                            ->image()
                            ->imagePreviewHeight('100')
                            ->maxSize(2048)
                            ->directory('meta-tags')
                            ->nullable(),

                        RichEditor::make('meta_description')
                            ->label('Мета-опис')
                            ->maxLength(376)
                            ->nullable()
                            ->disableToolbarButtons(['attachFiles'])
                            ->helperText('Автоматично генерується з опису')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            MoviesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'view' => Pages\ViewTag::route('/{record}'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }
}

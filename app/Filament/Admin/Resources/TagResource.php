<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Filament\Admin\Resources\TagResource\Pages;
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
                ImageColumn::make('image')
                    ->label('Зображення')
                    ->disk('public')
                    ->width(50)
                    ->height(50)
                    ->circular()
                    ->toggleable(),

                TextColumn::make('name')
                    ->label('Назва')
                    ->description(fn (Tag $tag): string => $tag->slug)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(60)
                    ->tooltip(fn (Tag $tag): string => $tag->description)
                    ->searchable()
                    ->toggleable()
                    ->wrap(),

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
            ])
            ->filters([
                SelectFilter::make('is_genre')
                    ->label('Жанр')
                    ->options([
                        '1' => 'Так',
                        '0' => 'Ні',
                    ]),
                Filter::make('name')
                    ->form([
                        TextInput::make('name')->label('Пошук за назвою'),
                    ])
                    ->query(fn ($query, $data) => $query->when(
                        $data['name'],
                        fn ($query) => $query->where('name', 'ilike', '%'.$data['name'].'%')
                    )),

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
                            ->prefixIcon('heroicon-o-tag'),

                        TextInput::make('slug')
                            ->label('Слаг')
                            ->required()
                            ->maxLength(128)
                            ->unique(ignoreRecord: true)
                            ->prefixIcon('heroicon-o-link'),

                        Textarea::make('description')
                            ->label('Опис')
                            ->required()
                            ->maxLength(512)
                            ->rows(3)
                            ->columnSpanFull(),

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

                Section::make('Мета-дані')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Мета-заголовок')
                            ->maxLength(128)
                            ->nullable()
                            ->prefixIcon('heroicon-o-document-text'),

                        Textarea::make('meta_description')
                            ->label('Мета-опис')
                            ->maxLength(376)
                            ->rows(3)
                            ->nullable()
                            ->columnSpanFull(),

                        FileUpload::make('meta_image')
                            ->label('Мета-зображення')
                            ->image()
                            ->imagePreviewHeight('100')
                            ->maxSize(2048)
                            ->directory('meta-tags')
                            ->nullable(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTag::route('/create'),
            'edit' => Pages\EditTag::route('/{record}/edit'),
        ];
    }
}

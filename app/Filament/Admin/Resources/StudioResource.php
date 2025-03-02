<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Pages;
use Liamtseva\Cinema\Models\Studio;

class StudioResource extends Resource
{
    protected static ?string $model = Studio::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'Студії';
    protected static ?string $modelLabel = 'студію';
    protected static ?string $pluralModelLabel = 'Студії';
    protected static ?string $navigationGroup = 'Персони та студії';
    protected static ?int $navigationSort = 2;

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
                    ->description(fn(Studio $studio): string => $studio->slug)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('name')
                    ->form([
                        TextInput::make('name')->label('Пошук за назвою'),
                    ])
                    ->query(fn($query, $data) => $query->when(
                        $data['name'],
                        fn($query) => $query->where('name', 'ilike', '%' . $data['name'] . '%')
                    )),
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
        return $form->schema([
            Section::make('Основна інформація')
                ->schema([
                    TextInput::make('name')
                        ->label('Назва')
                        ->required()
                        ->maxLength(128)
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $set('slug', str()->slug($state));
                        }),

                    TextInput::make('slug')
                        ->label('Слаг')
                        ->required()
                        ->maxLength(128)
                        ->unique(ignoreRecord: true),

                    Textarea::make('description')
                        ->label('Опис')
                        ->required()
                        ->maxLength(512)
                        ->rows(4),
                ])
                ->columns(2),

            Section::make('Медіа')
                ->schema([
                    FileUpload::make('image')
                        ->label('Головне зображення')
                        ->image()
                        ->imagePreviewHeight('100')
                        ->maxSize(2048)
                        ->directory('studios')
                        ->nullable(),
                ]),

            Section::make('SEO')
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta Title')
                        ->maxLength(128)
                        ->nullable(),

                    Textarea::make('meta_description')
                        ->label('Meta Description')
                        ->maxLength(376)
                        ->rows(3)
                        ->nullable(),

                    FileUpload::make('meta_image')
                        ->label('SEO Зображення')
                        ->image()
                        ->imagePreviewHeight('100')
                        ->maxSize(2048)
                        ->directory('studios/meta')
                        ->nullable(),
                ])
                ->columns(2),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            // Додайте зв'язки якщо потрібно, наприклад:
            // RelationManagers\MoviesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudios::route('/'),
            'create' => Pages\CreateStudio::route('/create'),
            'edit' => Pages\EditStudio::route('/{record}/edit'),
        ];
    }
}

<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\Pages;
use Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\RelationManagers\EpisodesRelationManager;
use Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\RelationManagers\MoviesRelationManager;
use Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\RelationManagers\PersonsRelationManager;
use Liamtseva\Cinema\Models\Selection;

class SelectionResource extends Resource
{
    protected static ?string $model = Selection::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Підбірки';

    protected static ?string $modelLabel = 'підбірку';

    protected static ?string $pluralModelLabel = 'Підбірки';

    protected static ?string $navigationGroup = 'Контент';

    protected static ?int $navigationSort = 4;

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
                            ->unique(Selection::class, 'slug', ignoreRecord: true)
                            ->helperText('Автоматично генерується з назви')
                            ->prefixIcon('heroicon-o-link'),

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

                        Select::make('user_id')
                            ->label('Автор')
                            ->relationship('user', 'name')
                            ->required()
                            ->preload()
                            ->searchable()
                            ->prefixIcon('heroicon-o-user'),
                    ])
                    ->columns(2),

                Section::make('Опис')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        RichEditor::make('description')
                            ->label('Опис')
                            ->nullable()
                            ->columnSpanFull()
                            ->disableToolbarButtons(['attachFiles'])
                            ->live(onBlur: true)
                            ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                                if ($operation == 'edit' || empty($state)) {
                                    return;
                                }
                                $plainText = strip_tags($state);
                                $set('meta_description', str($plainText)->limit(370, '...'));
                            }),
                    ]),

                Section::make('SEO налаштування')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        TextInput::make('meta_title')
                            ->label('Meta назва')
                            ->maxLength(128)
                            ->disabled()
                            ->prefixIcon('heroicon-o-tag'),

                        FileUpload::make('meta_image')
                            ->label('Meta зображення')
                            ->image()
                            ->imagePreviewHeight('100'),

                        RichEditor::make('meta_description')
                            ->label('Meta опис')
                            ->maxLength(376)
                            ->nullable()
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

                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->toggleable(),

                TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->slug)
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->description)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Автор')
                    ->searchable()
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
                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Автор')
                    ->searchable()
                    ->preload(),
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

    public static function getRelations(): array
    {
        return [
            MoviesRelationManager::class,
            EpisodesRelationManager::class,
            PersonsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSelections::route('/'),
            'create' => Pages\CreateSelection::route('/create'),
            'view' => Pages\ViewSelection::route('/{record}'),
            'edit' => Pages\EditSelection::route('/{record}/edit'),
        ];
    }
}

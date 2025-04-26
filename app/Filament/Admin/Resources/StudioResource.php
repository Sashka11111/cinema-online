<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\Pages;
use Liamtseva\Cinema\Filament\Admin\Resources\StudioResource\RelationManagers\MoviesRelationManager;
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
            ->modifyQueryUsing(function (Builder $query) {
                return $query->withCount('movies');
            })
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
                    ->description(fn (Studio $studio): string => $studio->slug)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->tooltip(fn (Studio $record): ?string => $record->description)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('movies_count')
                    ->label('Кількість фільмів')
                    ->sortable()
                    ->alignCenter()
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
        return $form->schema([
            Section::make('Основна інформація')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    TextInput::make('name')
                        ->label('Назва')
                        ->required()
                        ->maxLength(128)
                        ->live(onBlur: true)
                        ->prefixIcon('clarity-text-line')
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                            if ($operation == 'edit' || empty($state)) {
                                return;
                            }
                            $set('slug', Studio::generateSlug($state));
                            $set('meta_title', Studio::makeMetaTitle($state));
                        }),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(128)
                        ->unique(Studio::class, 'slug', ignoreRecord: true)
                        ->helperText('Автоматично генерується з імені')
                        ->prefixIcon('heroicon-o-link'),

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
                            $set('meta_description', Studio::makeMetaDescription($plainText));
                        }),

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

            Section::make('Медіа')
                ->icon('heroicon-o-photo')
                ->schema([
                    FileUpload::make('image')
                        ->label('Головне зображення')
                        ->image()
                        ->imagePreviewHeight('100')
                        ->maxSize(2048)
                        ->directory('studios')
                        ->nullable(),
                ]),

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
                        ->maxLength(376)
                        ->columnSpanFull(),
                ])
                ->collapsed()
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
            'index' => Pages\ListStudios::route('/'),
            'create' => Pages\CreateStudio::route('/create'),
            'view' => Pages\ViewStudio::route('/{record}'),
            'edit' => Pages\EditStudio::route('/{record}/edit'),
        ];
    }
}

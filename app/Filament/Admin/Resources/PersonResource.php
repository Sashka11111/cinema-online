<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Enums\Gender;
use Liamtseva\Cinema\Enums\PersonType;
use Liamtseva\Cinema\Filament\Admin\Resources\PersonResource\Pages;
use Liamtseva\Cinema\Models\Person;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationLabel = 'Персони';

    protected static ?string $modelLabel = 'персону';

    protected static ?string $pluralModelLabel = 'Персони';

    protected static ?string $navigationGroup = 'Персони та студії';

    protected static ?int $navigationSort = 1;

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
                    ->label('Ім’я')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('original_name')
                    ->label('Оригінальне ім’я')
                    ->default('Немає оригінального імені')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('Тип')
                    ->badge()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('gender')
                    ->label('Стать')
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('birthday')
                    ->label('Дата та місце народження')
                    ->formatStateUsing(fn (Person $record) => $record->birthday->format('d-m-Y').
                        ($record->birthplace ? ' ('.$record->birthplace.')' : ''))
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
                    ->tooltip(fn (Person $record): ?string => $record->description)
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

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
                SelectFilter::make('type')
                    ->label('Тип')
                    ->options(PersonType::class)
                    ->multiple(),

                SelectFilter::make('gender')
                    ->label('Стать')
                    ->options(Gender::class)
                    ->multiple(),

                Filter::make('birthday')
                    ->form([
                        DatePicker::make('birthday_from')->label('Дата народження від'),
                        DatePicker::make('birthday_to')->label('Дата народження до'),
                    ])
                    ->query(fn ($query, $data) => $query
                        ->when($data['birthday_from'], fn ($q, $date) => $q->where('birthday', '>=', $date))
                        ->when($data['birthday_to'], fn ($q, $date) => $q->where('birthday', '<=', $date))),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-o-eye')
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-o-pencil')
                    ->color('primary'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-o-trash')
                    ->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Основна інформація')
                ->icon('heroicon-o-information-circle')
                ->schema([
                    TextInput::make('name')
                        ->label('Ім’я')
                        ->required()
                        ->maxLength(128)
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                            if ($operation == 'edit' || empty($state)) {
                                return;
                            }
                            $set('slug', Person::generateSlug($state));
                            $set('meta_title', Person::makeMetaTitle($state));
                        }),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->maxLength(128)
                        ->unique(Person::class, 'slug', ignoreRecord: true)
                        ->helperText('Автоматично генерується з імені'),

                    Select::make('type')
                        ->label('Тип')
                        ->options(PersonType::class)
                        ->required()
                        ->native(false),

                    Select::make('gender')
                        ->label('Стать')
                        ->options(Gender::class)
                        ->nullable()
                        ->native(false),

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

            Section::make('Особисті дані')
                ->icon('heroicon-o-calendar')
                ->schema([
                    TextInput::make('original_name')
                        ->label('Оригінальне ім’я')
                        ->maxLength(128)
                        ->nullable(),

                    DatePicker::make('birthday')
                        ->label('Дата народження')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now())
                        ->nullable(),

                    TextInput::make('birthplace')
                        ->label('Місце народження')
                        ->maxLength(248)
                        ->nullable(),

                    RichEditor::make('description')
                        ->label('Опис')
                        ->columnSpanFull()
                        ->disableToolbarButtons(['attachFiles'])
                        ->live(onBlur: true)
                        ->afterStateUpdated(function (string $operation, ?string $state, Set $set) {
                            if ($operation === 'edit' || empty($state)) {
                                return;
                            }
                            $plainText = strip_tags($state);
                            $set('meta_description', Person::makeMetaDescription($plainText));
                        }),
                ])
                ->columns(3),

            Section::make('Зображення')
                ->icon('heroicon-o-photo')
                ->schema([
                    FileUpload::make('image')
                        ->label('Фото')
                        ->image()
                        ->imageEditor()
                        ->maxSize(2048)
                        ->directory('people')
                        ->previewable()
                        ->nullable(),
                ]),

            Section::make('SEO налаштування')
                ->icon('heroicon-o-globe-alt')
                ->collapsed()
                ->schema([
                    TextInput::make('meta_title')
                        ->label('Meta назва')
                        ->maxLength(128)
                        ->helperText('Автоматично генерується з імені')
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

    public static function getRelations(): array
    {
        return [
            // Наприклад: RelationManagers\MoviesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPeople::route('/'),
            'create' => Pages\CreatePerson::route('/create'),
            'view' => Pages\ViewPerson::route('/{record}'),
            'edit' => Pages\EditPerson::route('/{record}/edit'),
        ];
    }
}

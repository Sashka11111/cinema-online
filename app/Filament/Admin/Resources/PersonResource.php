<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
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
                ImageColumn::make('image')
                    ->label('Фото')
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

                TextColumn::make('description')
                    ->label('Опис')
                    ->limit(50)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('Тип')
                    ->formatStateUsing(fn (PersonType $state) => PersonType::getLabels()[$state->value])
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        PersonType::ACTOR => 'success',
                        PersonType::DIRECTOR => 'info',
                        PersonType::WRITER => 'warning',
                        default => 'primary',
                    })
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('gender')
                    ->label('Стать')
                    ->formatStateUsing(fn (Gender $state) => Gender::getLabels()[$state->value])
                    ->badge()
                    ->color(fn ($state): ?string => match ($state) {
                        Gender::MALE => 'info',
                        Gender::FEMALE => 'warning',
                        Gender::OTHER => 'gray',
                        null => null,
                    })
                    ->icon(fn ($state): ?string => match ($state) {
                        Gender::MALE => 'fas-male',
                        Gender::FEMALE => 'fas-female',
                        Gender::OTHER => 'bx-male-female',
                        null => null,
                    })
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('birthday')
                    ->label('Дата та місце народження')
                    ->formatStateUsing(fn (Person $record) => $record->birthday->format('d-m-Y').
                        ($record->birthplace ? ' ('.$record->birthplace.')' : ''))
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Тип')
                    ->options(PersonType::getLabels())
                    ->multiple(),

                SelectFilter::make('gender')
                    ->label('Стать')
                    ->options(Gender::getLabels())
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
                ->collapsible()
                ->schema([
                    TextInput::make('name')
                        ->label('Ім’я')
                        ->required()
                        ->maxLength(128)
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', str()->slug($state))),

                    TextInput::make('slug')
                        ->label('Слаг')
                        ->required()
                        ->maxLength(128)
                        ->unique(ignoreRecord: true)
                        ->helperText('Автоматично генерується з імені'),

                    TextInput::make('original_name')
                        ->label('Оригінальне ім’я')
                        ->maxLength(128)
                        ->nullable(),

                    Select::make('type')
                        ->label('Тип')
                        ->options(PersonType::getLabels())
                        ->required()
                        ->native(false),

                    Select::make('gender')
                        ->label('Стать')
                        ->options(Gender::getLabels())
                        ->nullable()
                        ->native(false),
                ])
                ->columns(2),

            Section::make('Особисті дані')
                ->icon('heroicon-o-calendar')
                ->collapsible()
                ->schema([
                    DatePicker::make('birthday')
                        ->label('Дата народження')
                        ->displayFormat('d/m/Y')
                        ->maxDate(now())
                        ->nullable(),

                    TextInput::make('birthplace')
                        ->label('Місце народження')
                        ->maxLength(248)
                        ->nullable(),
                ])
                ->columns(2),

            Section::make('Медіа')
                ->icon('heroicon-o-photo')
                ->collapsible()
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

            Section::make('Опис')
                ->icon('heroicon-o-document-text')
                ->collapsible()
                ->schema([
                    Textarea::make('description')
                        ->label('Опис')
                        ->maxLength(512)
                        ->rows(4)
                        ->nullable()
                        ->columnSpanFull(),
                ]),

            Section::make('SEO налаштування')
                ->icon('heroicon-o-globe-alt')
                ->collapsible()
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
                        ->label('Meta зображення')
                        ->image()
                        ->maxSize(2048)
                        ->directory('people/meta')
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
            'edit' => Pages\EditPerson::route('/{record}/edit'),
        ];
    }
}

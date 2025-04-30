<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserResource\RelationManagers;

use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Enums\UserListType;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Person;
use Liamtseva\Cinema\Models\Selection;
use Liamtseva\Cinema\Models\Tag;
use Liamtseva\Cinema\Models\UserList;

class UserListsRelationManager extends RelationManager
{
    protected static string $relationship = 'userLists';

    protected static ?string $title = 'Списки';

    protected static ?string $modelLabel = 'список';

    protected static ?string $pluralModelLabel = 'списки';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Тип списку')
                    ->sortable(),

                TextColumn::make('listable.name')
                    ->label('Елемент списку')
                    ->getStateUsing(fn ($record) => $record->listable ? $record->listable->name : '—')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('listable_type')
                    ->label('Тип елемента')
                    ->sortable()
                    ->sortable()
                    ->getStateUsing(fn (UserList $userList) => $userList->translated_type)
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Тип списку')
                    ->options(UserListType::class),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->label('Тип списку')
                    ->options(UserListType::class)
                    ->required(),

                MorphToSelect::make('listable')
                    ->label('Елемент списку')
                    ->required()
                    ->types([
                        MorphToSelect\Type::make(Movie::class)
                            ->titleAttribute('name')
                            ->label('Фільм'),
                        MorphToSelect\Type::make(Episode::class)
                            ->titleAttribute('name')
                            ->label('Епізод'),
                        MorphToSelect\Type::make(Selection::class)
                            ->titleAttribute('name')
                            ->label('Підбірка'),
                        MorphToSelect\Type::make(Person::class)
                            ->titleAttribute('name')
                            ->label('Персона'),
                        MorphToSelect\Type::make(Tag::class)
                            ->titleAttribute('name')
                            ->label('Тег'),
                    ]),
            ]);
    }
}

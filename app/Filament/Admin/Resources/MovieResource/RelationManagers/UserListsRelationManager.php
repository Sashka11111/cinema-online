<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers;

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

class UserListsRelationManager extends RelationManager
{
    protected static string $relationship = 'userLists';

    protected static ?string $title = 'Списки користувачів';

    protected static ?string $modelLabel = 'список';

    protected static ?string $pluralModelLabel = 'списки';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('user.name')
                    ->label('Користувач')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('type')
                    ->label('Тип списку')
                    ->sortable()
                    ->searchable()
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
                SelectFilter::make('type')
                    ->label('Тип списку')
                    ->options(UserListType::class),

                SelectFilter::make('user_id')
                    ->label('Користувач')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
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
                Select::make('user_id')
                    ->label('Користувач')
                    ->relationship('user', 'name')
                    ->required()
                    ->preload()
                    ->searchable()
                    ->prefixIcon('heroicon-o-user'),

                Select::make('type')
                    ->label('Тип списку')
                    ->options(UserListType::class)
                    ->required()
                    ->prefixIcon('heroicon-o-list-bullet'),
            ]);
    }
}

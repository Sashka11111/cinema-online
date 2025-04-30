<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\RelationManagers;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Коментарі';

    protected static ?string $modelLabel = 'коментар';

    protected static ?string $pluralModelLabel = 'коментарі';

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

                TextColumn::make('body')
                    ->label('Текст')
                    ->html()
                    ->limit(50)
                    ->tooltip(fn ($record) => strip_tags($record->body))
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                ToggleColumn::make('is_spoiler')
                    ->label('Спойлер')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('updated_at')
                    ->label('Дата оновлення')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('user')
                    ->label('Користувач')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('Користувач')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Toggle::make('is_spoiler')
                    ->label('Містить спойлер')
                    ->offIcon('heroicon-o-eye-slash')
                    ->onIcon('heroicon-o-eye'),

                RichEditor::make('body')
                    ->label('Текст коментаря')
                    ->required()
                    ->columnSpanFull()
                    ->disableToolbarButtons(['attachFiles']),
            ]);
    }
}

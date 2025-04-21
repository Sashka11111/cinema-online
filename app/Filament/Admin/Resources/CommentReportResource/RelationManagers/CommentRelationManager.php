<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentReportResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CommentRelationManager extends RelationManager
{
    protected static string $relationship = 'comment';

    protected static ?string $title = 'Коментар';

    protected static ?string $modelLabel = 'коментар';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('body')
                    ->label('Текст')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->body)
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('user.name')
                    ->label('Автор')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('translated_type')
                    ->label('Тип контенту')
                    ->toggleable(),

                TextColumn::make('commentable.name')
                    ->label('Контент')
                    ->toggleable(),

                TextColumn::make('is_spoiler')
                    ->label('Спойлер')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d-m-Y H:i')
                    ->toggleable(),
            ])
            ->filters([])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }
}

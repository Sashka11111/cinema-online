<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\MovieResource\RelationManagers;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Selection;

class CommentsRelationManager extends RelationManager
{
    protected static string $relationship = 'comments';

    protected static ?string $title = 'Коментарі';

    protected static ?string $modelLabel = 'коментар';

    protected static ?string $pluralModelLabel = 'коментарі';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('commentable_type')
                    ->label('Тип контенту')
                    ->getStateUsing(fn (Comment $comment) => $comment->translated_type)
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('commentable.name')
                    ->label('Контент')
                    ->sortable()
                    ->formatStateUsing(fn (Comment $comment) => $comment->commentable?->name ?? 'Немає даних')
                    ->toggleable(),

                TextColumn::make('body')
                    ->label('Текст')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->body)
                    ->html(),

                ToggleColumn::make('is_spoiler')
                    ->label('Спойлер')
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('commentable_type')
                    ->label('Тип контенту')
                    ->options(function () {
                        return [
                            Movie::class => (new Comment)->setAttribute('commentable_type', Movie::class)->translated_type,
                            Episode::class => (new Comment)->setAttribute('commentable_type', Episode::class)->translated_type,
                            Selection::class => (new Comment)->setAttribute('commentable_type', Selection::class)->translated_type,
                        ];
                    })
                    ->placeholder('Усі'),

                TernaryFilter::make('is_spoiler')
                    ->label('Спойлер'),
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
}

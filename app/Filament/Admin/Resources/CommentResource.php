<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextArea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\Pages;
use Liamtseva\Cinema\Models\Comment;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $pluralModelLabel = 'Коментарі';

    protected static ?string $navigationGroup = 'Користувачі та взаємодія з контентом';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextArea::make('body')
                    ->label('Текст коментаря')
                    ->required(),
                Checkbox::make('is_spoiler')
                    ->label('Містить спойлер')
                    ->default(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('body')
                    ->label('Текст коментаря')
                    ->searchable()
                    ->sortable(),
                BooleanColumn::make('is_spoiler')
                    ->label('Містить спойлер')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Автор коментаря')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                // додати фільтри, якщо потрібно
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // додати зв'язки, якщо є
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}

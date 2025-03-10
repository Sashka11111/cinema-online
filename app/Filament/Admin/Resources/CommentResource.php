<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\Pages;
use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Selection;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $pluralModelLabel = 'Коментарі';

    protected static ?string $navigationGroup = 'Користувацька активність';

    protected static ?int $navigationSort = 2;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Автор')
                    ->description(fn (Comment $comment): string => $comment->user->email)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('body')
                    ->label('Текст коментаря')
                    ->limit(50)
                    ->tooltip(fn (Comment $comment): string => $comment->body)
                    ->searchable()
                    ->toggleable(),

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

                IconColumn::make('is_spoiler')
                    ->label('Спойлер')
                    ->boolean()
                    ->trueIcon('heroicon-o-eye-slash')
                    ->falseIcon('heroicon-o-eye')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Дата створення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('is_spoiler')
                    ->label('Спойлер')
                    ->options([
                        '1' => 'Так',
                        '0' => 'Ні',
                    ]),

                SelectFilter::make('user')
                    ->label('Автор')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

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

                Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Дата створення від')
                            ->placeholder('Виберіть дату'),
                        DatePicker::make('created_until')
                            ->label('Дата створення до')
                            ->placeholder('Виберіть дату'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($query) => $query->whereDate('created_at', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($query) => $query->whereDate('created_at', '<=', $data['created_until']));
                    }),
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основна інформація')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Select::make('user_id')
                            ->label('Автор')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-user'),

                        Textarea::make('body')
                            ->label('Текст коментаря')
                            ->required()
                            ->rows(5)
                            ->columnSpanFull(),

                        Select::make('commentable_type')
                            ->label('Тип контенту')
                            ->required()
                            ->prefixIcon('heroicon-o-film'),

                        TextInput::make('commentable_id')
                            ->label('ID контенту')
                            ->required()
                            ->numeric()
                            ->prefixIcon('heroicon-o-identification'),

                        Select::make('parent_id')
                            ->label('Батьківський коментар')
                            ->relationship('parent', 'body', fn ($query) => $query->limit(50))
                            ->searchable()
                            ->nullable()
                            ->prefixIcon('heroicon-o-arrow-up-on-square'),
                    ])
                    ->columns(2),

                Section::make('Налаштування')
                    ->icon('clarity-settings-line')
                    ->schema([
                        Toggle::make('is_spoiler')
                            ->label('Позначити як спойлер')
                            ->default(false),
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

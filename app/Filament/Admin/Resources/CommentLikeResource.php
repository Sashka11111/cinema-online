<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentLikeResource\Pages;
use Liamtseva\Cinema\Models\CommentLike;

class CommentLikeResource extends Resource
{
    protected static ?string $model = CommentLike::class;

    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static ?string $pluralModelLabel = 'Реакції на коментарі';

    protected static ?string $navigationLabel = 'Реакції на коментарі';

    protected static ?string $modelLabel = 'реакцію на коментар';

    protected static ?string $navigationGroup = 'Коментарі';

    protected static ?int $navigationSort = 3;

    public static function table(Table $table): Table
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
                    ->description(fn (CommentLike $record): string => $record->user->email ?? '')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('comment.body')
                    ->label('Коментар')
                    ->limit(50)
                    ->tooltip(fn (CommentLike $record): string => $record->comment->body ?? 'Немає даних')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('comment.commentable_type')
                    ->label('Тип контенту')
                    ->formatStateUsing(fn (CommentLike $record) => $record->comment?->translated_type ?? 'Немає даних')
                    ->sortable()
                    ->toggleable(),

                IconColumn::make('is_liked')
                    ->label('Тип реакції')
                    ->boolean()
                    ->trueIcon('heroicon-o-hand-thumb-up')
                    ->falseIcon('heroicon-o-hand-thumb-down')
                    ->trueColor('success')
                    ->falseColor('danger')
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
                SelectFilter::make('is_liked')
                    ->label('Тип реакції')
                    ->options([
                        '1' => 'Лайк',
                        '0' => 'Дизлайк',
                    ]),

                SelectFilter::make('user')
                    ->label('Користувач')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

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
        return $form
            ->schema([
                Section::make('Основна інформація')
                    ->icon('heroicon-o-information-circle')
                    ->schema([
                        Select::make('user_id')
                            ->label('Користувач')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-user'),

                        Select::make('comment_id')
                            ->label('Коментар')
                            ->relationship('comment', 'body')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-chat-bubble-left-ellipsis'),

                        Toggle::make('is_liked')
                            ->label('Тип реакції')
                            ->onIcon('heroicon-o-hand-thumb-up')
                            ->offIcon('heroicon-o-hand-thumb-down')
                            ->onColor('success')
                            ->offColor('danger')
                            ->default(true)
                            ->helperText('Увімкнено = лайк, вимкнено = дизлайк'),

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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCommentLikes::route('/'),
            'create' => Pages\CreateCommentLike::route('/create'),
            'view' => Pages\ViewCommentLike::route('/{record}'),
            'edit' => Pages\EditCommentLike::route('/{record}/edit'),
        ];
    }
}

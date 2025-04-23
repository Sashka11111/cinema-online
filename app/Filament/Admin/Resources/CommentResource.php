<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\MorphToSelect;
use Filament\Forms\Components\RichEditor;
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
use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\Pages;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\RelationManagers\LikesRelationManager;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\RelationManagers\RepliesRelationManager;
use Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\RelationManagers\ReportsRelationManager;
use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Selection;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

    protected static ?string $pluralModelLabel = 'Коментарі';

    protected static ?string $navigationLabel = 'Коментарі';

    protected static ?string $modelLabel = 'коментарі';

    protected static ?string $navigationGroup = 'Коментарі';

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

                TextColumn::make('user.name')
                    ->label('Автор')
                    ->description(fn (Comment $comment): string => $comment->user->email)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('parent.body')
                    ->label('Батьківський коментар')
                    ->default('—')
                    ->tooltip(fn (Comment $comment): ?string => $comment->parent?->body)
                    ->sortable()
                    ->limit(50)
                    ->tooltip(fn (Comment $comment): string => $comment->body)
                    ->toggleable(isToggledHiddenByDefault: true),

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

                TextColumn::make('updated_at')
                    ->label('Дата оновлення')
                    ->dateTime('d-m-Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                        MorphToSelect::make('commentable')
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
                            ]),

                        Select::make('commentable_type')
                            ->label('Тип контенту')
                            ->options([
                                Movie::class => (new Comment)->setAttribute('commentable_type', Movie::class)->translated_type,
                                Episode::class => (new Comment)->setAttribute('commentable_type', Episode::class)->translated_type,
                                Selection::class => (new Comment)->setAttribute('commentable_type', Selection::class)->translated_type,
                            ])
                            ->required()
                            ->prefixIcon('heroicon-o-film'),

                        Select::make('commentable_id')
                            ->label('Контент')
                            ->options(function (callable $get) {
                                $type = $get('commentable_type');
                                if (! $type) {
                                    return [];
                                }

                                return match ($type) {
                                    Movie::class => Movie::pluck('name', 'id')->all(),
                                    Episode::class => Episode::pluck('name', 'id')->all(),
                                    Selection::class => Selection::pluck('name', 'id')->all(),
                                    default => [],
                                };
                            })
                            ->searchable()
                            ->required()
                            ->prefixIcon('heroicon-o-identification'),

                        Select::make('user_id')
                            ->label('Користувач')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->prefixIcon('heroicon-o-user'),

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

                        RichEditor::make('body')
                            ->label('Текст коментаря')
                            ->required()
                            ->columnSpanFull()
                            ->disableToolbarButtons(['attachFiles']),
                    ])
                    ->columns(2),
                Section::make('Налаштування')
                    ->icon('clarity-settings-line')
                    ->schema([
                        Select::make('parent_id')
                            ->label('Батьківський коментар')
                            ->relationship('parent', 'body')
                            ->searchable()
                            ->nullable()
                            ->preload()
                            ->prefixIcon('heroicon-o-arrow-up-on-square')
                            ->helperText('Виберіть, якщо це відповідь на інший коментар'),

                        Toggle::make('is_spoiler')
                            ->label('Позначити як спойлер')
                            ->default(false)
                            ->helperText('Увімкніть, якщо коментар містить спойлери'),
                    ])
                    ->columns(2),
            ]);

    }

    public static function getRelations(): array
    {
        return [
            RepliesRelationManager::class,
            LikesRelationManager::class,
            ReportsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListComments::route('/'),
            'create' => Pages\CreateComment::route('/create'),
            'view' => Pages\ViewComment::route('/{record}'),
            'edit' => Pages\EditComment::route('/{record}/edit'),
        ];
    }
}

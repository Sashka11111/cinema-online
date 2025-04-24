<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique as UniqueRule;

class LikesRelationManager extends RelationManager
{
    protected static string $relationship = 'likes';

    protected static ?string $modelLabel = 'реакцію';

    protected static ?string $pluralModelLabel = 'реакції';

    protected static ?string $title = 'Реакції';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->unique(table: 'comment_likes', column: 'user_id', modifyRuleUsing: function (UniqueRule $rule) {
                        return $rule->where('comment_id', $this->getOwnerRecord()->id);
                    })
                    ->validationMessages([
                        'unique' => 'Цей користувач вже поставив реакцію цьому коментарю.',
                    ]),

                Toggle::make('is_liked')
                    ->label('Тип реакції')
                    ->onIcon('heroicon-o-hand-thumb-up')
                    ->offIcon('heroicon-o-hand-thumb-down')
                    ->onColor('success')
                    ->offColor('danger')
                    ->default(true)
                    ->helperText('Увімкнено = лайк, вимкнено = дизлайк'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Користувач')
                    ->searchable()
                    ->sortable(),

                IconColumn::make('is_liked')
                    ->label('Тип реакції')
                    ->boolean()
                    ->trueIcon('heroicon-o-hand-thumb-up')
                    ->falseIcon('heroicon-o-hand-thumb-down')
                    ->trueColor('success')
                    ->falseColor('danger'),

                TextColumn::make('created_at')
                    ->label('Дата')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
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
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}

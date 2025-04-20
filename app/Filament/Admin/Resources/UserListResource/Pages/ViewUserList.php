<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserListResource\Pages;

use Filament\Actions;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;
use Liamtseva\Cinema\Filament\Admin\Resources\UserListResource;
use Liamtseva\Cinema\Models\UserList;

class ViewUserList extends ViewRecord
{
    protected static ?string $title = 'Перегляд списків користувача';

    protected static string $resource = UserListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Інформація про список')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('Користувач')
                            ->icon('heroicon-m-user'),

                        TextEntry::make('type')
                            ->label('Тип списку')
                            ->badge()
                            ->icon('heroicon-m-list-bullet'),

                        TextEntry::make('listable_type')
                            ->label('Тип елемента')
                            ->formatStateUsing(fn (UserList $record) => $record->translated_type)
                            ->icon('heroicon-m-square-2-stack'),

                        TextEntry::make('listable.name')
                            ->label('Елемент списку')
                            ->icon('heroicon-m-document-text'),

                        TextEntry::make('created_at')
                            ->label('Дата створення')
                            ->dateTime('d.m.Y H:i')
                            ->icon('heroicon-m-calendar'),

                        TextEntry::make('updated_at')
                            ->label('Дата оновлення')
                            ->dateTime('d.m.Y H:i')
                            ->icon('heroicon-m-clock'),
                    ])
                    ->columns(2),
            ]);
    }
}

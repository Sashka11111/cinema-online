<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\UserListResource\Pages;

use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Liamtseva\Cinema\Filament\Admin\Resources\UserListResource;
use Liamtseva\Cinema\Filament\Admin\Resources\UserListResource\Widgets\UserListStatsOverview;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Person;
use Liamtseva\Cinema\Models\Selection;
use Liamtseva\Cinema\Models\Tag;
use Liamtseva\Cinema\Models\UserList;

class ListUserLists extends ListRecords
{
    protected static string $resource = UserListResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Усі списки')
                ->icon('heroicon-o-list-bullet')
                ->badge(fn () => UserList::count())
                ->query(fn ($query) => $query),

            'movies' => Tab::make('Фільми')
                ->icon('heroicon-o-film')
                ->badge(fn () => UserList::where('listable_type', Movie::class)->count())
                ->query(fn ($query) => $query->where('listable_type', Movie::class)),

            'episodes' => Tab::make('Епізоди')
                ->icon('heroicon-o-puzzle-piece')
                ->badge(fn () => UserList::where('listable_type', Episode::class)->count())
                ->query(fn ($query) => $query->where('listable_type', Episode::class)),

            'selections' => Tab::make('Підбірки')
                ->icon('heroicon-o-rectangle-stack')
                ->badge(fn () => UserList::where('listable_type', Selection::class)->count())
                ->query(fn ($query) => $query->where('listable_type', Selection::class)),

            'persons' => Tab::make('Персони')
                ->icon('heroicon-o-user-group')
                ->badge(fn () => UserList::where('listable_type', Person::class)->count())
                ->query(fn ($query) => $query->where('listable_type', Person::class)),

            'recent' => Tab::make('Теги')
                ->icon('heroicon-o-tag')
                ->badge(fn () => UserList::where('listable_type', Tag::class)->count())
                ->query(fn ($query) => $query->where('listable_type', Tag::class)),

            'empty' => Tab::make('Порожні')
                ->icon('heroicon-o-archive-box')
                ->badge(fn () => UserList::doesntHave('listable')->count())
                ->query(fn ($query) => $query->doesntHave('listable')),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            UserListStatsOverview::class,
        ];
    }
}

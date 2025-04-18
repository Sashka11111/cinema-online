<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\SelectionResource\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Liamtseva\Cinema\Models\Selection;

class SelectionsStats extends BaseWidget
{
    protected ?string $heading = 'Статистика підбірок';

    protected function getStats(): array
    {
        $totalSelections = Selection::count();
        $uniqueAuthors = Selection::distinct('user_id')->count('user_id');

        // Отримуємо автора з найбільшою кількістю підбірок
        $topAuthor = Selection::select('user_id', DB::raw('COUNT(*) as selections_count'))
            ->with('user:name,id') // Завантажуємо ім’я користувача
            ->groupBy('user_id')
            ->orderByDesc('selections_count')
            ->first();

        $topAuthorName = $topAuthor ? $topAuthor->user->name : 'Немає даних';
        $topAuthorCount = $topAuthor ? $topAuthor->selections_count : 0;

        return [
            Stat::make('Загальна кількість підбірок', $totalSelections)
                ->description('Всі підбірки в системі')
                ->icon('heroicon-o-rectangle-stack')
                ->color('success'),
            Stat::make('Кількість авторів', $uniqueAuthors)
                ->description('Унікальні автори підбірок')
                ->icon('heroicon-o-users')
                ->color('primary'),
            Stat::make('Найпопулярніший автор', $topAuthorName)
                ->description("Кількість створених підбірок: $topAuthorCount")
                ->icon('heroicon-o-star')
                ->color('warning'),
        ];
    }
}

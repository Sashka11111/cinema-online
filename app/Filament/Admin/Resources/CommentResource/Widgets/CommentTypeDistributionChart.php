<?php

namespace Liamtseva\Cinema\Filament\Admin\Resources\CommentResource\Widgets;

use Filament\Widgets\ChartWidget;
use Liamtseva\Cinema\Models\Comment;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Selection;

class CommentTypeDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Розподіл коментарів за типом контенту';

    protected static string $color = 'primary';

    protected static ?string $maxHeight = '250px';

    protected function getData(): array
    {
        $movieCount = Comment::where('commentable_type', Movie::class)->count();
        $episodeCount = Comment::where('commentable_type', Episode::class)->count();
        $selectionCount = Comment::where('commentable_type', Selection::class)->count();

        return [
            'datasets' => [
                [
                    'data' => [$movieCount, $episodeCount, $selectionCount],
                    'backgroundColor' => ['#3490dc', '#38c172', '#f6993f'], // Кольори для кожного типу
                    'borderColor' => ['#2b6cb0', '#2f855a', '#e07b00'],
                    'borderWidth' => 1,
                ],
            ],
            'labels' => [
                (new Comment)->setAttribute('commentable_type', Movie::class)->translated_type ?? 'Фільми',
                (new Comment)->setAttribute('commentable_type', Episode::class)->translated_type ?? 'Епізоди',
                (new Comment)->setAttribute('commentable_type', Selection::class)->translated_type ?? 'Підбірки',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'pie'; // Тип графіка — кругова діаграма
    }
}

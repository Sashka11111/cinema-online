<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Illuminate\Support\Facades\Cache;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Models\Movie;
use Livewire\Component;

class TrendingMoviesComponent extends Component
{
    public string $contentType = 'all';

    public function mount($contentType = 'all')
    {
        $this->contentType = $contentType;
    }

    public function render()
    {
        // Кешуємо результати на 1 годину
        $trendingMovies = Cache::remember("trending_movies_{$this->contentType}", 3600, function () {
            $query = Movie::query()
                ->where('is_published', true)
                ->select([
                    'id', 'name', 'slug', 'poster', 'imdb_score',
                    'status', 'restricted_rating', 'kind',
                ]);

            // Фільтруємо за типом контенту, якщо він вказаний
            if ($this->contentType !== 'all') {
                // Перетворюємо рядкове значення на відповідне значення з переліку Kind
                $kindValue = $this->getContentKind()->value;
                $query->where('kind', $kindValue);
            }

            return $query->orderBy('imdb_score', 'desc')
                ->limit(10)
                ->get();
        });

        return view('livewire.components.trending-movies-component', [
            'trendingMovies' => $trendingMovies,
        ]);
    }

    private function getContentKind(): Kind
    {
        return match ($this->contentType) {
            'movies' => Kind::MOVIE,
            'series' => Kind::TV_SERIES,
            'cartoons' => Kind::ANIMATED_MOVIE,
            'cartoon_series' => Kind::ANIMATED_SERIES,
            'anime' => Kind::ANIME,
            default => Kind::MOVIE
        };
    }
}

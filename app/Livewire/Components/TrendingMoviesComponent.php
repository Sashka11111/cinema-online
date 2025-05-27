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
        $trendingMovies = Cache::remember("trending_movies_{$this->contentType}", 3600, function () {
            $query = Movie::query()
                ->select([
                    'id', 'name', 'slug', 'poster', 'imdb_score',
                    'status', 'restricted_rating', 'kind',
                ]);

            if ($this->contentType !== 'all') {
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

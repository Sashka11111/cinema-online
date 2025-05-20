<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Models\Movie;
use Livewire\Component;

class MovieSliderComponent extends Component
{
    public string $type = 'latest';

    public function mount($type = 'latest')
    {
        $this->type = $type;
    }

    public function render()
    {
        // Отримуємо фільми в залежності від типу слайдера
        $movies = Cache::remember("movies_{$this->type}", 3600, function () {
            $query = Movie::query()->where('is_published', true);

            switch ($this->type) {
                case 'latest':
                    $query->latest('first_air_date');
                    break;
                case 'popular':
                    // Якщо є поле views, використовуємо його, інакше використовуємо imdb_score
                    if (Schema::hasColumn('movies', 'views')) {
                        $query->orderBy('views', 'desc');
                    } else {
                        $query->orderBy('imdb_score', 'desc');
                    }
                    break;
                case 'series':
                    $query->where('kind', Kind::TV_SERIES->value)
                        ->orderBy('imdb_score', 'desc');
                    break;
                case 'top_rated':
                    $query->orderBy('imdb_score', 'desc');
                    break;
                default:
                    $query->latest();
            }

            return $query->limit(10)->get();
        });

        return view('livewire.components.movie-slider-component', [
            'movies' => $movies,
        ]);
    }
}

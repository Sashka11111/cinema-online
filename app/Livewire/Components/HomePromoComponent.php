<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Illuminate\Support\Facades\Cache;
use Liamtseva\Cinema\Models\Movie;
use Livewire\Component;

class HomePromoComponent extends Component
{
    public function render()
    {
        $featuredMovies = Cache::remember('home_featured_movies', 3600, function () {
            return Movie::query()
                ->where('is_published', true)
                ->orderBy('imdb_score', 'desc')
                ->orderBy('created_at', 'desc')
                ->select([
                    'id', 'name', 'slug', 'poster', 'image_name', 'imdb_score',
                    'status', 'restricted_rating', 'kind', 'first_air_date', 'description',
                ])
                ->limit(7)
                ->get();
        });

        return view('livewire.components.home-promo-component', [
            'featuredMovies' => $featuredMovies,
        ]);
    }
}

<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Illuminate\Support\Facades\Cache;
use Liamtseva\Cinema\Models\Tag;
use Livewire\Component;

class GenresGridComponent extends Component
{
    public function render()
    {
        // Отримуємо жанри з кешу
        $genres = Cache::remember('home_page_genres', 86400, function () {
            return Tag::where('is_genre', true)
                ->withCount('movies')
                ->limit(8)
                ->get();
        });

        return view('livewire.components.genres-grid-component', [
            'genres' => $genres,
        ]);
    }
}

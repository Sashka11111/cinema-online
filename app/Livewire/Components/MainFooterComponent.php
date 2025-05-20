<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Illuminate\Support\Facades\Cache;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Models\Movie;
use Livewire\Component;

class MainFooterComponent extends Component
{
    public function render()
    {
        // Кешуємо фільми по типах контенту на 1 годину
        $contentTypesWithMovies = Cache::remember('footer_content_types_with_movies', 3600, function () {
            // Визначаємо всі типи контенту з енаму Kind
            $contentTypes = [
                Kind::MOVIE->value => 'Фільми',
                Kind::TV_SERIES->value => 'Серіали',
                Kind::ANIMATED_MOVIE->value => 'Мультфільми',
                Kind::ANIMATED_SERIES->value => 'Мультсеріали',
                Kind::ANIME->value => 'Аніме',
            ];

            $result = [];

            foreach ($contentTypes as $kind => $label) {
                // Для кожного типу контенту отримуємо 3 останніх фільми
                $movies = Movie::query()
                    ->where('is_published', true)
                    ->where('kind', $kind)
                    ->latest('created_at')
                    ->select(['id', 'name', 'slug'])
                    ->limit(3)
                    ->get();

                // Додаємо до результату тільки якщо є фільми
                if ($movies->isNotEmpty()) {
                    $result[$label] = $movies;
                }
            }

            return $result;
        });

        return view('livewire.components.main-footer-component', compact('contentTypesWithMovies'));
    }
}

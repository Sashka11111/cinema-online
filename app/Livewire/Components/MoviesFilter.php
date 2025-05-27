<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Liamtseva\Cinema\Enums\Country;
use Liamtseva\Cinema\Enums\Period;
use Liamtseva\Cinema\Enums\RestrictedRating;
use Liamtseva\Cinema\Enums\Source;
use Liamtseva\Cinema\Enums\Status;
use Liamtseva\Cinema\Models\SearchHistory;
use Liamtseva\Cinema\Models\Studio;
use Liamtseva\Cinema\Models\Tag;
use Livewire\Component;

class MoviesFilter extends Component
{
    // Дані для селектів (завантажуються в компоненті)
    public Collection $statuses;
    public Collection $periods;
    public Collection $studios;
    public Collection $years;
    public Collection $genres;
    public Collection $ratings;
    public Collection $countries;
    public Collection $sources;

    public string $contentType = 'movies';

    // Поточні значення фільтрів
    public string $search = '';
    public string $status = '';
    public string $period = '';
    public string $studio = '';
    public string $year = '';
    public string $genre = '';
    public string $rating = '';
    public string $duration = '';
    public string $country = '';
    public string $source = '';
    public string $imdbScore = '';
    public bool $hasEpisodes = false;

    public function mount($contentType = 'movies'): void
    {
        $this->contentType = $contentType;

        // Завантажуємо всі дані для селектів
        $this->loadFilterData();
    }

    private function loadFilterData(): void
    {
        // Статуси - це enum
        $this->statuses = collect(Status::cases());

        // Періоди - це enum
        $this->periods = collect(Period::cases());

        // Студії - завантажуємо з БД з кешуванням
        $this->studios = Cache::remember('movie_studios', 86400, function () {
            return Studio::orderBy('name')->get();
        });

        // Роки - генеруємо діапазон
        $currentYear = now()->year;
        $this->years = collect(range($currentYear, $currentYear - 50));

        // Жанри - завантажуємо з БД з кешуванням
        $this->genres = Cache::remember('movie_genres', 86400, function () {
            return Tag::where('is_genre', true)->orderBy('name')->get();
        });

        // Рейтинги - це enum
        $this->ratings = collect(RestrictedRating::cases());

        // Країни - це enum
        $this->countries = collect(Country::cases());

        // Джерела - це enum
        $this->sources = collect(Source::cases());
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->status = '';
        $this->period = '';
        $this->studio = '';
        $this->year = '';
        $this->genre = '';
        $this->rating = '';
        $this->duration = '';
        $this->country = '';
        $this->source = '';
        $this->imdbScore = '';
        $this->hasEpisodes = false;

        // Відправляємо подію з порожніми фільтрами
        $this->dispatch('filter-changed', $this->getFilterData());
    }

    public function applyFilters(): void
    {
        // Зберігаємо пошуковий запит в історію, якщо користувач авторизований
        if (!empty($this->search) && auth()->check()) {
            SearchHistory::create([
                'user_id' => auth()->id(),
                'query' => $this->search,
            ]);
        }

        // Відправляємо подію з поточними фільтрами
        $this->dispatch('filter-changed', $this->getFilterData());
    }

    private function getFilterData(): array
    {
        return [
            'search' => $this->search,
            'status' => $this->status,
            'period' => $this->period,
            'studio' => $this->studio,
            'year' => $this->year,
            'genre' => $this->genre,
            'rating' => $this->rating,
            'duration' => $this->duration,
            'country' => $this->country,
            'source' => $this->source,
            'imdbScore' => $this->imdbScore,
            'hasEpisodes' => $this->hasEpisodes,
        ];
    }

    public function render()
    {
        return view('livewire.components.movies-filter');
    }
}

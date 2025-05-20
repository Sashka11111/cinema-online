<?php

namespace Liamtseva\Cinema\Livewire\Components;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Liamtseva\Cinema\Enums\Country;
use Liamtseva\Cinema\Enums\RestrictedRating;
use Liamtseva\Cinema\Enums\Source;
use Liamtseva\Cinema\Models\SearchHistory;
use Liamtseva\Cinema\Models\Tag;
use Livewire\Component;

class MoviesFilter extends Component
{
    public Collection $statuses;

    public Collection $periods;

    public Collection $studios;

    public Collection $years;

    public Collection $genres;

    public Collection $ratings;

    public Collection $countries;

    public Collection $sources;

    public string $sortField;

    public string $sortDirection;

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

    public ?string $imdbScoreMin = null;

    public bool $hasEpisodes = false;

    public string $contentType = 'movies';

    // Використовуємо дебаунс для зменшення кількості запитів
    protected $updatesQueryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'period' => ['except' => ''],
        'studio' => ['except' => ''],
        'year' => ['except' => ''],
        'genre' => ['except' => ''],
        'rating' => ['except' => ''],
        'duration' => ['except' => ''],
        'country' => ['except' => ''],
        'source' => ['except' => ''],
        'imdbScoreMin' => ['except' => ''],
        'hasEpisodes' => ['except' => false],
    ];

    public function mount($statuses, $periods, $studios, $years, $sortField, $sortDirection, $contentType = 'movies'): void
    {
        $this->statuses = $statuses;
        $this->periods = $periods;
        $this->studios = $studios;
        $this->years = $years;
        $this->sortField = $sortField;
        $this->sortDirection = $sortDirection;
        $this->contentType = $contentType;

        // Кешуємо жанри на 24 години
        $this->genres = Cache::remember('movie_genres', 86400, function () {
            return Tag::where('is_genre', true)->orderBy('name')->get();
        });

        // Рейтинги - це enum, тому їх не потрібно завантажувати з БД
        $this->ratings = collect(RestrictedRating::cases());

        // Країни - це enum
        $this->countries = collect(Country::cases());

        // Джерела - це enum
        $this->sources = collect(Source::cases());
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->dispatch('sort-changed', [
            'field' => $this->sortField,
            'direction' => $this->sortDirection,
        ]);
    }

    // Оновлений метод для обробки змін у фільтрах
    public function updated($name, $value)
    {
        // Якщо оновлюється поле пошуку і воно не порожнє, зберігаємо в історію пошуку
        if ($name === 'search' && ! empty($value)) {
            // Зберігаємо пошуковий запит в історію, якщо користувач авторизований
            if (auth()->check()) {
                // Припускаємо, що у вас є модель SearchHistory
                SearchHistory::create([
                    'user_id' => auth()->id(),
                    'query' => $value,
                    'content_type' => $this->contentType,
                ]);
            }
        }

        // Диспетчеризуємо подію з усіма поточними фільтрами
        // Триграмний пошук буде використано в MoviesPage через метод search() у MovieQueryBuilder
        $this->dispatch('filter-changed', [
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
            'imdbScoreMin' => $this->imdbScoreMin,
            'hasEpisodes' => $this->hasEpisodes,
        ]);
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
        $this->imdbScoreMin = null;
        $this->hasEpisodes = false;

        $this->dispatch('filters-reset');
    }

    public function applyFilters(): void
    {
        $this->dispatch('filters-applied', [
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
            'imdbScoreMin' => $this->imdbScoreMin,
            'hasEpisodes' => $this->hasEpisodes,
        ]);
    }

    public function render()
    {
        return view('livewire.components.movies-filter');
    }
}

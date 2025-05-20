<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Database\Eloquent\Builder;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Enums\Period;
use Liamtseva\Cinema\Enums\Status;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Scopes\PublishedScope;
use Liamtseva\Cinema\Models\Studio;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MoviesPage extends Component
{
    use WithPagination;

    // Тип контенту
    #[Url(except: '')]
    public $contentType = 'movies';

    // Додаємо властивість page для пагінації
    #[Url]
    public $page = 1;

    // Фільтри
    #[Url(except: '')]
    public $search = '';

    #[Url(except: '')]
    public $status = '';

    #[Url(except: '')]
    public $period = '';

    #[Url(except: '')]
    public $studio = '';

    #[Url(except: '')]
    public $year = '';

    // Додаємо нові фільтри, але не використовуємо ті, яких немає в БД
    #[Url(except: '')]
    public $genre = '';

    #[Url(except: '')]
    public $rating = '';

    #[Url(except: '')]
    public $duration = '';

    // Сортування
    #[Url(except: 'created_at')]
    public $sortField = 'created_at';

    #[Url(except: 'desc')]
    public $sortDirection = 'desc';

    // Дані для відображення
    public $trendingMovies;

    // Параметри URL (для Livewire 2 - можна видалити, якщо використовуєте Livewire 3)
    protected $queryString = [
        'contentType' => ['except' => ''],
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'period' => ['except' => ''],
        'studio' => ['except' => ''],
        'year' => ['except' => ''],
        'genre' => ['except' => ''],
        'rating' => ['except' => ''],
        'duration' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

    protected $listeners = [
        'filter-changed' => 'handleFilterChange',
        'sort-changed' => 'handleSortChange',
        'filters-reset' => 'resetAllFilters',
        'filters-applied' => 'applyAllFilters',
    ];

    // Оновлюємо методи для нових фільтрів
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedPeriod()
    {
        $this->resetPage();
    }

    public function updatedStudio()
    {
        $this->resetPage();
    }

    public function updatedYear()
    {
        $this->resetPage();
    }

    public function updatedGenre()
    {
        $this->resetPage();
    }

    public function updatedRating()
    {
        $this->resetPage();
    }

    public function updatedDuration()
    {
        $this->resetPage();
    }

    public function updatedContentType()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getMoviesProperty()
    {
        // Створюємо унікальний ключ для кешу
        $cacheKey = 'movies_page_'.md5(json_encode([
            $this->contentType,
            $this->search,
            $this->status,
            $this->period,
            $this->studio,
            $this->year,
            $this->genre,
            $this->rating,
            $this->duration,
            $this->sortField,
            $this->sortDirection,
            $this->page,
        ]));

        // Використовуємо try-catch для обробки помилок
        try {
            return cache()->remember($cacheKey, 60, function () {
                return $this->moviesQuery()->paginate(10);
            });
        } catch (\Exception $e) {
            // Логуємо помилку
            \Log::error('Error in MoviesPage::getMoviesProperty: '.$e->getMessage());

            // Повертаємо порожню колекцію
            return new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), 0, 20, $this->page
            );
        }
    }

    public function getStatusesProperty()
    {
        return collect(Status::cases());
    }

    public function getPeriodsProperty()
    {
        return collect(Period::cases());
    }

    public function getStudiosProperty()
    {
        return Studio::orderBy('name')->get();
    }

    public function getYearsProperty()
    {
        $currentYear = now()->year;

        return collect(range($currentYear, $currentYear - 50));
    }

    public function getPageTitleProperty()
    {
        return match ($this->contentType) {
            'movies' => 'Фільми',
            'series' => 'Серіали',
            'cartoons' => 'Мультфільми',
            'cartoon_series' => 'Мультсеріали',
            'anime' => 'Аніме',
            default => 'Фільми'
        };
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

    private function moviesQuery(): Builder
    {
        $query = Movie::query()
            ->withoutGlobalScopes([PublishedScope::class])
            ->where('is_published', true)
            ->where('kind', $this->getContentKind());

        // Використовуємо індекси для швидшого пошуку
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhereRaw("searchable @@ plainto_tsquery('ukrainian', ?)", [$this->search]);
            });
        }

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->period) {
            $query->where('period', $this->period);
        }

        if ($this->studio) {
            $query->where('studio_id', $this->studio);
        }

        if ($this->year) {
            $query->whereYear('first_air_date', $this->year);
        }

        // Додаємо нові фільтри
        if ($this->genre) {
            $query->whereHas('tags', function ($q) {
                $q->where('tags.id', $this->genre);
            });
        }

        if ($this->rating) {
            $query->where('restricted_rating', $this->rating);
        }

        if ($this->duration) {
            switch ($this->duration) {
                case 'short':
                    $query->where('duration', '<', 90);
                    break;
                case 'medium':
                    $query->whereBetween('duration', [90, 120]);
                    break;
                case 'long':
                    $query->where('duration', '>', 120);
                    break;
            }
        }

        // Сортування
        $query->orderBy($this->sortField, $this->sortDirection);

        // Використовуємо eager loading для зменшення кількості запитів
        return $query->with(['studio', 'tags']);
    }

    public function mount($contentType = null)
    {
        // Перевіряємо, чи передано параметр contentType
        if ($contentType) {
            $this->contentType = $contentType;
        }

        // Перевіряємо, чи є contentType в списку допустимих значень
        $validContentTypes = ['movies', 'series', 'cartoons', 'cartoon_series', 'anime'];
        if (! in_array($this->contentType, $validContentTypes)) {
            $this->contentType = 'movies'; // За замовчуванням
        }

        // Отримуємо фільми в тренді для поточного типу контенту
        $this->trendingMovies = Movie::query()
            ->withoutGlobalScopes([PublishedScope::class])
            ->where('is_published', true)
            ->where('kind', $this->getContentKind())
            ->select([
                'id', 'name', 'slug', 'poster', 'imdb_score',
                'status', 'restricted_rating', 'duration',
            ])
            ->orderBy('imdb_score', 'desc') // Сортуємо за рейтингом
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.movies-page', [
            'movies' => $this->movies,
            'pageTitle' => $this->pageTitle,
            'contentType' => $this->contentType,
        ]);
    }

    public function handleFilterChange($filter)
    {
        foreach ($filter as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        $this->resetPage();
    }

    public function handleSortChange($sort)
    {
        $this->sortField = $sort['field'];
        $this->sortDirection = $sort['direction'];
    }

    // Додаємо нові методи для обробки подій
    public function resetAllFilters()
    {
        $this->search = '';
        $this->status = '';
        $this->period = '';
        $this->studio = '';
        $this->year = '';
        $this->genre = '';
        $this->rating = '';
        $this->duration = '';
        $this->resetPage();
    }

    public function applyAllFilters($filters)
    {
        dd($filters);
        foreach ($filters as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        $this->resetPage();
    }

    // Метод resetPage для Livewire 3
    public function resetPage()
    {
        $this->page = 1;
    }
}

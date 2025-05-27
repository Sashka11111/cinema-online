<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Database\Eloquent\Builder;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Models\Movie;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class MoviesPage extends Component
{
    use WithPagination;

    // Встановлюємо тему пагінації
    protected $paginationTheme = 'bootstrap';

    // Додаємо властивість page для пагінації
    #[Url]
    public $page = 1;

    // Тип контенту
    public $contentType = 'movies';

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

    #[Url(except: '')]
    public $genre = '';

    #[Url(except: '')]
    public $rating = '';

    #[Url(except: '')]
    public $duration = '';

    #[Url(except: '')]
    public $country = '';

    #[Url(except: '')]
    public $source = '';

    #[Url(except: '')]
    public $imdbScoreMin = '';

    // Сортування
    #[Url(except: 'created_at')]
    public $sortField = 'created_at';

    #[Url(except: 'desc')]
    public $sortDirection = 'desc';

    // Дані для відображення
    public $trendingMovies;

    protected $listeners = [
        'filter-changed' => 'handleFilterChange',
    ];

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
            $this->country,
            $this->source,
            $this->imdbScoreMin,
            $this->sortField,
            $this->sortDirection,
            $this->page,
        ]));

        // Використовуємо try-catch для обробки помилок
        try {
            return cache()->remember($cacheKey, 60, function () {
                return $this->moviesQuery()->paginate(20, ['*'], 'page', $this->page);
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
            ->where('kind', $this->getContentKind());

        // Використовуємо метод search() з MovieQueryBuilder
        if (!empty($this->search)) {
            $query->search($this->search);
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

        if ($this->country) {
            $query->where('country_code', $this->country);
        }

        if ($this->source) {
            $query->where('source', $this->source);
        }

        if ($this->imdbScoreMin) {
            $query->where('imdb_score', '>=', $this->imdbScoreMin);
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

    public function handleFilterChange($filters)
    {
        foreach ($filters as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        $this->resetPage();
    }

    // Метод resetPage для Livewire 3
    public function resetPage($pageName = 'page')
    {
        $this->page = 1;
    }

    // Метод для переходу на конкретну сторінку
    public function gotoPage($page, $pageName = 'page')
    {
        $this->page = $page;
    }
}

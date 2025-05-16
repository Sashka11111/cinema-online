<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Database\Eloquent\Builder;
use Liamtseva\Cinema\Enums\Kind;
use Liamtseva\Cinema\Enums\Period;
use Liamtseva\Cinema\Enums\Status;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Scopes\PublishedScope;
use Livewire\Component;
use Livewire\WithPagination;

class MoviesPage extends Component
{
    use WithPagination;

    // Фільтри
    public $search = '';

    public $status = '';

    public $period = '';

    public $studio = '';

    public $year = '';

    // Сортування
    public $sortField = 'created_at';

    public $sortDirection = 'desc';

    // Дані для відображення
    public $trendingMovies;

    // Параметри URL
    protected $queryString = [
        'search' => ['except' => ''],
        'status' => ['except' => ''],
        'period' => ['except' => ''],
        'studio' => ['except' => ''],
        'year' => ['except' => ''],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'page' => ['except' => 1],
    ];

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
        return $this->moviesQuery()
            ->paginate(12);
    }

    public function getStatusesProperty()
    {
        return Status::cases();
    }

    public function getPeriodsProperty()
    {
        return Period::cases();
    }

    public function getStudiosProperty()
    {
        return \Liamtseva\Cinema\Models\Studio::orderBy('name')->get();
    }

    public function getYearsProperty()
    {
        $currentYear = date('Y');
        $years = [];
        for ($i = $currentYear; $i >= $currentYear - 50; $i--) {
            $years[] = $i;
        }

        return $years;
    }

    private function moviesQuery(): Builder
    {
        $query = Movie::query()
            ->where('is_published', true)
            ->where('kind', Kind::MOVIE) // Відображаємо лише фільми
            ->with(['studio']);

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('original_name', 'like', '%'.$this->search.'%')
                    ->orWhere('description', 'like', '%'.$this->search.'%');
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

        return $query->orderBy($this->sortField, $this->sortDirection);
    }

    public function mount()
    {
        // Отримуємо фільми в тренді
        $this->trendingMovies = Movie::query()
            ->withoutGlobalScopes([PublishedScope::class])
            ->where('is_published', true)
            ->where('kind', Kind::MOVIE) // Лише фільми
            ->orderBy('imdb_score', 'desc') // Сортуємо за рейтингом
            ->limit(10)
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.movies-page', [
            'movies' => $this->movies,
        ]);
    }
}

<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Liamtseva\Cinema\Models\Selection;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SelectionsPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Url]
    public $page = 1;

    #[Url(except: '')]
    public $search = '';

    #[Url(except: '')]
    public $category = '';

    #[Url(except: 'created_at')]
    public $sortField = 'created_at';

    #[Url(except: 'desc')]
    public $sortDirection = 'desc';

    public function getSelectionsProperty()
    {
        $query = Selection::with(['user', 'movies']); // Додаємо eager loading для user та movies

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('description', 'like', "%{$this->search}%");
            });
        }

        if ($this->category) {
            // Перевіряємо, чи є категорія в списку допустимих
            if (array_key_exists($this->category, $this->categories)) {
                // Залежно від категорії, застосовуємо різні фільтри
                switch ($this->category) {
                    case 'trending':
                        $query->orderByPopularity(); // Використовуємо метод з QueryBuilder
                        break;
                    case 'new':
                        $query->orderBy('created_at', 'desc');
                        break;
                    case 'thematic':
                        // Логіка для тематичних підбірок
                        break;
                    case 'genre':
                        // Підбірки за жанрами
                        $query->whereHas('movies', function ($q) {
                            $q->whereHas('tags', function ($q2) {
                                // Тут можна додати додаткову логіку фільтрації за жанрами
                            });
                        });
                        break;
                    case 'actor':
                        // Підбірки за акторами
                        $query->whereHas('persons', function ($q) {
                            $q->where('type', 'actor');
                        });
                        break;
                    case 'director':
                        // Підбірки за режисерами
                        $query->whereHas('persons', function ($q) {
                            $q->where('type', 'director');
                        });
                        break;
                }
            }
        }

        // Застосовуємо сортування
        if ($this->sortField === 'popularity') {
            $query->orderByPopularity();
        } elseif ($this->sortField === 'items_count') {
            $query->orderByItemsCount();
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query->paginate(12, ['*'], 'page', $this->page);
    }

    public function getCategoriesProperty()
    {
        return [
            'trending' => 'Популярні',
            'new' => 'Нові',
            'thematic' => 'Тематичні',
            'genre' => 'За жанрами',
            'actor' => 'За акторами',
            'director' => 'За режисерами',
        ];
    }

    public function resetPage($pageName = 'page')
    {
        $this->page = 1;
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->page = $page;
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
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

    public function render()
    {
        return view('livewire.pages.selections-page', [
            'selections' => $this->selections,
            'categories' => $this->categories,
        ]);
    }
}

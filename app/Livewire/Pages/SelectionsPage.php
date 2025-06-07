<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Liamtseva\Cinema\Models\Selection;
use Liamtseva\Cinema\Models\User;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class SelectionsPage extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    #[Url]
    public int $page = 1;

    #[Url(except: '')]
    public string $search = '';

    #[Url(except: '')]
    public string $author = '';

    #[Url(except: 'created_at')]
    public string $sortField = 'created_at';

    #[Url(except: 'desc')]
    public string $sortDirection = 'desc';

    public array $authors = [];

    public string $tempAuthor = '';

    protected $listeners = ['filter-changed' => 'handleFilterChange'];

    /**
     * Automatically save search query when it changes.
     */
    public function updatedSearch(): void
    {
        // Зберігаємо пошуковий запит в історію, якщо користувач авторизований
        if (! empty($this->search) && auth()->check()) {
            \Liamtseva\Cinema\Models\SearchHistory::create([
                'user_id' => auth()->id(),
                'query' => $this->search,
            ]);
        }

        $this->resetPage();
    }

    public function mount(): void
    {
        $this->authors = User::whereHas('selections')->pluck('name', 'id')->toArray();

        $this->tempAuthor = $this->author;
    }

    /**
     * Changes the sorting field and direction.
     */
    public function sortBy(string $field): void
    {
        $this->sortDirection = ($this->sortField === $field && $this->sortDirection === 'asc') ? 'desc' : 'asc';
        $this->sortField = $field;
        $this->resetPage();
    }

    /**
     * Retrieves paginated selections with applied filters and sorting.
     */
    public function getSelectionsProperty(): LengthAwarePaginator
    {
        $cacheKey = $this->generateCacheKey();

        // Очищаємо кеш при кожному запиті для тестування
        Cache::forget($cacheKey);

        try {
            return Cache::remember($cacheKey, 60, fn () => $this->selectionsQuery()->paginate(12, ['*'], 'page', $this->page));
        } catch (\Exception $e) {
            \Log::error('Error fetching selections: '.$e->getMessage());

            return new LengthAwarePaginator(collect(), 0, 12, $this->page);
        }
    }

    /**
     * Builds the query for selections with filters and sorting.
     */
    protected function selectionsQuery(): Builder
    {
        // Базовий запит з необхідними зв'язками
        $query = Selection::query()->with([
            'user',
            'movies' => function ($query) {
                $query->select('id', 'name', 'poster');
            },
            'persons' => function ($query) {
                $query->select('id', 'name');
            },
            'episodes' => function ($query) {
                $query->select('id', 'name');
            },
        ]);

        // Додаємо підрахунок кількості елементів для відображення
        $query->withCount([
            'movies as movies_count' => fn ($q) => $q->where('is_published', true),
            'persons',
            'episodes',
        ]);

        // Використовуємо повнотекстовий пошук
        if (! empty($this->search)) {
            $query->search($this->search);
        }

        // Фільтрація за автором
        if ($this->author !== '') {
            $query->byUser($this->author);
        }

        // Сортування
        if ($this->sortField === 'comments_count') {
            $query->withCount('comments')->orderBy('comments_count', $this->sortDirection);
        } else {
            $query->orderBy($this->sortField, $this->sortDirection);
        }

        return $query;
    }

    /**
     * Applies temporary filter values and resets pagination.
     */
    public function applyFilters(): void
    {
        $this->author = $this->tempAuthor;
        $this->resetPage();
    }

    /**
     * Resets all filters and sorting to default values.
     */
    public function resetFilters(): void
    {
        $this->fill([
            'search' => '',
            'author' => '',
            'sortField' => 'created_at',
            'sortDirection' => 'desc',
            'tempAuthor' => '',
        ]);
        $this->resetPage();
    }

    /**
     * Retrieves the featured selection, cached for 1 hour.
     */
    public function getFeaturedSelectionProperty(): ?Selection
    {
        // Очищаємо кеш для тестування
        Cache::forget('featured_selection');

        return Cache::remember('featured_selection', 3600, function () {
            // Використовуємо CTE (Common Table Expression) для вирішення проблеми з ORDER BY
            $query = "
                WITH selection_counts AS (
                    SELECT
                        selections.*,
                        (SELECT COUNT(*) FROM movies
                         INNER JOIN selectionables ON movies.id = selectionables.selectionable_id
                         WHERE selections.id = selectionables.selection_id
                         AND selectionables.selectionable_type = 'Liamtseva\\Cinema\\Models\\Movie'
                         AND movies.is_published = true) AS movies_count,
                        (SELECT COUNT(*) FROM people
                         INNER JOIN selectionables ON people.id = selectionables.selectionable_id
                         WHERE selections.id = selectionables.selection_id
                         AND selectionables.selectionable_type = 'Liamtseva\\Cinema\\Models\\Person') AS persons_count,
                        (SELECT COUNT(*) FROM episodes
                         INNER JOIN selectionables ON episodes.id = selectionables.selectionable_id
                         WHERE selections.id = selectionables.selection_id
                         AND selectionables.selectionable_type = 'Liamtseva\\Cinema\\Models\\Episode') AS episodes_count,
                        (SELECT COUNT(*) FROM user_lists
                         WHERE selections.id = user_lists.listable_id
                         AND user_lists.listable_type = 'Liamtseva\\Cinema\\Models\\Selection') AS user_lists_count
                    FROM selections
                )
                SELECT * FROM selection_counts
                ORDER BY user_lists_count DESC, (movies_count + persons_count + episodes_count) DESC
                LIMIT 1
            ";

            $selectionId = DB::selectOne($query)->id;

            return Selection::with(['user', 'movies' => fn ($query) => $query->select('id', 'name', 'poster')->limit(4)])
                ->withCount([
                    'movies as movies_count' => fn ($q) => $q->where('is_published', true),
                    'persons',
                    'episodes',
                    'userLists',
                ])
                ->find($selectionId);
        });
    }

    /**
     * Handles external filter change events.
     */
    public function handleFilterChange(): void
    {
        $this->resetPage();
    }

    /**
     * Resets pagination to the first page.
     */
    public function resetPage(string $pageName = 'page'): void
    {
        $this->page = 1;
    }

    /**
     * Navigates to a specific page.
     */
    public function gotoPage(int $page, string $pageName = 'page'): void
    {
        $this->page = $page;
    }

    /**
     * Navigates to the next page.
     */
    public function nextPage(string $pageName = 'page'): void
    {
        $this->page++;
    }

    /**
     * Navigates to the previous page if not on the first page.
     */
    public function previousPage(string $pageName = 'page'): void
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    /**
     * Generates a cache key based on filter and sorting parameters.
     */
    protected function generateCacheKey(): string
    {
        return 'selections_page_'.md5(json_encode([
            $this->search,
            $this->author,
            $this->sortField,
            $this->sortDirection,
            $this->page,
        ]));
    }

    /**
     * Gets available sort options.
     */
    public function getSortOptionsProperty(): array
    {
        return [
            'created_at' => 'Дата створення',
            'name' => 'Назва',
            'comments_count' => 'Кількість коментарів',
        ];
    }

    /**
     * Checks if any filters are active.
     */
    public function getHasActiveFiltersProperty(): bool
    {
        return $this->search !== '' ||
            $this->author !== '';
    }

    /**
     * Renders the selections page view.
     */
    public function render()
    {
        return view('livewire.pages.selections-page', [
            'selections' => $this->selections,
            'authors' => $this->authors,
            'featuredSelection' => $this->featuredSelection,
            'sortOptions' => $this->sortOptions,
            'hasActiveFilters' => $this->hasActiveFilters,
        ]);
    }
}

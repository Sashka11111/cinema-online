<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
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
    public $contentType = '';

    #[Url(except: '')]
    public $minItems = '';

    #[Url(except: '')]
    public $author = '';

    #[Url(except: 'created_at')]
    public $sortField = 'created_at';

    #[Url(except: 'desc')]
    public $sortDirection = 'desc';

    public $authors = [];

    // Temp properties for apply button
    public $tempSearch = '';

    public $tempContentType = '';

    public $tempMinItems = '';

    public $tempAuthor = '';

    protected $listeners = [
        'filter-changed' => 'handleFilterChange',
    ];

    public function mount()
    {
        $this->getFeaturedSelectionProperty();
        $this->authors = \Liamtseva\Cinema\Models\User::whereHas('selections')->pluck('name', 'id')->toArray();
        // Sync temp properties
        $this->tempSearch = $this->search;
        $this->tempContentType = $this->contentType;
        $this->tempMinItems = $this->minItems;
        $this->tempAuthor = $this->author;
    }

    public function getSelectionsProperty()
    {
        $cacheKey = 'selections_page_'.md5(json_encode([
            $this->search,
            $this->contentType,
            $this->minItems,
            $this->author,
            $this->sortField,
            $this->sortDirection,
            $this->page,
        ]));
        try {
            return cache()->remember($cacheKey, 60, function () {
                return $this->selectionsQuery()->paginate(12, ['*'], 'page', $this->page);
            });
        } catch (\Exception $e) {
            \Log::error('Error in SelectionsPage::getSelectionsProperty: '.$e->getMessage());

            return new \Illuminate\Pagination\LengthAwarePaginator(collect([]), 0, 12, $this->page);
        }
    }

    private function selectionsQuery(): Builder
    {
        $query = Selection::query();

        if (! empty($this->search)) {
            $query = $query->search($this->search);
        } else {
            $query = $query->with(['user', 'movies']);
        }

        if ($this->contentType) {
            switch ($this->contentType) {
                case 'movies':
                    $query->whereHas('movies')->whereDoesntHave('persons')->whereDoesntHave('episodes');
                    break;
                case 'persons':
                    $query->whereHas('persons')->whereDoesntHave('movies')->whereDoesntHave('episodes');
                    break;
                case 'episodes':
                    $query->whereHas('episodes')->whereDoesntHave('movies')->whereDoesntHave('persons');
                    break;
            }
        }

        if ($this->minItems) {
            $minCount = (int) $this->minItems;
            $query->withCount(['movies', 'persons', 'episodes'])
                ->having(\DB::raw('movies_count + persons_count + episodes_count'), '>=', $minCount);
        }

        if ($this->author) {
            $query->where('user_id', $this->author);
        }

        if (empty($this->search) || $this->sortField !== 'created_at') {
            if ($this->sortField === 'popularity') {
                $query->orderByPopularity();
            } elseif ($this->sortField === 'items_count') {
                $query->orderByItemsCount();
            } else {
                $query->orderBy($this->sortField, $this->sortDirection);
            }
        }

        return $query;
    }

    public function applyFilters()
    {
        $this->search = $this->tempSearch;
        $this->contentType = $this->tempContentType;
        $this->minItems = $this->tempMinItems;
        $this->author = $this->tempAuthor;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->contentType = '';
        $this->minItems = '';
        $this->author = '';
        $this->sortField = 'created_at';
        $this->sortDirection = 'desc';
        $this->tempSearch = '';
        $this->tempContentType = '';
        $this->tempMinItems = '';
        $this->tempAuthor = '';
        $this->resetPage();
    }

    public function getFeaturedSelectionProperty()
    {
        return Cache::remember('featured_selection', 3600, function () {
            return Selection::query()
                ->withCount('movies')
                ->withCount('userLists')
                ->orderByDesc('user_lists_count')
                ->orderByDesc('movies_count')
                ->with(['movies' => function ($query) {
                    $query->select('id', 'name', 'poster')->limit(4);
                }])
                ->first();
        });
    }

    public function handleFilterChange()
    {
        $this->resetPage();
    }

    public function resetPage($pageName = 'page')
    {
        $this->page = 1;
    }

    public function gotoPage($page, $pageName = 'page')
    {
        $this->page = $page;
    }

    public function nextPage($pageName = 'page')
    {
        $this->page++;
    }

    public function previousPage($pageName = 'page')
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function render()
    {
        return view('livewire.pages.selections-page', [
            'selections' => $this->selections,
            'authors' => $this->authors,
            'featuredSelection' => $this->featuredSelection,
        ]);
    }
}

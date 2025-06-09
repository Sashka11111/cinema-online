<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Liamtseva\Cinema\Enums\UserListType;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Person;
use Liamtseva\Cinema\Models\UserList;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class UserListsPage extends Component
{
    use WithPagination;

    // Додаємо властивість page для пагінації з URL-параметром
    #[Url]
    public $page = 1;

    public string $activeTab = 'favorite';

    public string $contentType = 'movies';

    protected $queryString = [
        'activeTab' => ['except' => 'favorite'],
        'contentType' => ['except' => 'movies'],
    ];

    public function mount()
    {
        if (! Auth::check()) {
            return $this->redirectRoute('login', navigate: true);
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetPage();
    }

    public function setContentType($type)
    {
        $this->contentType = $type;
        $this->resetPage();
    }

    public function removeFromList($userListId)
    {
        $userList = UserList::where('id', $userListId)
            ->where('user_id', Auth::id())
            ->first();

        if ($userList) {
            $userList->delete();
            $this->dispatch('list-updated');
        }
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

    public function render()
    {
        $user = Auth::user();

        if (! $user) {
            return $this->redirectRoute('login', navigate: true);
        }

        // Отримуємо тип списку з активної вкладки
        $listType = UserListType::from($this->activeTab);

        // Базовий запит для списків користувача з використанням скоупів
        $query = $user->userLists()
            ->ofType($listType)
            ->with([
                'listable' => function ($morphTo) {
                    $morphTo->morphWith([
                        Movie::class => ['studio', 'tags'],
                        Episode::class => ['movie'],
                        Person::class => ['movies'],
                    ]);
                },
            ]);

        // Фільтруємо за типом контенту
        if ($this->contentType === 'movies') {
            $query->where('listable_type', Movie::class);
        } elseif ($this->contentType === 'episodes') {
            $query->where('listable_type', Episode::class);
        } elseif ($this->contentType === 'people') {
            $query->where('listable_type', Person::class);
        }

        $userLists = $query->latest()->paginate(10, ['*'], 'page', $this->page);

        return view('livewire.pages.user-lists-page', [
            'userLists' => $userLists,
        ])->title('Мої списки');
    }
}

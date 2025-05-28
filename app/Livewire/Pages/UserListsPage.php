<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Liamtseva\Cinema\Enums\UserListType;
use Liamtseva\Cinema\Models\Episode;
use Liamtseva\Cinema\Models\Movie;
use Liamtseva\Cinema\Models\Person;
use Liamtseva\Cinema\Models\UserList;
use Livewire\Component;
use Livewire\WithPagination;

class UserListsPage extends Component
{
    use WithPagination;

    public string $activeTab = 'favorite';

    public string $contentType = 'movies';

    protected $queryString = [
        'activeTab' => ['except' => 'favorite'],
        'contentType' => ['except' => 'movies'],
    ];

    public function mount()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
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

    public function render()
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
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

        $userLists = $query->latest()->paginate(12);

        return view('livewire.pages.user-lists-page', [
            'userLists' => $userLists,
        ])->title('Мої списки');
    }
}

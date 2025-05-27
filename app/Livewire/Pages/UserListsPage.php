<?php

namespace Liamtseva\Cinema\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Liamtseva\Cinema\Enums\UserListType;
use Liamtseva\Cinema\Models\Movie;
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
        if (!Auth::check()) {
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
        
        if (!$user) {
            return redirect()->route('login');
        }

        // Отримуємо тип списку з активної вкладки
        $listType = UserListType::from($this->activeTab);

        // Базовий запит для списків користувача
        $query = $user->userLists()
            ->where('type', $listType)
            ->with('listable');

        // Фільтруємо за типом контенту
        if ($this->contentType === 'movies') {
            $query->where('listable_type', Movie::class);
        } elseif ($this->contentType === 'episodes') {
            $query->where('listable_type', \Liamtseva\Cinema\Models\Episode::class);
        } elseif ($this->contentType === 'people') {
            $query->where('listable_type', \Liamtseva\Cinema\Models\Person::class);
        }

        $userLists = $query->latest()->paginate(12);

        // Статистика для вкладок
        $stats = [
            'favorite' => $user->userLists()->where('type', UserListType::FAVORITE)->count(),
            'watching' => $user->userLists()->where('type', UserListType::WATCHING)->count(),
            'planned' => $user->userLists()->where('type', UserListType::PLANNED)->count(),
            'watched' => $user->userLists()->where('type', UserListType::WATCHED)->count(),
            'stopped' => $user->userLists()->where('type', UserListType::STOPPED)->count(),
            'rewatching' => $user->userLists()->where('type', UserListType::REWATCHING)->count(),
        ];

        return view('livewire.pages.user-lists-page', [
            'userLists' => $userLists,
            'stats' => $stats,
        ])->title('Мої списки');
    }
}

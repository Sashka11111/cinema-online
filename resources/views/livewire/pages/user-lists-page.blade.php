<div class="user-lists">
    <livewire:components.header-component/>

    <div class="user-lists__main">
        <div class="movie-watch__navigation">
            <div class="container">
                <livewire:components.breadcrumbs :items="[
                    ['label' => 'Головна', 'route' => 'home'],
                    ['label' => 'Мої списки', 'active' => true]
                ]"/>
            </div>
        </div>
        <div class="user-lists__header">
            <h1 class="user-lists__title">Мої списки</h1>
            <p class="user-lists__subtitle">Керуйте своїми улюбленими фільмами, серіалами та іншим
                контентом</p>
        </div>

        <!-- Фільтр типу контенту -->
        <div class="user-lists__content-filter">
            <button
                wire:click="setContentType('movies')"
                class="user-lists__content-button {{ $contentType === 'movies' ? 'user-lists__content-button--active' : '' }}"
            >
                <i class="fas fa-film"></i>
                Фільми
            </button>
            <button
                wire:click="setContentType('episodes')"
                class="user-lists__content-button {{ $contentType === 'episodes' ? 'user-lists__content-button--active' : '' }}"
            >
                <i class="fas fa-tv"></i>
                Епізоди
            </button>
            <button
                wire:click="setContentType('people')"
                class="user-lists__content-button {{ $contentType === 'people' ? 'user-lists__content-button--active' : '' }}"
            >
                <i class="fas fa-users"></i>
                Персони
            </button>
        </div>

        <!-- Вкладки типів списків -->
        <div class="user-lists__tabs">
            <button
                wire:click="setActiveTab('favorite')"
                class="user-lists__tab {{ $activeTab === 'favorite' ? 'user-lists__tab--active' : '' }}"
            >
                <i class="fas fa-heart"></i>
                Улюблене
            </button>

            <button
                wire:click="setActiveTab('watching')"
                class="user-lists__tab {{ $activeTab === 'watching' ? 'user-lists__tab--active' : '' }}"
            >
                <i class="fas fa-eye"></i>
                Дивлюся
            </button>

            <button
                wire:click="setActiveTab('planned')"
                class="user-lists__tab {{ $activeTab === 'planned' ? 'user-lists__tab--active' : '' }}"
            >
                <i class="fas fa-clock"></i>
                В планах
            </button>

            <button
                wire:click="setActiveTab('watched')"
                class="user-lists__tab {{ $activeTab === 'watched' ? 'user-lists__tab--active' : '' }}"
            >
                <i class="fas fa-check-circle"></i>
                Переглянуто
            </button>

            <button
                wire:click="setActiveTab('not_watching')"
                class="user-lists__tab {{ $activeTab === 'not_watching' ? 'user-lists__tab--active' : '' }}"
            >
                <i class="fas fa-eye-slash"></i>
                Не дивлюся
            </button>

            <button
                wire:click="setActiveTab('stopped')"
                class="user-lists__tab {{ $activeTab === 'stopped' ? 'user-lists__tab--active' : '' }}"
            >
                <i class="fas fa-pause"></i>
                Перестав
            </button>

            <button
                wire:click="setActiveTab('rewatching')"
                class="user-lists__tab {{ $activeTab === 'rewatching' ? 'user-lists__tab--active' : '' }}"
            >
                <i class="fas fa-redo"></i>
                Передивляюсь
            </button>
        </div>

        <!-- Контент списків -->
        <div class="user-lists__content">
            @if($userLists->count() > 0)
                <div class="user-lists__grid">
                    @foreach($userLists as $userList)
                        <div class="user-lists__item">
                            @if($userList->listable_type === 'Liamtseva\Cinema\Models\Movie')
                                <livewire:components.movie-card :movie="$userList->listable"
                                                                :key="'movie-'.$userList->listable->id"/>
                                <div class="user-lists__item-actions">
                                    <button
                                        wire:click="removeFromList('{{ $userList->id }}')"
                                        class="user-lists__item-remove"
                                        wire:confirm="Ви впевнені, що хочете видалити цей елемент зі списку?"
                                    >
                                        <i class="fas fa-trash"></i>
                                        Видалити
                                    </button>
                                </div>
                            @elseif($userList->listable_type === 'Liamtseva\Cinema\Models\Person')
                                <div class="user-lists__item-image">
                                    @if($userList->listable->image_url)
                                        <img src="{{ $userList->listable->image_url }}"
                                             alt="{{ $userList->listable->name ?? 'Персона' }}">
                                    @else
                                        <div class="user-lists__item-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="user-lists__item-content">
                                    <h3 class="user-lists__item-title">{{ $userList->listable->name ?? 'Невідомий елемент' }}</h3>
                                    <div class="user-lists__item-actions">
                                        <button
                                            wire:click="removeFromList('{{ $userList->id }}')"
                                            class="user-lists__item-remove"
                                            wire:confirm="Ви впевнені, що хочете видалити цей елемент зі списку?"
                                        >
                                            <i class="fas fa-trash"></i>
                                            Видалити
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="user-lists__item-content user-lists__item-content--full">
                                    <h3 class="user-lists__item-title">{{ $userList->listable->name ?? 'Невідомий епізод' }}</h3>
                                    <div class="user-lists__item-meta">
                                         <span class="user-lists__item-type">
                                             {{ $userList->listable->movie->name ?? 'Невідомий фільм' }}
                                         </span>
                                    </div>
                                    <div class="user-lists__item-actions">
                                        <button
                                            wire:click="removeFromList('{{ $userList->id }}')"
                                            class="user-lists__item-remove"
                                            wire:confirm="Ви впевнені, що хочете видалити цей елемент зі списку?"
                                        >
                                            <i class="fas fa-trash"></i>
                                            Видалити
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Пагінація -->
                <div class="content-page__pagination">
                    {{ $userLists->links('livewire.components.pagination') }}
                </div>
            @else
                <div class="user-lists__empty">
                    <div class="user-lists__empty-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <h3 class="user-lists__empty-title">Список порожній</h3>
                    <p class="user-lists__empty-text">
                        У вас поки немає елементів у цьому списку.
                        Додайте фільми, серіали або інший контент до своїх списків.
                    </p>
                </div>
            @endif
        </div>
    </div>
    <livewire:components.main-footer-component/>
</div>

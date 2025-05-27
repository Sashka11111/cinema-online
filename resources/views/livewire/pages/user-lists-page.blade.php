<div class="user-lists">
    <div class="user-lists__main">
        <div class="user-lists__header">
            <h1 class="user-lists__title">Мої списки</h1>
            <p class="user-lists__subtitle">Керуйте своїми улюбленими фільмами, серіалами та іншим контентом</p>
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
                @if($stats['favorite'] > 0)
                    <span class="user-lists__tab-count">{{ $stats['favorite'] }}</span>
                @endif
            </button>

            <button
                wire:click="setActiveTab('watching')"
                class="user-lists__tab {{ $activeTab === 'watching' ? 'user-lists__tab--active' : '' }}"
            >
                <i class="fas fa-eye"></i>
                Дивлюся
                @if($stats['watching'] > 0)
                    <span class="user-lists__tab-count">{{ $stats['watching'] }}</span>
                @endif
            </button>

            <button
                wire:click="setActiveTab('planned')"
                class="user-lists__tab {{ $activeTab === 'planned' ? 'user-lists__tab--active' : '' }}"
            >
                <i class="fas fa-clock"></i>
                В планах
                @if($stats['planned'] > 0)
                    <span class="user-lists__tab-count">{{ $stats['planned'] }}</span>
                @endif
            </button>

            <button
                wire:click="setActiveTab('watched')"
                class="user-lists__tab {{ $activeTab === 'watched' ? 'user-lists__tab--active' : '' }}"
            >
                <i class="fas fa-check-circle"></i>
                Переглянуто
                @if($stats['watched'] > 0)
                    <span class="user-lists__tab-count">{{ $stats['watched'] }}</span>
                @endif
            </button>

            <button
                wire:click="setActiveTab('stopped')"
                class="user-lists__tab {{ $activeTab === 'stopped' ? 'user-lists__tab--active' : '' }}"
            >
                <i class="fas fa-pause"></i>
                Перестав
                @if($stats['stopped'] > 0)
                    <span class="user-lists__tab-count">{{ $stats['stopped'] }}</span>
                @endif
            </button>

            <button
                wire:click="setActiveTab('rewatching')"
                class="user-lists__tab {{ $activeTab === 'rewatching' ? 'user-lists__tab--active' : '' }}"
            >
                <i class="fas fa-redo"></i>
                Передивляюсь
                @if($stats['rewatching'] > 0)
                    <span class="user-lists__tab-count">{{ $stats['rewatching'] }}</span>
                @endif
            </button>
        </div>

        <!-- Контент списків -->
        <div class="user-lists__content">
            @if($userLists->count() > 0)
                <div class="user-lists__grid">
                    @foreach($userLists as $userList)
                        <div class="user-lists__item">
                            @if($userList->listable_type === 'Liamtseva\Cinema\Models\Movie')
                                <div class="user-lists__item-image">
                                    @if($userList->listable->poster_url)
                                        <img src="{{ $userList->listable->poster_url }}" alt="{{ $userList->listable->name }}" loading="lazy">
                                    @else
                                        <div class="user-lists__item-placeholder">
                                            <i class="fas fa-film"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="user-lists__item-content">
                                    <h3 class="user-lists__item-title">
                                        <a href="{{ route('movies.show', $userList->listable) }}">
                                            {{ $userList->listable->name }}
                                        </a>
                                    </h3>

                                    @if($userList->listable->imdb_score)
                                        <div class="user-lists__item-rating">
                                            <i class="fas fa-star"></i>
                                            {{ $userList->listable->imdb_score }}
                                        </div>
                                    @endif

                                    <div class="user-lists__item-meta">
                                        <span class="user-lists__item-type">{{ $userList->listable->kind->getLabel() }}</span>
                                        @if($userList->listable->first_air_date)
                                            <span class="user-lists__item-year">{{ $userList->listable->first_air_date->format('Y') }}</span>
                                        @endif
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
                            @else
                                <div class="user-lists__item-content user-lists__item-content--full">
                                    <h3 class="user-lists__item-title">{{ $userList->listable->name ?? 'Невідомий елемент' }}</h3>
                                    <div class="user-lists__item-meta">
                                        <span class="user-lists__item-type">{{ $userList->translated_type }}</span>
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
                <div class="user-lists__pagination">
                    {{ $userLists->links() }}
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
                    <a href="{{ route('movies.index') }}" class="user-lists__empty-button">
                        <i class="fas fa-search"></i>
                        Переглянути фільми
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

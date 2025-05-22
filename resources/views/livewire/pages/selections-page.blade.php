<div class="selections-page">
    <livewire:components.header-component/>

    <div class="content-page__main">
        <div class="container">
            <livewire:components.breadcrumbs :items="[
                ['label' => 'Головна', 'route' => 'home'],
                ['label' => 'Підбірки', 'active' => true]
            ]"/>

            <h1 class="content-page__title">Підбірки фільмів</h1>

            <div class="selections-page__filters">
                <div class="selections-page__search">
                    <input type="text" wire:model.live.debounce.300ms="search"
                           class="selections-page__search-input"
                           placeholder="Пошук підбірок...">
                    <button class="selections-page__search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <div class="selections-page__categories">
                    <button wire:click="$set('category', '')"
                            class="selections-page__category-btn {{ $category === '' ? 'selections-page__category-btn--active' : '' }}">
                        Усі
                    </button>
                    @foreach($categories as $key => $label)
                        <button wire:click="$set('category', '{{ $key }}')"
                                class="selections-page__category-btn {{ $category === $key ? 'selections-page__category-btn--active' : '' }}">
                            {{ $label }}
                        </button>
                    @endforeach
                </div>

                <div class="selections-page__sort">
                    <div class="selections-page__sort-label">Сортувати:</div>
                    <div class="selections-page__sort-options">
                        <button wire:click="sortBy('created_at')"
                                class="selections-page__sort-btn {{ $sortField === 'created_at' ? 'selections-page__sort-btn--active' : '' }}">
                            За датою
                            @if($sortField === 'created_at')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </button>
                        <button wire:click="sortBy('name')"
                                class="selections-page__sort-btn {{ $sortField === 'name' ? 'selections-page__sort-btn--active' : '' }}">
                            За назвою
                            @if($sortField === 'name')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </button>
                        <button wire:click="sortBy('movies_count')"
                                class="selections-page__sort-btn {{ $sortField === 'movies_count' ? 'selections-page__sort-btn--active' : '' }}">
                            За кількістю фільмів
                            @if($sortField === 'movies_count')
                                <i class="fas fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                            @endif
                        </button>
                    </div>
                </div>
            </div>

            <div class="selections-grid">
                @forelse($selections as $selection)
                    <a href="{{ route('selection.show', $selection->slug) }}"
                       class="selection-card">
                        <div class="selection-card__image-wrapper">
                            @php
                                // Отримуємо перший фільм з підбірки
                                $firstMovie = $selection->movies->first();
                            @endphp

                            @if($firstMovie && $firstMovie->poster_url)
                                <img src="{{ $firstMovie->poster_url }}"
                                     alt="{{ $selection->name }}"
                                     class="selection-card__image">
                            @else
                                <img
                                    src="{{ $selection->meta_image ? asset('storage/' . $selection->meta_image) : asset('images/placeholder.jpg') }}"
                                    alt="{{ $selection->name }}"
                                    class="selection-card__image">
                            @endif

                            <div class="selection-card__overlay"></div>
                            <div class="selection-card__count">{{ $selection->movies->count() }}
                                фільмів
                            </div>
                        </div>
                        <div class="selection-card__content">
                            <h3 class="selection-card__title">{{ $selection->name }}</h3>
                            <p class="selection-card__description">
                                {{ Str::limit(strip_tags($selection->description ?? ''), 100) }}
                            </p>
                            <div class="selection-card__meta">
                                <span class="selection-card__date">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ $selection->created_at->format('d.m.Y') }}
                                </span>
                                @if($selection->user)
                                    <span class="selection-card__author">
                                        <i class="far fa-user"></i>
                                        {{ $selection->user->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="selections-page__empty">
                        <p class="selections-page__empty-text">Підбірки не знайдено.</p>
                    </div>
                @endforelse
            </div>

            <div class="content-page__pagination">
                {{ $selections->links('livewire.components.pagination') }}
            </div>
        </div>
    </div>

    <livewire:components.main-footer-component/>
</div>

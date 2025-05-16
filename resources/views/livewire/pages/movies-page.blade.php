<div class="movies-page">
    <livewire:components.header-component/>

    <main class="movies-page__main">
        <div class="container">
            <!-- Хлібні крихти -->
            <nav class="breadcrumbs" aria-label="Навігація по сайту">
                <ol class="breadcrumbs__list">
                    <li class="breadcrumbs__item">
                        <a href="{{ route('home') }}" class="breadcrumbs__link">Головна</a>
                    </li>
                    <li class="breadcrumbs__item breadcrumbs__item--active" aria-current="page">
                        Фільми
                    </li>
                </ol>
            </nav>

            <h1 class="movies-page__title">Фільми</h1>

            <!-- Секція "В тренді" -->
            <div class="trending-section">
                <h2 class="trending-section__title">В ТРЕНДІ</h2>
                <p class="trending-section__description">Фільми, які зараз популярні серед
                    користувачів</p>

                <div class="trending-movies">
                    @foreach($trendingMovies ?? collect() as $trendingMovie)
                        <article class="trending-movie-card">
                            <a href="{{ route('movies.show', $trendingMovie) }}"
                               class="trending-movie-card__link">
                                <div class="trending-movie-card__poster-wrapper">
                                    <img
                                        src="{{ $trendingMovie->poster ? asset('storage/' . $trendingMovie->poster) : asset('images/no-poster.jpg') }}"
                                        alt="{{ $trendingMovie->name }}"
                                        class="trending-movie-card__poster"
                                        loading="lazy"
                                    >
                                    @if($trendingMovie->imdb_score)
                                        <div class="trending-movie-card__rating">
                                            <span class="trending-movie-card__rating-icon">★</span>
                                            <span
                                                class="trending-movie-card__rating-value">{{ number_format($trendingMovie->imdb_score, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="trending-movie-card__content">
                                    <h3 class="trending-movie-card__title">{{ $trendingMovie->name }}</h3>
                                </div>
                            </a>
                        </article>
                    @endforeach
                </div>
            </div>

            <!-- Фільтри та сортування -->
            <div class="movies-page__filters">
                <div class="filters">
                    <div class="filters__search">
                        <input
                            type="text"
                            wire:model.live.debounce.300ms="search"
                            placeholder="Пошук фільмів..."
                            class="filters__search-input"
                        >
                    </div>

                    <div class="filters__group">
                        <select wire:model.live="status" class="filters__select">
                            <option value="">Усі статуси</option>
                            @foreach($this->statuses as $statusOption)
                                <option
                                    value="{{ $statusOption->value }}">{{ $statusOption->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="period" class="filters__select">
                            <option value="">Усі періоди</option>
                            @foreach($this->periods as $periodOption)
                                <option
                                    value="{{ $periodOption->value }}">{{ $periodOption->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="studio" class="filters__select">
                            <option value="">Усі студії</option>
                            @foreach($this->studios as $studioOption)
                                <option
                                    value="{{ $studioOption->id }}">{{ $studioOption->name }}</option>
                            @endforeach
                        </select>

                        <select wire:model.live="year" class="filters__select">
                            <option value="">Усі роки</option>
                            @foreach($this->years as $yearOption)
                                <option value="{{ $yearOption }}">{{ $yearOption }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="filters__sort">
                        <span class="filters__sort-label">Сортувати за:</span>
                        <button
                            wire:click="sortBy('name')"
                            class="filters__sort-button {{ $sortField === 'name' ? 'filters__sort-button--active' : '' }}"
                        >
                            Назвою
                            @if($sortField === 'name')
                                <span class="filters__sort-direction">
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                </span>
                            @endif
                        </button>
                        <button
                            wire:click="sortBy('first_air_date')"
                            class="filters__sort-button {{ $sortField === 'first_air_date' ? 'filters__sort-button--active' : '' }}"
                        >
                            Датою виходу
                            @if($sortField === 'first_air_date')
                                <span class="filters__sort-direction">
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                </span>
                            @endif
                        </button>
                        <button
                            wire:click="sortBy('imdb_score')"
                            class="filters__sort-button {{ $sortField === 'imdb_score' ? 'filters__sort-button--active' : '' }}"
                        >
                            Рейтингом
                            @if($sortField === 'imdb_score')
                                <span class="filters__sort-direction">
                                    {{ $sortDirection === 'asc' ? '↑' : '↓' }}
                                </span>
                            @endif
                        </button>
                    </div>
                </div>
            </div>

            <!-- Список фільмів -->
            <div class="movie-grid">
                @forelse($movies as $movie)
                    <article class="movie-card">
                        <a href="{{ route('movies.show', $movie) }}" class="movie-card__link">
                            <div class="movie-card__poster-wrapper">
                                <img
                                    src="{{ $movie->poster ? asset('storage/' . $movie->poster) : asset('images/no-poster.jpg') }}"
                                    alt="{{ $movie->name }}"
                                    class="movie-card__poster"
                                    loading="lazy"
                                >
                                @if($movie->imdb_score)
                                    <div class="movie-card__rating movie-card__rating--imdb">
                                        <span class="movie-card__rating-icon">★</span>
                                        <span
                                            class="movie-card__rating-value">{{ number_format($movie->imdb_score, 1) }}</span>
                                    </div>
                                @endif
                                <div class="movie-card__badges">
                                    <span
                                        class="movie-card__badge movie-card__badge--{{ $movie->status->value }}">
                                        {{ $movie->status->name }}
                                    </span>
                                    @if($movie->restricted_rating)
                                        <span class="movie-card__badge movie-card__badge--age">
                                            {{ $movie->restricted_rating->value }}+
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="movie-card__content">
                                <h2 class="movie-card__title">{{ $movie->name }}</h2>
                                @if($movie->name)
                                    <div
                                        class="movie-card__original-title">{{ $movie->name }}</div>
                                @endif
                                <div class="movie-card__meta">
                                    @if($movie->first_air_date)
                                        <span
                                            class="movie-card__year">{{ $movie->first_air_date->format('Y') }}</span>
                                    @endif
                                    @if($movie->duration)
                                        <span
                                            class="movie-card__duration">{{ $movie->duration }} хв</span>
                                    @endif
                                    @if($movie->studio)
                                        <span
                                            class="movie-card__studio">{{ $movie->studio->name }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </article>
                @empty
                    <div class="movies-page__empty">
                        <p class="movies-page__empty-text">Фільми не знайдено. Спробуйте змінити
                            параметри фільтрації.</p>
                    </div>
                @endforelse
            </div>

            <!-- Пагінація -->
            <div class="movies-page__pagination">
                {{ $movies->links('livewire.components.pagination') }}
            </div>
        </div>
    </main>

</div>

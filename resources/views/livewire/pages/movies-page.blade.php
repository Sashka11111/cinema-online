<div>
    <livewire:components.header-component/>

    <main class="content-page__main">
        <div class="container">
            <livewire:components.breadcrumbs :items="[
                ['label' => 'Головна', 'route' => 'home'],
                ['label' => $pageTitle, 'active' => true]
            ]"/>

            <h1 class="content-page__title">{{ $pageTitle }}</h1>

            <!-- Додаємо компонент "В тренді" -->
            <livewire:components.trending-movies-component :contentType="$contentType"/>

            <livewire:components.movies-filter
                :statuses="$this->statuses"
                :periods="$this->periods"
                :studios="$this->studios"
                :years="$this->years"
                :sortField="$sortField"
                :sortDirection="$sortDirection"
                :contentType="$contentType"
                @filters-applied="applyAllFilters($event.detail)"
            />

            <div class="movie-grid">
                @forelse($movies as $movie)
                    <livewire:components.movie-card :movie="$movie" :key="'movie-'.$movie->id"/>
                @empty
                    <div class="movies-page__empty">
                        <p class="movies-page__empty-text">Контент не знайдено.</p>
                    </div>
                @endforelse
            </div>

            <div class="content-page__pagination">
                {{ $movies->links('livewire.components.pagination') }}
            </div>
        </div>
    </main>

    <livewire:components.main-footer-component/>
</div>

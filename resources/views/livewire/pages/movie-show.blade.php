<div class="movie-show">
    <livewire:components.header-component/>

    <main class="movie-show__main">
        <div class="container">
            <livewire:components.breadcrumbs :items="[
                ['label' => 'Головна', 'route' => 'home'],
                ['label' => 'Фільми', 'route' => 'movies'],
                ['label' => $movie->name, 'active' => true]
            ]"/>

            <livewire:components.movie-details :movie="$movie"/>

            <div class="movie-details__section">
                <livewire:components.movie-rating :movie="$movie" />
            </div>

            @if($movie->persons->isNotEmpty())
                <section class="movie-cast">
                    <div class="movie-cast__header">
                        <h2 class="movie-cast__title">
                            <i class="fas fa-users"></i>
                            Акторський склад та знімальна група
                        </h2>
                    </div>

                    <div class="movie-cast__grid">
                        @foreach($movie->persons as $person)
                            <livewire:components.person-card :person="$person"
                                                             :key="'person-'.$person->id" />
                        @endforeach
                    </div>
                </section>
            @endif

            @if($similarMovies->isNotEmpty())
                <livewire:components.similar-movies :movies="$similarMovies"/>
        @endif

    </main>
    <livewire:components.main-footer-component/>
</div>

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

            {{--            <livewire:components.movie-player :movie="$movie"/>--}}

            {{--            @if($movie->persons->isNotEmpty())--}}
            {{--                <livewire:components.movie-cast :persons="$movie->persons"/>--}}
            {{--            @endif--}}

            {{--            @if($similarMovies->isNotEmpty())--}}
            {{--                <livewire:components.similar-movies :movies="$similarMovies"/>--}}
            {{--            --}}{{--            @endif--}}

            {{--            <livewire:components.movie-comments :movie="$movie"/>--}}
        </div>
    </main>
</div>

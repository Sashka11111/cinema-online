<div class="selection-show">

    <livewire:components.header-component/>

    <main class="content-page__main">
        <div class="container">
            <livewire:components.breadcrumbs :items="[
                ['label' => 'Головна', 'route' => 'home'],
                ['label' => 'Підбірки', 'route' => 'selections'],
                ['label' => $selection->name, 'active' => true]
            ]"/>
            <h1 class="content-page__title">{{ $selection->name }}</h1>
            <div class="selection-show__header">
                <div class="selection-show__info">
                    <!-- Опис -->
                    @if($selection->description)
                        <div class="selection-show__description">
                            {!! $selection->description !!}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Фільми -->
            @if($selection->movies->isNotEmpty())
                <div class="selection-show__section">
                    <h2 class="selection-show__section-title">
                        <i class="fas fa-film"></i>
                        Фільми ({{ $selection->movies_count }})
                    </h2>
                    <div class="movie-grid">
                        @foreach($selection->movies as $movie)
                            <livewire:components.movie-card :movie="$movie"
                                                            :key="'movie-'.$movie->id"/>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Персони -->
            @if($selection->persons->isNotEmpty())
                <div class="selection-show__section">
                    <h2 class="selection-show__section-title">
                        <i class="fas fa-user"></i>
                        Персони ({{ $selection->persons_count }})
                    </h2>
                    <div class="persons-grid">
                        @foreach($selection->persons as $person)
                            <livewire:components.person-card :person="$person"
                                                             :key="'person-'.$person->id"/>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Епізоди -->
            @if($selection->episodes->isNotEmpty())
                <div class="selection-show__section">
                    <h2 class="selection-show__section-title">
                        <i class="fas fa-play"></i>
                        Епізоди ({{ $selection->episodes_count }})
                    </h2>
                    <div class="episodes-grid">
                        @foreach($selection->episodes as $episode)
                            <div class="episode-card">
                                <div class="episode-card__content">
                                    <h3 class="episode-card__title">{{ $episode->name }}</h3>
                                    @if($episode->movie)
                                        <p class="episode-card__movie">{{ $episode->movie->name }}</p>
                                    @endif
                                    @if(isset($episode->episode_number))
                                        <p class="episode-card__number">
                                            Епізод {{ $episode->episode_number }}</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Якщо підбірка порожня -->
            @if($selection->movies->isEmpty() && $selection->persons->isEmpty() && $selection->episodes->isEmpty())
                <div class="selection-show__empty">
                    <p class="selection-show__empty-text">Ця підбірка поки що порожня.</p>
                </div>
            @endif
        </div>
    </main>

    <livewire:components.main-footer-component/>
</div>

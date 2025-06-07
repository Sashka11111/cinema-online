<div>
    <livewire:components.header-component/>

    <main class="content-page__main">
        <div class="container">
            <!-- Breadcrumbs -->
            <livewire:components.breadcrumbs :items="[
                ['label' => 'Головна', 'route' => 'home'],
                ['label' => $person->name, 'route' => 'person.show', 'params' => ['person' => $person->slug], 'active' => true],
            ]"/>

            <!-- Person Details -->
            <div class="person-detail">
                <div class="person-detail__header">
                    <div class="person-detail__image">
                        <img
                            src="{{ $person->image_url ?? asset('images/person-placeholder.svg') }}"
                            alt="{{ $person->name }}"
                            class="person-detail__photo"
                            loading="lazy"
                            onerror="this.onerror=null; this.src='{{ asset('images/person-placeholder.svg') }}';">
                    </div>
                    <div class="person-detail__info">
                        <h1 class="content-page__title">{{ $pageTitle }}</h1>

                        @if($person->original_name)
                            <p class="person-detail__original-name">{{ $person->original_name }}</p>
                        @endif

                        @if($person->type)
                            <p class="person-detail__type">{{ $person->type->getLabel() }}</p>
                        @endif

                        @if($person->gender)
                            <p class="person-detail__gender">
                                <i class="fas fa-venus-mars"></i>
                                {{ $person->gender->getLabel() }}
                            </p>
                        @endif

                        @if($person->birthday)
                            <p class="person-detail__birth-date">
                                <i class="far fa-calendar-alt"></i>
                                {{ \Carbon\Carbon::parse($person->birthday)->format('F j, Y') }}
                                ({{ \Carbon\Carbon::parse($person->birthday)->age }} years)
                            </p>
                        @endif

                        @if($person->birthplace)
                            <p class="person-detail__birthplace">
                                <i class="fas fa-map-marker-alt"></i>
                                {{ $person->birthplace }}
                            </p>
                        @endif
                    </div>

                    @if($person->description)
                        <div class="person-detail__description">
                            <h2>Biography</h2>
                            <p>{{ $person->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Movies Grid -->
                @if($movies && $movies->isNotEmpty())
                    <div class="person-detail__movies">
                        <h2>Filmography</h2>
                        <div class="movie-grid">
                            @foreach($movies as $movie)
                                <livewire:components.movie-card :movie="$movie"
                                                                :key="'movie-'.$movie->id"/>
                            @endforeach
                        </div>
                    </div>
                @else
                    <div class="movies-page__empty">
                        <p class="movies-page__empty-text">Контент не знайдено.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>

    <livewire:components.main-footer-component/>
</div>

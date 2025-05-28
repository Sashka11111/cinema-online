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
                            <div class="movie-cast__card">
                                <div class="movie-cast__photo">
                                    @if($person->image_url)
                                        <img src="{{ $person->image_url }}"
                                             alt="{{ $person->name }}"
                                             class="movie-cast__image"
                                             loading="lazy">
                                    @else
                                        <div class="movie-cast__placeholder">
                                            @if($person->type->value === 'actor')
                                                <svg class="movie-cast__placeholder-svg"
                                                     viewBox="0 0 120 120" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="120" height="120"
                                                          fill="url(#actorGradient)"/>
                                                    <circle cx="60" cy="40" r="18"
                                                            fill="rgba(255,255,255,0.9)"/>
                                                    <path d="M30 120 Q30 85 60 85 Q90 85 90 120 Z"
                                                          fill="rgba(255,255,255,0.9)"/>
                                                    <!-- Маска театральна -->
                                                    <path
                                                        d="M45 35 Q60 30 75 35 Q75 45 60 50 Q45 45 45 35 Z"
                                                        fill="rgba(255,255,255,0.3)"/>
                                                    <defs>
                                                        <linearGradient id="actorGradient" x1="0%"
                                                                        y1="0%" x2="100%" y2="100%">
                                                            <stop offset="0%"
                                                                  style="stop-color:#667eea;stop-opacity:1"/>
                                                            <stop offset="100%"
                                                                  style="stop-color:#764ba2;stop-opacity:1"/>
                                                        </linearGradient>
                                                    </defs>
                                                </svg>
                                            @elseif($person->type->value === 'director')
                                                <svg class="movie-cast__placeholder-svg"
                                                     viewBox="0 0 120 120" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="120" height="120"
                                                          fill="url(#directorGradient)"/>
                                                    <circle cx="60" cy="40" r="18"
                                                            fill="rgba(255,255,255,0.9)"/>
                                                    <path d="M30 120 Q30 85 60 85 Q90 85 90 120 Z"
                                                          fill="rgba(255,255,255,0.9)"/>
                                                    <!-- Кінокамера -->
                                                    <rect x="45" y="25" width="30" height="20"
                                                          rx="3" fill="rgba(255,255,255,0.3)"/>
                                                    <circle cx="55" cy="35" r="4"
                                                            fill="rgba(255,255,255,0.5)"/>
                                                    <defs>
                                                        <linearGradient id="directorGradient"
                                                                        x1="0%" y1="0%" x2="100%"
                                                                        y2="100%">
                                                            <stop offset="0%"
                                                                  style="stop-color:#f093fb;stop-opacity:1"/>
                                                            <stop offset="100%"
                                                                  style="stop-color:#f5576c;stop-opacity:1"/>
                                                        </linearGradient>
                                                    </defs>
                                                </svg>
                                            @elseif($person->type->value === 'producer')
                                                <svg class="movie-cast__placeholder-svg"
                                                     viewBox="0 0 120 120" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="120" height="120"
                                                          fill="url(#producerGradient)"/>
                                                    <circle cx="60" cy="40" r="18"
                                                            fill="rgba(255,255,255,0.9)"/>
                                                    <path d="M30 120 Q30 85 60 85 Q90 85 90 120 Z"
                                                          fill="rgba(255,255,255,0.9)"/>
                                                    <!-- Кінострічка -->
                                                    <rect x="40" y="30" width="40" height="6"
                                                          fill="rgba(255,255,255,0.3)"/>
                                                    <rect x="42" y="32" width="4" height="2"
                                                          fill="rgba(255,255,255,0.5)"/>
                                                    <rect x="48" y="32" width="4" height="2"
                                                          fill="rgba(255,255,255,0.5)"/>
                                                    <rect x="54" y="32" width="4" height="2"
                                                          fill="rgba(255,255,255,0.5)"/>
                                                    <defs>
                                                        <linearGradient id="producerGradient"
                                                                        x1="0%" y1="0%" x2="100%"
                                                                        y2="100%">
                                                            <stop offset="0%"
                                                                  style="stop-color:#4facfe;stop-opacity:1"/>
                                                            <stop offset="100%"
                                                                  style="stop-color:#00f2fe;stop-opacity:1"/>
                                                        </linearGradient>
                                                    </defs>
                                                </svg>
                                            @else
                                                <svg class="movie-cast__placeholder-svg"
                                                     viewBox="0 0 120 120" fill="none"
                                                     xmlns="http://www.w3.org/2000/svg">
                                                    <rect width="120" height="120"
                                                          fill="url(#defaultGradient)"/>
                                                    <circle cx="60" cy="40" r="18"
                                                            fill="rgba(255,255,255,0.9)"/>
                                                    <path d="M30 120 Q30 85 60 85 Q90 85 90 120 Z"
                                                          fill="rgba(255,255,255,0.9)"/>
                                                    <!-- Перо для сценариста -->
                                                    <path d="M45 30 L75 30 L73 40 L47 40 Z"
                                                          fill="rgba(255,255,255,0.3)"/>
                                                    <circle cx="50" cy="35" r="2"
                                                            fill="rgba(255,255,255,0.5)"/>
                                                    <defs>
                                                        <linearGradient id="defaultGradient" x1="0%"
                                                                        y1="0%" x2="100%" y2="100%">
                                                            <stop offset="0%"
                                                                  style="stop-color:#43e97b;stop-opacity:1"/>
                                                            <stop offset="100%"
                                                                  style="stop-color:#38f9d7;stop-opacity:1"/>
                                                        </linearGradient>
                                                    </defs>
                                                </svg>
                                            @endif
                                        </div>
                                    @endif

                                    <div
                                        class="movie-cast__type-badge movie-cast__type-badge--{{ strtolower($person->type->value) }}">
                                        {{ $person->type->getLabel() }}
                                    </div>
                                </div>

                                <div class="movie-cast__info">
                                    <h3 class="movie-cast__name">{{ $person->name }}</h3>
                                    @if($person->pivot->character_name)
                                        <p class="movie-cast__character">
                                            {{ $person->pivot->character_name }}
                                        </p>
                                    @endif
                                </div>
                            </div>
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

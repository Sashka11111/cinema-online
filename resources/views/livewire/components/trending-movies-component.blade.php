<section class="trending-section">
    <h2 class="trending-section__title">В тренді</h2>
    <p class="trending-section__description">Найпопулярніші медіа</p>

    <div class="trending-movies__wrapper">
        <button class="trending-movies__nav trending-movies__nav--prev"
                aria-label="Попередні фільми" disabled id="trendingPrev">
            <span class="trending-movies__nav-icon">←</span>
        </button>

        <div class="trending-movies__carousel">
            <div class="trending-movies__track" id="trendingTrack">
                @forelse($trendingMovies as $movie)
                    <div class="trending-movie-card">
                        <a href="{{ route('movies.show', $movie) }}" wire:navigate
                           class="trending-movie-card__link">
                            <div class="trending-movie-card__poster-wrapper">
                                <img
                                    src="{{ $movie->poster_url ?? asset('images/movie-placeholder.svg') }}"
                                    alt="{{ $movie->name }}"
                                    class="trending-movie-card__poster"
                                    loading="lazy"
                                    onerror="this.onerror=null; this.src='{{ asset('images/movie-placeholder.svg') }}';"
                                >

                                @if($movie->imdb_score)
                                    <div class="trending-movie-card__rating">
                                        <span class="trending-movie-card__rating-icon">★</span>
                                        <span
                                            class="trending-movie-card__rating-value">{{ number_format($movie->imdb_score, 1) }}</span>
                                    </div>

                                @endif

                                <div
                                    class="trending-movie-card__badge trending-movie-card__badge--{{ $movie->status->value }}">
                                    {{ $movie->status->getLabel()

                            }}
                                </div>
                            </div>

                            <div
                                class
                                ="trending-movie-card__info"
                            >
                                <h3 class
                                    ="
                        trending-movie-card__title">{{ $movie->name }}</h3>
                                <div class="trending-movie-card__kind">
                                    {{$movie->kind->getLabel() }}
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="
                trending-section__empty">
                        <p>Немає доступних медіа у тренді.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <button class="trending-movies__nav trending-movies__nav--next"
                aria-label="Наступні фільми" id="trendingNext">
            <span class="trending-movies__nav-icon">→</span>
        </button>
    </div>
</section>

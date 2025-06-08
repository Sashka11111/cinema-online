<div class="movie-slider">
    @if($movies->isEmpty())
        <div class="movie-slider__empty">
            <p>Немає доступних фільмів.</p>
        </div>
    @else
        <div class="movie-slider__container">
            @foreach($movies as $movie)
                <div class="movie-card">
                    <a href="{{ route('movies.show', $movie) }}" wire:navigate class="movie-card__link">
                        <div class="movie-card__poster-wrapper">
                            <img src="{{ $movie->poster_url }}" alt="{{ $movie->name }}"
                                 class="movie-card__poster">
                            <div class="movie-card__overlay">
                                <div class="movie-card__play-icon">
                                    <i class="fas fa-play"></i>
                                </div>
                            </div>
                        </div>
                        <div class="movie-card__info">
                            <h3 class="movie-card__title">{{ $movie->name }}</h3>
                            <div class="movie-card__meta">
                                <span
                                    class="movie-card__year">{{ $movie->first_air_date ? $movie->first_air_date->format('Y') : 'Невідомо' }}</span>
                                <span class="movie-card__rating">
                                    <i class="fas fa-star"></i> {{ number_format($movie->imdb_score, 1) }}
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>

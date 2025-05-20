<article class="movie-card">
    <a href="{{ route('movies.show', $movie) }}" class="movie-card__link">
        <div class="movie-card__poster-wrapper">
            <img
                src="{{ $movie->poster_url ?? asset('images/movie-placeholder.svg') }}"
                alt="{{ $movie->name }}"
                class="movie-card__poster"
                loading="lazy"
                onerror="this.onerror=null; this.src='{{ asset('images/movie-placeholder.svg') }}';"
            >
            @if($movie->imdb_score)
                <div class="movie-card__rating movie-card__rating--imdb">
                    <img src="{{ asset('images/star.png') }}" alt="★"
                         class="movie-card__rating-icon">
                    <span
                        class="movie-card__rating-value">{{ number_format($movie->imdb_score, 1) }}</span>
                </div>
            @endif
            <div class="movie-card__badges">
                <span
                    class="movie-card__badge movie-card__badge--{{ $movie->status->value }}">
                    {{ $movie->status->getLabel() }}
                </span>
                @if($movie->restricted_rating)
                    <span class="movie-card__badge movie-card__badge--age">
                        {{ $movie->restricted_rating->getLabel() }}+
                    </span>
                @endif
            </div>
        </div>
        <div class="movie-card__content">
            <h2 class="movie-card__title">{{ $movie->name }}</h2>

            <div class="movie-card__meta">
                @if($movie->studio)
                    <span class="movie-card__studio">{{ $movie->studio->name }}</span>
                @endif
                @if($movie->first_air_date)
                    <span class="movie-card__year">{{ $movie->first_air_date->format('Y') }}</span>
                @endif
            </div>

            @if($movie->tags && $movie->tags->isNotEmpty())
                <div class="movie-card__tags">
                    @foreach($movie->tags->where('is_genre', true)->take(2) as $tag)
                        <span class="movie-card__tag">{{ $tag->name }}</span>
                    @endforeach
                </div>
            @endif
        </div>
    </a>
</article>

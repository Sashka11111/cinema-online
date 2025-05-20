<div class="movie-details">
    <div class="movie-details__poster-container">
        <img
            src="{{ $movie->poster ? asset('storage/' . $movie->poster) : asset('images/no-image.svg') }}"
            alt="{{ $movie->name }}"
            class="movie-details__poster"
        >

        @if($movie->imdb_score)
            <div class="movie-details__rating">
                <span class="movie-details__rating-icon">★</span>
                <span
                    class="movie-details__rating-value">{{ number_format($movie->imdb_score, 1) }}</span>
                <span class="movie-details__rating-label">IMDb</span>
            </div>
        @endif
    </div>

    <div class="movie-details__info">
        <h1 class="movie-details__title">{{ $movie->name }}</h1>

        @if(count($movie->aliases ?? []) > 0)
            <div class="movie-details__original-title">
                {{ $movie->aliases[0] }}
            </div>
        @endif

        <div class="movie-details__meta">
            <div class="movie-details__meta-item">
                <span class="movie-details__meta-label">Рік:</span>
                <span class="movie-details__meta-value">
                    {{ $movie->first_air_date ? $movie->first_air_date->format('Y') : 'Невідомо' }}
                </span>
            </div>

            @if($movie->countries && count($movie->countries) > 0)
                <div class="movie-details__meta-item">
                    <span class="movie-details__meta-label">Країна:</span>
                    <span class="movie-details__meta-value">
                        {{ implode(', ', $movie->countries) }}
                    </span>
                </div>
            @endif

            @if($movie->studio)
                <div class="movie-details__meta-item">
                    <span class="movie-details__meta-label">Студія:</span>
                    <span class="movie-details__meta-value">
                        {{ $movie->studio->name }}
                    </span>
                </div>
            @endif

            @if($movie->duration)
                <div class="movie-details__meta-item">
                    <span class="movie-details__meta-label">Тривалість:</span>
                    <span class="movie-details__meta-value">
                        {{ $movie->duration }} хв
                    </span>
                </div>
            @endif

            @if($movie->restricted_rating)
                <div class="movie-details__meta-item">
                    <span class="movie-details__meta-label">Вікове обмеження:</span>
                    <span class="movie-details__meta-value movie-details__meta-value--age">
                        {{ $movie->restricted_rating->value }}+
                    </span>
                </div>
            @endif

            @if($movie->status)
                <div class="movie-details__meta-item">
                    <span class="movie-details__meta-label">Статус:</span>
                    <span class="movie-details__meta-value movie-details__meta-value--status">
                        {{ $movie->status->name }}
                    </span>
                </div>
            @endif
        </div>

        @if($movie->tags->isNotEmpty())
            <div class="movie-details__tags">
                @foreach($movie->tags as $tag)
                    <a href="{{ route('movies', ['tag' => $tag->id]) }}" class="movie-details__tag">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        <div class="movie-details__description">
            <h2 class="movie-details__section-title">Опис</h2>
            <div class="movie-details__description-text">
                {{ $movie->description }}
            </div>
        </div>

        <div class="movie-details__actions">
            <button class="movie-details__action-button movie-details__action-button--watch">
                <i class="fas fa-play"></i> Дивитися
            </button>
            <button class="movie-details__action-button movie-details__action-button--favorite">
                <i class="fas fa-heart"></i> У вибране
            </button>
            <button class="movie-details__action-button movie-details__action-button--share">
                <i class="fas fa-share-alt"></i> Поділитися
            </button>
        </div>
    </div>
</div>

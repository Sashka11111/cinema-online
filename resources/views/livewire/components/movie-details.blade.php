<div class="movie-details">
    <div class="movie-details__poster-container">
        <img src="{{ $movie->poster_url }}" alt="{{ $movie->name }}"
             class="movie-details__poster">

        @if($movie->imdb_score)
            <div class="movie-details__rating">
                <img src="{{ asset('images/star.png') }}" alt="★"
                     class="movie-details__rating-icon">
                <div class="movie-details__rating-score">
                    <i class="fas fa-star"></i>
                    <span
                        class="movie-details__rating-value">{{ number_format($movie->imdb_score, 1) }}</span>
                </div>
                <span class="movie-details__rating-label">IMDb</span>
            </div>
        @endif

        @if($movie->kind)
            <div class="movie-details__type-badge">
                <i class="fas fa-{{ str_contains($movie->kind->value, 'movie') ? 'film' : 'tv' }}"></i>
                {{ $movie->kind->getLabel() }}
            </div>
        @endif
    </div>

    <div class="movie-details__info">
        <div class="movie-details__header">
            <h1 class="movie-details__title">{{ $movie->name }}</h1>

            @if(isset($movie->aliases) && is_array($movie->aliases) && count($movie->aliases) > 0)
                <div class="movie-details__original-title">
                    {{ $movie->aliases[0] }}
                </div>
            @endif

            @if($movie->restricted_rating)
                <div class="movie-details__age-rating">
                    {{ $movie->restricted_rating->value }}+
                </div>
            @endif
        </div>

        <div class="movie-details__meta-grid">
            <div class="movie-details__meta-section">
                <h3 class="movie-details__meta-title">Основна інформація</h3>

                <div class="movie-details__meta-item">
                    <span class="movie-details__meta-label">
                        <i class="fas fa-calendar"></i> Рік випуску:
                    </span>
                    <span class="movie-details__meta-value">
                        {{ $movie->first_air_date ? $movie->first_air_date->format('Y') : 'Невідомо' }}
                    </span>
                </div>

                @if(isset($movie->countries) && is_array($movie->countries) && count($movie->countries) > 0)
                    <div class="movie-details__meta-item">
                        <span class="movie-details__meta-label">
                            <i class="fas fa-globe"></i> Країна:
                        </span>
                        <span class="movie-details__meta-value">
                            {{ implode(', ', $movie->countries) }}
                        </span>
                    </div>
                @endif

                @if($movie->studio)
                    <div class="movie-details__meta-item">
                        <span class="movie-details__meta-label">
                            <i class="fas fa-building"></i> Студія:
                        </span>
                        <span class="movie-details__meta-value">
                            {{ $movie->studio->name }}
                        </span>
                    </div>
                @endif

                @if($movie->duration)
                    <div class="movie-details__meta-item">
                        <span class="movie-details__meta-label">
                            <i class="fas fa-clock"></i> Тривалість:
                        </span>
                        <span class="movie-details__meta-value">
                            {{ $movie->duration }} хв
                        </span>
                    </div>
                @endif

                @if($movie->status)
                    <div class="movie-details__meta-item">
                        <span class="movie-details__meta-label">
                            <i class="fas fa-info-circle"></i> Статус:
                        </span>
                        <span class="movie-details__meta-value movie-details__meta-value--status">
                            {{ $movie->status->name }}
                        </span>
                    </div>
                @endif
            </div>

            @if($movie->episodes && $movie->episodes->count() > 0)
                <div class="movie-details__meta-section">
                    <h3 class="movie-details__meta-title">Епізоди</h3>

                    <div class="movie-details__meta-item">
                        <span class="movie-details__meta-label">
                            <i class="fas fa-list"></i> Кількість епізодів:
                        </span>
                        <span class="movie-details__meta-value">
                            {{ $movie->episodes->count() }}
                        </span>
                    </div>


                </div>
            @endif
        </div>

        @if($movie->tags && $movie->tags->isNotEmpty())
            <div class="movie-details__tags-section">
                <h3 class="movie-details__section-title">
                    <i class="fas fa-tags"></i> Жанри
                </h3>
                <div class="movie-details__tags">
                    @foreach($movie->tags as $tag)
                        <a href="{{ route('movies', ['tag' => $tag->id]) }}"
                           class="movie-details__tag">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($movie->description)
            <div class="movie-details__description">
                <h3 class="movie-details__section-title">
                    <i class="fas fa-align-left"></i> Опис
                </h3>
                <div class="movie-details__description-text">
                    {!! nl2br(e($movie->description)) !!}
                </div>
            </div>
        @endif

        <div class="movie-details__actions">
            <a href="{{ route('movies.watch', $movie) }}"
               class="movie-details__action-button movie-details__action-button--watch">
                <i class="fas fa-play"></i> Дивитися
            </a>

            <button class="movie-details__action-button movie-details__action-button--favorite">
                <i class="fas fa-heart"></i> У вибране
            </button>
        </div>
    </div>
</div>

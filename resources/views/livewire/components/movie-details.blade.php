<div class="movie-details">
    <div class="movie-details__poster-container">
        <img src="{{ $movie->poster_url ?? asset('images/movie-placeholder.svg') }}"
             alt="{{ $movie->name }}"
             class="movie-details__poster"
             loading="lazy"
             onerror="this.onerror=null; this.src='{{ asset('images/movie-placeholder.svg') }}';">

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
                    {{ $movie->restricted_rating->getLabel() }}+
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
                            {{ $movie->status->getLabel() }}
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
                        <a href="{{ route('movies', ['tag' => $tag->id]) }}" wire:navigate
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
            <div class="movie-details__primary-actions">
                <a href="{{ route('movies.watch', $movie) }}" wire:navigate
                   class="movie-details__action-button movie-details__action-button--watch">
                    <i class="fas fa-play"></i> Дивитися
                </a>

                <a href="{{ route('movies.comments', $movie->slug) }}" wire:navigate
                   class="movie-details__action-button movie-details__action-button--comments">
                    <i class="fas fa-comments"></i> Коментарі
                </a>
            </div>

            <div class="movie-details__lists">
                <h4 class="movie-details__lists-title">
                    <i class="fas fa-list"></i> Додати до списку
                </h4>
                <div class="movie-details__lists-buttons">
                    <button wire:click="addToList('favorite')"
                            class="movie-details__list-button movie-details__list-button--favorite {{ $currentListType === 'favorite' ? 'movie-details__list-button--current' : '' }}">
                        <i class="fas fa-heart"></i> Улюблене
                        @if($currentListType === 'favorite') <span class="current-indicator">✓</span> @endif
                    </button>

                    <button wire:click="addToList('watching')"
                            class="movie-details__list-button movie-details__list-button--watching {{ $currentListType === 'watching' ? 'movie-details__list-button--current' : '' }}">
                        <i class="fas fa-eye"></i> Дивлюся
                        @if($currentListType === 'watching') <span class="current-indicator">✓</span> @endif
                    </button>

                    <button wire:click="addToList('planned')"
                            class="movie-details__list-button movie-details__list-button--planned {{ $currentListType === 'planned' ? 'movie-details__list-button--current' : '' }}">
                        <i class="fas fa-clock"></i> В планах
                        @if($currentListType === 'planned') <span class="current-indicator">✓</span> @endif
                    </button>

                    <button wire:click="addToList('watched')"
                            class="movie-details__list-button movie-details__list-button--watched {{ $currentListType === 'watched' ? 'movie-details__list-button--current' : '' }}">
                        <i class="fas fa-check-circle"></i> Переглянуто
                        @if($currentListType === 'watched') <span class="current-indicator">✓</span> @endif
                    </button>

                    <button wire:click="addToList('not_watching')"
                            class="movie-details__list-button movie-details__list-button--not-watching {{ $currentListType === 'not_watching' ? 'movie-details__list-button--current' : '' }}">
                        <i class="fas fa-eye-slash"></i> Не дивлюся
                        @if($currentListType === 'not_watching') <span class="current-indicator">✓</span> @endif
                    </button>

                    <button wire:click="addToList('stopped')"
                            class="movie-details__list-button movie-details__list-button--stopped {{ $currentListType === 'stopped' ? 'movie-details__list-button--current' : '' }}">
                        <i class="fas fa-pause"></i> Перестав
                        @if($currentListType === 'stopped') <span class="current-indicator">✓</span> @endif
                    </button>

                    <button wire:click="addToList('rewatching')"
                            class="movie-details__list-button movie-details__list-button--rewatching {{ $currentListType === 'rewatching' ? 'movie-details__list-button--current' : '' }}">
                        <i class="fas fa-arrow-path"></i> Передивляюсь
                        @if($currentListType === 'rewatching') <span class="current-indicator">✓</span> @endif
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Компонент сповіщень -->
    <div x-data="{
            show: false,
            message: '',
            type: 'success',
            init() {
                console.log('Notification component initialized');
            },
            showNotification(event) {
                console.log('Notification event received:', event.detail);
                this.message = event.detail.message;
                this.type = event.detail.type;
                this.show = true;
                setTimeout(() => { this.show = false }, 10000);
            }
        }"
         x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-2"
         @notify.window="showNotification($event)"
         @notify="showNotification($event)"
         class="movie-details__notification"
         :class="{
            'movie-details__notification--success': type === 'success',
            'movie-details__notification--error': type === 'error',
            'movie-details__notification--info': type === 'info'
        }">
        <div class="movie-details__notification-icon">
            <i class="fas" :class="{
                'fa-check-circle': type === 'success',
                'fa-exclamation-circle': type === 'error',
                'fa-info-circle': type === 'info'
            }"></i>
        </div>
        <div class="movie-details__notification-content" x-text="message"></div>
        <button @click="show = false" class="movie-details__notification-close">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <!-- Стилі для активного стану кнопок -->
    <style>
        .movie-details__list-button--favorite {
            background-color: #e91e63 !important;
            color: white !important;
            border-color: #e91e63 !important;
        }

        .movie-details__list-button--watching {
            background-color: #28a745 !important;
            color: white !important;
            border-color: #28a745 !important;
        }

        .movie-details__list-button--planned {
            background-color: #ffc107 !important;
            color: #212529 !important;
            border-color: #ffc107 !important;
        }

        .movie-details__list-button--watched {
            background-color: #007bff !important;
            color: white !important;
            border-color: #007bff !important;
        }

        .movie-details__list-button--not-watching {
            background-color: #6c757d !important;
            color: white !important;
            border-color: #6c757d !important;
        }

        .movie-details__list-button--stopped {
            background-color: #dc3545 !important;
            color: white !important;
            border-color: #dc3545 !important;
        }

        .movie-details__list-button--rewatching {
            background-color: #17a2b8 !important;
            color: white !important;
            border-color: #17a2b8 !important;
        }

        .movie-details__notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            max-width: 400px;
            padding: 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .movie-details__notification--success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .movie-details__notification--error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .movie-details__notification--info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            color: #0c5460;
        }

        .movie-details__notification-close {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            margin-left: auto;
            opacity: 0.7;
        }

        .movie-details__notification-close:hover {
            opacity: 1;
        }
    </style>
</div>

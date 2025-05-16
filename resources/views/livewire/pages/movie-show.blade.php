<div class="movie-show">
    <livewire:components.header-component/>

    <main class="movie-show__main">
        <div class="container">
            <!-- Хлібні крихти -->
            <nav class="breadcrumbs" aria-label="Навігація по сайту">
                <ol class="breadcrumbs__list">
                    <li class="breadcrumbs__item">
                        <a href="{{ route('home') }}" class="breadcrumbs__link">Головна</a>
                    </li>
                    <li class="breadcrumbs__item">
                        <a href="{{ route('movies') }}" class="breadcrumbs__link">Фільми</a>
                    </li>
                    <li class="breadcrumbs__item breadcrumbs__item--active" aria-current="page">
                        {{ $movie->name }}
                    </li>
                </ol>
            </nav>

            <!-- Основна інформація про фільм -->
            <div class="movie-details">
                <div class="movie-details__poster-container">
                    <img 
                        src="{{ $movie->poster ? asset('storage/' . $movie->poster) : asset('images/no-poster.jpg') }}" 
                        alt="{{ $movie->name }}" 
                        class="movie-details__poster"
                    >
                    
                    @if($movie->imdb_score)
                        <div class="movie-details__rating">
                            <span class="movie-details__rating-icon">★</span>
                            <span class="movie-details__rating-value">{{ number_format($movie->imdb_score, 1) }}</span>
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
            
            <!-- Відео плеєр -->
            <div class="movie-player">
                <h2 class="movie-player__title">Дивитися фільм {{ $movie->name }}</h2>
                <div class="movie-player__container">
                    @if(isset($movie->attachments) && count($movie->attachments) > 0)
                        @php
                            $videoAttachment = collect($movie->attachments)
                                ->firstWhere('type', 'video');
                        @endphp
                        
                        @if($videoAttachment)
                            <iframe 
                                src="{{ $videoAttachment['src'] }}" 
                                class="movie-player__iframe" 
                                allowfullscreen
                            ></iframe>
                        @else
                            <div class="movie-player__placeholder">
                                <p>Відео недоступне для перегляду</p>
                            </div>
                        @endif
                    @else
                        <div class="movie-player__placeholder">
                            <p>Відео недоступне для перегляду</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Актори та знімальна група -->
            @if($movie->persons->isNotEmpty())
                <div class="movie-cast">
                    <h2 class="movie-cast__title">Актори та знімальна група</h2>
                    <div class="movie-cast__list">
                        @foreach($movie->persons as $person)
                            <div class="movie-cast__item">
                                <div class="movie-cast__photo-container">
                                    <img 
                                        src="{{ $person->photo ? asset('storage/' . $person->photo) : asset('images/no-photo.jpg') }}" 
                                        alt="{{ $person->name }}" 
                                        class="movie-cast__photo"
                                    >
                                </div>
                                <div class="movie-cast__info">
                                    <div class="movie-cast__name">{{ $person->name }}</div>
                                    @if($person->pivot->character_name)
                                        <div class="movie-cast__character">{{ $person->pivot->character_name }}</div>
                                    @endif
                                    <div class="movie-cast__type">{{ $person->type->name }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Схожі фільми -->
            @if($similarMovies->isNotEmpty())
                <div class="similar-movies">
                    <h2 class="similar-movies__title">Схожі фільми</h2>
                    <div class="similar-movies__list">
                        @foreach($similarMovies as $similarMovie)
                            <div class="similar-movie-card">
                                <a href="{{ route('movies.show', $similarMovie) }}" class="similar-movie-card__link">
                                    <div class="similar-movie-card__poster-container">
                                        <img 
                                            src="{{ $similarMovie->poster ? asset('storage/' . $similarMovie->poster) : asset('images/no-poster.jpg') }}" 
                                            alt="{{ $similarMovie->name }}" 
                                            class="similar-movie-card__poster"
                                        >
                                    </div>
                                    <div class="similar-movie-card__info">
                                        <div class="similar-movie-card__title">{{ $similarMovie->name }}</div>
                                        @if($similarMovie->first_air_date)
                                            <div class="similar-movie-card__year">{{ $similarMovie->first_air_date->format('Y') }}</div>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Коментарі -->
            <div class="movie-comments">
                <h2 class="movie-comments__title">Коментарі</h2>
                
                @auth
                    <div class="movie-comments__form">
                        <textarea 
                            class="movie-comments__input" 
                            placeholder="Напишіть ваш коментар..."
                        ></textarea>
                        <button class="movie-comments__submit">Відправити</button>
                    </div>
                @else
                    <div class="movie-comments__login-prompt">
                        <p>Щоб залишити коментар, будь ласка, <a href="{{ route('login') }}">увійдіть</a> або <a href="{{ route('register') }}">зареєструйтесь</a>.</p>
                    </div>
                @endauth
                
                <div class="movie-comments__list">
                    @forelse($movie->comments as $comment)
                        <div class="movie-comment">
                            <div class="movie-comment__avatar">
                                <img 
                                    src="{{ $comment->user->avatar ? asset('storage/' . $comment->user->avatar) : asset('images/default-avatar.jpg') }}" 
                                    alt="{{ $comment->user->name }}" 
                                    class="movie-comment__avatar-img"
                                >
                            </div>
                            <div class="movie-comment__content">
                                <div class="movie-comment__header">
                                    <div class="movie-comment__author">{{ $comment->user->name }}</div>
                                    <div class="movie-comment__date">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="movie-comment__text">{{ $comment->content }}</div>
                                <div class="movie-comment__actions">
                                    <button class="movie-comment__action movie-comment__action--reply">Відповісти</button>
                                    <button class="movie-comment__action movie-comment__action--like">
                                        <i class="fas fa-thumbs-up"></i> 
                                        <span class="movie-comment__likes-count">{{ $comment->likes_count ?? 0 }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="movie-comments__empty">
                            <p>Коментарів поки немає. Будьте першим, хто залишить коментар!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </main>
</div>
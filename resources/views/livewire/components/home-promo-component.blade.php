<section class="home-promo" id="promoSection">
    <div class="home-promo__container">
        <div class="home-promo__content">
            <h1 class="home-promo__title">Ласкаво просимо до нашої кінотеки</h1>
            <div class="home-promo__actions">
                <a href="{{ route('movies.show', $featuredMovies->first()) }}" wire:navigate
                   class="home-promo__button home-promo__button--primary" id="promoWatchButton">
                    Переглянути
                </a>
                <a href="{{ route('selections') }}" wire:navigate
                   class="home-promo__button home-promo__button--secondary">
                    Підбірки
                </a>
            </div>
        </div>

        <div class="home-promo__slider">
            <div class="home-promo__slider-inner">
                <div id="promoTrack" class="home-promo__track">
                    @foreach($featuredMovies as $movie)
                        <div class="home-promo__featured-card"
                             data-poster="{{ $movie->poster_url ?? asset('images/movie-placeholder.svg') }}"
                             data-title="{{ $movie->name }}"
                             data-slug="{{ $movie->slug }}">
                            <div class="home-promo__featured-overlay"></div>
                            <div class="home-promo__featured-poster">
                                <img
                                    src="{{ $movie->poster_url ?? asset('images/movie-placeholder.svg') }}"
                                    alt="{{ $movie->name }}"
                                    class="home-promo__featured-image"
                                    loading="lazy"
                                    onerror="this.onerror=null; this.src='{{ asset('images/movie-placeholder.svg') }}';"
                                >
                                @if($movie->imdb_score)
                                    <div class="home-promo__featured-rating">
                                        <span class="home-promo__featured-rating-icon">★</span>
                                        <span
                                            class="home-promo__featured-rating-value">{{ number_format($movie->imdb_score, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="home-promo__featured-info">
                                <h3 class="home-promo__featured-title">{{ $movie->name }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <button id="promoPrev" class="home-promo__nav-btn" aria-label="Попередній">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button id="promoNext" class="home-promo__nav-btn" aria-label="Наступний">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>

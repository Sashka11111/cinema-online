<div class="similar-movies">
    <h2 class="similar-movies__title">Схожі фільми</h2>
    <div class="similar-movies__list">
        @foreach($movies as $similarMovie)
            <div class="similar-movie-card">
                <a href="{{ route('movies.show', $similarMovie) }}"
                   class="similar-movie-card__link">
                    <div class="similar-movie-card__poster-container">
                        <img
                            src="{{ $similarMovie->poster ? asset('storage/' . $similarMovie->poster) : asset('images/no-image.svg') }}"
                            alt="{{ $similarMovie->name }}"
                            class="similar-movie-card__poster"
                        >
                    </div>
                    <div class="similar-movie-card__info">
                        <div class="similar-movie-card__title">{{ $similarMovie->name }}</div>
                        @if(isset($similarMovie->first_air_date) && $similarMovie->first_air_date)
                            <div
                                class="similar-movie-card__year">{{ date('Y', strtotime($similarMovie->first_air_date)) }}</div>
                        @endif
                    </div>
                </a>
            </div>
        @endforeach
    </div>
</div>

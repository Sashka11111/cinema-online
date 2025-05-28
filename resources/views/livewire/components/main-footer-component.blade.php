<footer class="main-footer">
    <div class="main-footer__container">
        <div class="main-footer__content-types">
            <div class="main-footer__brand">
                <a href="{{ route('home') }}" class="main-footer__logo">
                    <span class="main-footer__logo-text">Cinema</span>
                </a>
                <p class="main-footer__slogan">Відкрийте для себе світ кіно</p>
            </div>
            @if(isset($contentTypesWithMovies) && count($contentTypesWithMovies) > 0)
                @foreach($contentTypesWithMovies as $contentType => $movies)
                    <div class="main-footer__content-type-block">
                        <h3 class="main-footer__heading">{{ $contentType }}</h3>
                        <ul class="main-footer__movie-list">
                            @foreach($movies as $movie)
                                <li class="main-footer__movie-item">
                                    <a href="{{ route('movies.show', $movie) }}" wire:navigate
                                       class="main-footer__movie-link">
                                        {{ $movie->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            @else
                <div class="main-footer__empty">
                    <p>Немає доступних фільмів.</p>
                </div>
            @endif
        </div>

        <div class="main-footer__bottom">
            <div class="main-footer__copyright">
                <p>© {{ date('Y') }} Cinema. Всі права захищені.</p>
            </div>
            <div class="main-footer__links">
                <a href="{{ route('privacy-policy') }}" wire:navigate class="main-footer__link">Політика
                    конфіденційності</a>
                <a href="{{route('terms-of-use')}}" wire:navigate class="main-footer__link">Умови
                    використання</a>
                <a href="{{route('cookie-policy')}}" wire:navigate class="main-footer__link">Політика
                    щодо файлів
                    cookie</a>
            </div>
        </div>
    </div>
</footer>

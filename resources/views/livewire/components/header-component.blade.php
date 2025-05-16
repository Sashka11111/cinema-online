<header class="header">
    <div class="header__container">
        <div class="header__logo">
            <a href="{{ route('home') }}" class="header__logo-link">
                <span class="header__logo-text">Cinema</span>
            </a>
        </div>

        <nav class="header__nav">
            <ul class="header__nav-list">
                <li class="header__nav-item">
                    <a href="{{ route('home') }}"
                       class="header__nav-link {{ request()->routeIs('home') ? 'header__nav-link--active' : '' }}">Головна</a>
                </li>
                <li class="header__nav-item">
                    <a href="{{ route('movies') }}"
                       class="header__nav-link {{ request()->routeIs('movies*') ? 'header__nav-link--active' : '' }}">Фільми</a>
                </li>
                <li class="header__nav-item">
                    <a href="#"
                       class="header__nav-link {{ request()->routeIs('series*') ? 'header__nav-link--active' : '' }}">Серіали</a>
                </li>
                <li class="header__nav-item">
                    <a href="#"
                       class="header__nav-link {{ request()->routeIs('cartoons*') ? 'header__nav-link--active' : '' }}">Мультфільми</a>
                </li>
                <li class="header__nav-item">
                    <a href="#"
                       class="header__nav-link {{ request()->routeIs('cartoon-series*') ? 'header__nav-link--active' : '' }}">Мультсеріали</a>
                </li>
                <li class="header__nav-item">
                    <a href="#"
                       class="header__nav-link {{ request()->routeIs('anime*') ? 'header__nav-link--active' : '' }}">Аніме</a>
                </li>
                <li class="header__nav-item">
                    <a href="#"
                       class="header__nav-link {{ request()->routeIs('selections*') ? 'header__nav-link--active' : '' }}">Підбірки</a>
                </li>
            </ul>
        </nav>

        <div class="header__actions">
            <div class="header__search">
                <button class="header__search-button">
                    <img src="{{ asset('images/search-icon.svg') }}" alt="Пошук"
                         class="header__search-icon">
                </button>
            </div>

            <livewire:components.theme-toggle/>

            @auth
                <div class="header__user">
                    <button class="header__user-button">
                        <img
                            src="{{ auth()->user()->profile_photo_url ?? asset('images/default-avatar.png') }}"
                            alt="Аватар" class="header__user-avatar">
                    </button>
                    <div class="header__user-dropdown">
                        <a href="#" class="header__dropdown-item">Профіль</a>
                        <a href="#" class="header__dropdown-item">Мої фільми</a>
                        <a href="#" class="header__dropdown-item">Налаштування</a>
                        <hr class="header__dropdown-divider">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="header__dropdown-item header__dropdown-item--danger">
                                Вийти
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="header__auth">
                    <a href="{{ route('login') }}" class="header__auth-link">Увійти</a>
                    <a href="{{ route('register') }}" class="header__auth-button">Реєстрація</a>
                </div>
            @endauth
        </div>

        <button class="header__mobile-menu-button">
            <img src="{{ asset('images/menu-icon.svg') }}" alt="Меню"
                 class="header__mobile-menu-icon">
        </button>
    </div>
</header>

<header class="header">
    <div class="header__container">
        <div class="header__logo">
            <a href="{{ route('home') }}" wire:navigate class="header__logo-link">
                <span class="header__logo-text">Cinema</span>
            </a>
        </div>

        <nav class="header__nav">
            <ul class="header__nav-list">
                @foreach ($routes as $route)
                    <li class="header__nav-item">
                        <a href="{{ route($route['name']) }}"
                           wire:navigate
                           class="header__nav-link {{ request()->routeIs($route['name']) ? 'header__nav-link--active' : '' }}">
                            {{ $route['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="header__actions">
            <livewire:components.theme-toggle/>

            @auth
                <div class="header__user">
                    <button class="header__user-button">
                        @if(auth()->user()->profile_photo_url ?? false)
                            <img src="{{ auth()->user()->profile_photo_url }}" alt="Аватар"
                                 class="header__user-avatar">
                        @else
                            <div class="header__user-icon"></div>
                        @endif
                    </button>
                    <div class="header__user-dropdown">
                        <a href="{{ route('profile') }}" wire:navigate
                           class="header__dropdown-item">Профіль</a>
                        <a href="#" class="header__dropdown-item">Мої списки</a>
                        <a href="#" class="header__dropdown-item">Налаштування</a>
                        <hr class="header__dropdown-divider">
                        <livewire:components.logout-button/>

                    </div>
                </div>
            @else
                <div class="header__auth">
                    <a href="{{ route('login') }}" wire:navigate
                       class="header__auth-link">Увійти</a>
                    <a href="{{ route('register') }}" wire:navigate
                       class="header__auth-button">Зареєструватися</a>
                </div>
            @endauth
        </div>

        <button class="header__mobile-menu-button">
            <img src="{{ asset('images/menu-icon.svg') }}" alt="Меню"
                 class="header__mobile-menu-icon">
        </button>
    </div>
</header>

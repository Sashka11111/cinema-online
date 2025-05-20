<div class="profile-page">
    <livewire:components.header-component/>

    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-header__cover"></div>
            <div class="profile-header__user">
                <div class="profile-header__photo-container">
                    @if($profilePhoto)
                        <img src="{{ $profilePhoto }}" alt="{{ $name }}"
                             class="profile-header__photo">
                    @else
                        <div class="profile-header__photo-placeholder">
                            {{ substr($name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="profile-header__info">
                    <h1 class="profile-header__name">{{ $name }}</h1>
                    <div class="profile-header__stats">
                        <div class="profile-header__stat">
                            <span class="profile-header__stat-value">{{ $watchedMovies }}</span>
                            <span class="profile-header__stat-label">Фільмів</span>
                        </div>
                        <div class="profile-header__stat">
                            <span class="profile-header__stat-value">{{ $watchedSeries }}</span>
                            <span class="profile-header__stat-label">Серіалів</span>
                        </div>
                        <div class="profile-header__stat">
                            <span
                                class="profile-header__stat-value">{{ floor($totalWatchTime / 60) }}</span>
                            <span class="profile-header__stat-label">Годин</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-sidebar">
                <div class="profile-nav">
                    <button class="profile-nav__item profile-nav__item--active" x-data
                            @click="$dispatch('show-tab', 'profile')">
                        <div class="profile-nav__icon profile-nav__icon--profile"></div>
                        <span>Профіль</span>
                    </button>
                    <button class="profile-nav__item" x-data
                            @click="$dispatch('show-tab', 'security')">
                        <div class="profile-nav__icon profile-nav__icon--security"></div>
                        <span>Безпека</span>
                    </button>
                    <button class="profile-nav__item" x-data
                            @click="$dispatch('show-tab', 'settings')">
                        <div class="profile-nav__icon profile-nav__icon--settings"></div>
                        <span>Налаштування</span>
                    </button>
                    <button class="profile-nav__item" x-data
                            @click="$dispatch('show-tab', 'watchlist')">
                        <div class="profile-nav__icon profile-nav__icon--watchlist"></div>
                        <span>Мої списки</span>
                    </button>
                    <button class="profile-nav__item" x-data
                            @click="$dispatch('show-tab', 'history')">
                        <div class="profile-nav__icon profile-nav__icon--history"></div>
                        <span>Історія переглядів</span>
                    </button>
                </div>

                <div class="profile-genres">
                    <h3 class="profile-genres__title">Улюблені жанри</h3>
                    <div class="profile-genres__list">
                        @forelse($favoriteGenres as $genre)
                            <div class="profile-genres__item">
                                <span class="profile-genres__name">{{ $genre->name }}</span>
                                <div class="profile-genres__bar">
                                    <div class="profile-genres__progress"
                                         style="width: {{ $genre->percentage }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="profile-genres__empty">Почніть дивитися фільми, щоб побачити
                                свої улюблені жанри</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="profile-tabs">
                <div class="profile-tab" x-data="{ shown: true }" x-show="shown"
                     @show-tab.window="shown = $event.detail === 'profile'">
                    <div class="profile-card">
                        <h2 class="profile-card__title">Особиста інформація</h2>

                        @if(session('message'))
                            <div class="profile-alert profile-alert--success">
                                {{ session('message') }}
                            </div>
                        @endif

                        <form wire:submit.prevent="updateProfile" class="profile-form">
                            <div class="profile-form__group">
                                <label for="name" class="profile-form__label">Ім'я</label>
                                <input type="text" id="name" wire:model="name"
                                       class="profile-form__input">
                                @error('name') <span
                                    class="profile-form__error">{{ $message }}</span> @enderror
                            </div>

                            <div class="profile-form__group">
                                <label for="email" class="profile-form__label">Email</label>
                                <input type="email" id="email" wire:model="email"
                                       class="profile-form__input">
                                @error('email') <span
                                    class="profile-form__error">{{ $message }}</span> @enderror
                            </div>

                            {{--                            <div class="profile-form__group">--}}
                            {{--                                <label for="bio" class="profile-form__label">Про себе</label>--}}
                            {{--                                <textarea id="bio" wire:model="bio" class="profile-form__textarea"--}}
                            {{--                                          rows="4"></textarea>--}}
                            {{--                                @error('bio') <span--}}
                            {{--                                    class="profile-form__error">{{ $message }}</span> @enderror--}}
                            {{--                            </div>--}}

                            <div class="profile-form__group">
                                <label class="profile-form__label">Фото профілю</label>
                                <div class="profile-form__photo">
                                    @if($profilePhoto)
                                        <img src="{{ $profilePhoto }}" alt="{{ $name }}"
                                             class="profile-form__photo-preview">
                                    @else
                                        <div class="profile-form__photo-placeholder">
                                            {{ substr($name, 0, 1) }}
                                        </div>
                                    @endif

                                    <div class="profile-form__photo-actions">
                                        <label for="newProfilePhoto"
                                               class="profile-form__photo-button">
                                            Змінити фото
                                        </label>
                                        <input type="file" id="newProfilePhoto"
                                               wire:model="newProfilePhoto"
                                               class="profile-form__photo-input">
                                    </div>
                                </div>
                                @error('newProfilePhoto') <span
                                    class="profile-form__error">{{ $message }}</span> @enderror
                            </div>

                            <div class="profile-form__actions">
                                <button type="submit" class="profile-form__button">Зберегти зміни
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="profile-tab" x-data="{ shown: false }" x-show="shown"
                     @show-tab.window="shown = $event.detail === 'security'">
                    <div class="profile-card">
                        <h2 class="profile-card__title">Зміна пароля</h2>

                        @if(session('password_message'))
                            <div class="profile-alert profile-alert--success">
                                {{ session('password_message') }}
                            </div>
                        @endif

                        <form wire:submit.prevent="updatePassword" class="profile-form">
                            <div class="profile-form__group">
                                <label for="currentPassword" class="profile-form__label">Поточний
                                    пароль</label>
                                <input type="password" id="currentPassword"
                                       wire:model="currentPassword" class="profile-form__input">
                                @error('currentPassword') <span
                                    class="profile-form__error">{{ $message }}</span> @enderror
                            </div>

                            <div class="profile-form__group">
                                <label for="newPassword" class="profile-form__label">Новий
                                    пароль</label>
                                <input type="password" id="newPassword" wire:model="newPassword"
                                       class="profile-form__input">
                                @error('newPassword') <span
                                    class="profile-form__error">{{ $message }}</span> @enderror
                            </div>

                            <div class="profile-form__group">
                                <label for="newPasswordConfirmation" class="profile-form__label">Підтвердження
                                    пароля</label>
                                <input type="password" id="newPasswordConfirmation"
                                       wire:model="newPasswordConfirmation"
                                       class="profile-form__input">
                            </div>

                            <div class="profile-form__actions">
                                <button type="submit" class="profile-form__button">Змінити пароль
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="profile-card">
                        <h2 class="profile-card__title">Сеанси</h2>
                        <div class="profile-sessions">
                            <div class="profile-session">
                                <div class="profile-session__info">
                                    <div class="profile-session__device">
                                        <div
                                            class="profile-session__device-icon profile-session__device-icon--desktop"></div>
                                        <div class="profile-session__device-details">
                                            <span class="profile-session__device-name">Windows 10 - Chrome</span>
                                            <span class="profile-session__device-location">Київ, Україна</span>
                                        </div>
                                    </div>
                                    <div class="profile-session__status">
                                        <span
                                            class="profile-session__status-badge profile-session__status-badge--active">Активний</span>
                                        <span class="profile-session__status-time">Зараз</span>
                                    </div>
                                </div>
                                <button
                                    class="profile-session__button profile-session__button--current"
                                    disabled>Поточний сеанс
                                </button>
                            </div>

                            <div class="profile-session">
                                <div class="profile-session__info">
                                    <div class="profile-session__device">
                                        <div
                                            class="profile-session__device-icon profile-session__device-icon--mobile"></div>
                                        <div class="profile-session__device-details">
                                            <span class="profile-session__device-name">iPhone - Safari</span>
                                            <span class="profile-session__device-location">Київ, Україна</span>
                                        </div>
                                    </div>
                                    <div class="profile-session__status">
                                        <span class="profile-session__status-time">Останній вхід: 2 дні тому</span>
                                    </div>
                                </div>
                                <button
                                    class="profile-session__button profile-session__button--terminate">
                                    Завершити сеанс
                                </button>
                            </div>
                        </div>

                        <div class="profile-form__actions">
                            <button type="button"
                                    class="profile-form__button profile-form__button--danger">
                                Завершити всі інші сеанси
                            </button>
                        </div>
                    </div>
                </div>

                <div class="profile-tab" x-data="{ shown: false }" x-show="shown"
                     @show-tab.window="shown = $event.detail === 'settings'">
                    <div class="profile-card">
                        <h2 class="profile-card__title">Налаштування</h2>

                        @if(session('settings_message'))
                            <div class="profile-alert profile-alert--success">
                                {{ session('settings_message') }}
                            </div>
                        @endif

                        <form wire:submit.prevent="updateSettings" class="profile-form">
                            <div class="profile-form__section">
                                <h3 class="profile-form__section-title">Сповіщення</h3>

                                <div class="profile-form__toggle">
                                    <label for="emailNotifications"
                                           class="profile-form__toggle-label">
                                        <span>Email сповіщення</span>
                                        <span class="profile-form__toggle-description">Отримувати сповіщення про нові фільми та серіали на email</span>
                                    </label>
                                    <div class="profile-form__toggle-switch">
                                        <input type="checkbox" id="emailNotifications"
                                               wire:model="emailNotifications"
                                               class="profile-form__toggle-input">
                                        <span class="profile-form__toggle-slider"></span>
                                    </div>
                                </div>

                                <div class="profile-form__toggle">
                                    <label for="pushNotifications"
                                           class="profile-form__toggle-label">
                                        <span>Push сповіщення</span>
                                        <span class="profile-form__toggle-description">Отримувати push-сповіщення про нові фільми та серіали</span>
                                    </label>
                                    <div class="profile-form__toggle-switch">
                                        <input type="checkbox" id="pushNotifications"
                                               wire:model="pushNotifications"
                                               class="profile-form__toggle-input">
                                        <span class="profile-form__toggle-slider"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-form__section">
                                <h3 class="profile-form__section-title">Приватність</h3>

                                <div class="profile-form__toggle">
                                    <label for="privateProfile" class="profile-form__toggle-label">
                                        <span>Приватний профіль</span>
                                        <span class="profile-form__toggle-description">Ваш профіль буде видно тільки вам</span>
                                    </label>
                                    <div class="profile-form__toggle-switch">
                                        <input type="checkbox" id="privateProfile"
                                               wire:model="privateProfile"
                                               class="profile-form__toggle-input">
                                        <span class="profile-form__toggle-slider"></span>
                                    </div>
                                </div>

                                <div class="profile-form__toggle">
                                    <label for="showWatchHistory"
                                           class="profile-form__toggle-label">
                                        <span>Історія переглядів</span>
                                        <span class="profile-form__toggle-description">Показувати історію переглядів іншим користувачам</span>
                                    </label>
                                    <div class="profile-form__toggle-switch">
                                        <input type="checkbox" id="showWatchHistory"
                                               wire:model="showWatchHistory"
                                               class="profile-form__toggle-input">
                                        <span class="profile-form__toggle-slider"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-form__actions">
                                <button type="submit" class="profile-form__button">Зберегти
                                    налаштування
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="profile-tab" x-data="{ shown: false }" x-show="shown"
                     @show-tab.window="shown = $event.detail === 'watchlist'">
                    <div class="profile-card">
                        <h2 class="profile-card__title">Мої списки</h2>

                        <div class="profile-watchlist">
                            <div class="profile-watchlist__tabs">
                                <button
                                    class="profile-watchlist__tab profile-watchlist__tab--active">
                                    Хочу подивитись
                                </button>
                                <button class="profile-watchlist__tab">Улюблені</button>
                                <button class="profile-watchlist__tab">Переглянуті</button>
                            </div>

                            <div class="profile-watchlist__content">
                                <div class="profile-watchlist__grid">
                                    <!-- Тут буде список фільмів -->
                                    <div class="profile-movie-card">
                                        <div class="profile-movie-card__poster">
                                            <img src="https://via.placeholder.com/150x225"
                                                 alt="Movie Title"
                                                 class="profile-movie-card__image">
                                            <div class="profile-movie-card__rating">8.5</div>
                                        </div>
                                        <div class="profile-movie-card__content">
                                            <h3 class="profile-movie-card__title">Назва фільму</h3>
                                            <p class="profile-movie-card__year">2023</p>
                                        </div>
                                        <button class="profile-movie-card__remove">
                                            <span class="profile-movie-card__remove-icon"></span>
                                        </button>
                                    </div>

                                    <!-- Повторити для інших фільмів -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="profile-tab" x-data="{ shown: false }" x-show="shown"
                     @show-tab.window="shown = $event.detail === 'history'">
                    <div class="profile-card">
                        <h2 class="profile-card__title">Історія переглядів</h2>

                        <div class="profile-history">
                            <div class="profile-history__filters">
                                <div class="profile-history__filter">
                                    <select class="profile-history__select">
                                        <option value="all">Всі типи</option>
                                        <option value="movies">Фільми</option>
                                        <option value="series">Серіали</option>
                                    </select>
                                </div>

                                <div class="profile-history__filter">
                                    <select class="profile-history__select">
                                        <option value="recent">Нещодавні</option>
                                        <option value="oldest">Найстаріші</option>
                                        <option value="rating">За рейтингом</option>
                                    </select>
                                </div>
                            </div>

                            <div class="profile-history__timeline">
                                <div class="profile-history__day">
                                    <div class="profile-history__day-header">
                                        <h3 class="profile-history__day-title">Сьогодні</h3>
                                    </div>

                                    <div class="profile-history__items">
                                        <div class="profile-history__item">
                                            <div class="profile-history__item-time">20:30</div>
                                            <div class="profile-history__item-poster">
                                                <img src="https://via.placeholder.com/60x90"
                                                     alt="Movie Title"
                                                     class="profile-history__item-image">
                                            </div>
                                            <div class="profile-history__item-info">
                                                <h4 class="profile-history__item-title">Назва
                                                    фільму</h4>
                                                <p class="profile-history__item-meta">2023 • 2 год
                                                    15 хв</p>
                                            </div>
                                            <div class="profile-history__item-actions">
                                                <button
                                                    class="profile-history__item-action profile-history__item-action--favorite"></button>
                                                <button
                                                    class="profile-history__item-action profile-history__item-action--remove"></button>
                                            </div>
                                        </div>

                                        <!-- Повторити для інших переглядів -->
                                    </div>
                                </div>

                                <!-- Повторити для інших днів -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <livewire:components.main-footer-component/>
</div>

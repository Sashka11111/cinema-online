<div class="user-profile">
    <livewire:components.header-component/>

    <div class="user-profile__container">
        <div class="user-profile__header">
            <div class="user-profile__cover"></div>
            <div class="user-profile__user">
                <div class="user-profile__avatar-container">
                    @if($avatar)
                        <img src="{{ asset('storage/' . $avatar) }}" alt="{{ $name }}"
                             class="user-profile__avatar">
                    @else
                        <div class="user-profile__avatar-placeholder">
                            {{ substr($name, 0, 1) }}
                        </div>
                    @endif
                </div>
                <div class="user-profile__info">
                    <h1 class="user-profile__name">{{ $name }}</h1>
                    <div class="user-profile__badges">
                        @if($email_verified_at)
                            <div class="user-profile__badge user-profile__badge--verified">
                                <svg class="user-profile__badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Підтверджений
                            </div>
                        @endif

                        @if(auth()->user()->isAdmin())
                            <div class="user-profile__badge user-profile__badge--admin">
                                <svg class="user-profile__badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Адміністратор
                            </div>
                        @elseif(auth()->user()->isModerator())
                            <div class="user-profile__badge user-profile__badge--moderator">
                                <svg class="user-profile__badge-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z M20.618 5.984A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Модератор
                            </div>
                        @endif
                    </div>

                    @if(auth()->user()->isAdminOrModerator())
                        <div class="user-profile__admin-actions">
                            <a href="/admin" class="user-profile__admin-button">
                                <svg class="user-profile__admin-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 616 0z"></path>
                                </svg>
                                @if(auth()->user()->isAdmin())
                                    Панель адміністратора
                                @else
                                    Панель модератора
                                @endif
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="user-profile__content">
            <div class="user-profile__form-card">
                <h2 class="user-profile__form-title">Особиста інформація</h2>

                @if(session('message'))
                    <div class="user-profile__alert user-profile__alert--success">
                        <svg class="user-profile__alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('message') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="user-profile__alert user-profile__alert--error">
                        <svg class="user-profile__alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="user-profile__alert user-profile__alert--error">
                        <svg class="user-profile__alert-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <strong>Помилки валідації:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form wire:submit.prevent="updateProfile" class="user-profile__form">
                    <div class="user-profile__form-grid">
                        <div class="user-profile__field">
                            <label for="name" class="user-profile__label">Ім'я користувача</label>
                            <input type="text" id="name" wire:model="name"
                                   class="user-profile__input" required>
                            @error('name')
                                <span class="user-profile__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="user-profile__field">
                            <label for="email" class="user-profile__label">Email адреса</label>
                            <div class="user-profile__input-wrapper">
                                <input type="email" id="email" wire:model="email"
                                       class="user-profile__input">
                            </div>
                            @error('email')
                                <span class="user-profile__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="user-profile__field">
                            <label for="birthday" class="user-profile__label">Дата народження</label>
                            <input type="date" id="birthday" wire:model="birthday"
                                   class="user-profile__input">
                            @error('birthday')
                                <span class="user-profile__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="user-profile__field">
                            <label for="gender" class="user-profile__label">Стать</label>
                            <select id="gender" wire:model="gender" class="user-profile__select">
                                <option value="">Не вказано</option>
                                <option value="male">{{ __('gender.male') }}</option>
                                <option value="female">{{ __('gender.female') }}</option>
                                <option value="other">{{ __('gender.other') }}</option>
                            </select>
                            @error('gender')
                                <span class="user-profile__error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="user-profile__field user-profile__field--full">
                        <label for="description" class="user-profile__label">Опис профілю</label>
                        <textarea id="description" wire:model="description" rows="4"
                                  class="user-profile__textarea" maxlength="248"
                                  placeholder="Розкажіть про себе..."></textarea>
                        <div class="user-profile__counter">
                            <span x-text="$wire.description ? $wire.description.length : 0"></span>/248
                        </div>
                        @error('description')
                            <span class="user-profile__error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="user-profile__photos">
                        <div class="user-profile__field">
                            <label class="user-profile__label">Аватар профілю</label>
                            <div class="user-profile__photo user-profile__photo--avatar">
                                @if($avatar)
                                    <img src="{{ asset('storage/' . $avatar) }}" alt="{{ $name }}"
                                         class="user-profile__photo-preview">
                                @else
                                    <div class="user-profile__photo-placeholder">
                                        {{ substr($name, 0, 1) }}
                                    </div>
                                @endif

                                <div class="user-profile__photo-actions">
                                    <label for="newAvatar" class="user-profile__photo-button">
                                        <svg class="user-profile__photo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Змінити аватар
                                    </label>
                                    <input type="file" id="newAvatar" wire:model="newAvatar"
                                           class="user-profile__photo-input" accept="image/*">
                                </div>
                                <div wire:loading wire:target="newAvatar" class="user-profile__photo-loading">
                                    Завантаження...</div>
                            </div>
                            @error('newAvatar')
                                <span class="user-profile__error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="user-profile__field">
                            <label class="user-profile__label">Фонове зображення</label>
                            <div class="user-profile__photo user-profile__photo--backdrop">
                                @if($backdrop)
                                    <img src="{{ asset('storage/' . $backdrop) }}" alt="Фон"
                                         class="user-profile__photo-preview user-profile__photo-preview--backdrop">
                                @else
                                    <div class="user-profile__photo-placeholder user-profile__photo-placeholder--backdrop">
                                        <svg class="user-profile__photo-placeholder-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Фонове зображення
                                    </div>
                                @endif

                                <div class="user-profile__photo-actions">
                                    <label for="newBackdrop" class="user-profile__photo-button">
                                        <svg class="user-profile__photo-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Змінити фон
                                    </label>
                                    <input type="file" id="newBackdrop" wire:model="newBackdrop"
                                           class="user-profile__photo-input" accept="image/*">
                                </div>
                                <div wire:loading wire:target="newBackdrop" class="user-profile__photo-loading">
                                    Завантаження...
                                </div>
                            </div>
                            @error('newBackdrop')
                                <span class="user-profile__error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="user-profile__settings">
                        <h3 class="user-profile__settings-title">Налаштування перегляду</h3>

                        <div class="user-profile__settings-grid">
                            <div class="user-profile__setting">
                                <label class="user-profile__setting-label">
                                    <input type="checkbox" wire:model="allow_adult" class="user-profile__setting-checkbox">
                                    <span class="user-profile__setting-text">Дозволити контент 18+</span>
                                </label>
                                @error('allow_adult')
                                    <span class="user-profile__error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="user-profile__setting">
                                <label class="user-profile__setting-label">
                                    <input type="checkbox" wire:model="is_auto_play" class="user-profile__setting-checkbox">
                                    <span class="user-profile__setting-text">Автоматичне відтворення</span>
                                </label>
                                @error('is_auto_play')
                                    <span class="user-profile__error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="user-profile__setting">
                                <label class="user-profile__setting-label">
                                    <input type="checkbox" wire:model="is_auto_next" class="user-profile__setting-checkbox">
                                    <span class="user-profile__setting-text">Автоматичне відтворення наступного епізоду</span>
                                </label>
                                @error('is_auto_next')
                                    <span class="user-profile__error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="user-profile__setting">
                                <label class="user-profile__setting-label">
                                    <input type="checkbox" wire:model="is_auto_skip_intro" class="user-profile__setting-checkbox">
                                    <span class="user-profile__setting-text">Автоматичний пропуск інтро</span>
                                </label>
                                @error('is_auto_skip_intro')
                                    <span class="user-profile__error">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="user-profile__setting">
                                <label class="user-profile__setting-label">
                                    <input type="checkbox" wire:model="is_private_favorites" class="user-profile__setting-checkbox">
                                    <span class="user-profile__setting-text">Приватні улюблені</span>
                                </label>
                                @error('is_private_favorites')
                                    <span class="user-profile__error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="user-profile__actions">
                        <button type="submit" class="user-profile__button user-profile__button--primary"
                                wire:loading.attr="disabled" wire:target="updateProfile">
                            <svg class="user-profile__button-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 wire:loading.remove wire:target="updateProfile">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg class="user-profile__button-icon animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                 wire:loading wire:target="updateProfile">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            <span wire:loading.remove wire:target="updateProfile">Зберегти зміни</span>
                            <span wire:loading wire:target="updateProfile">Збереження...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <livewire:components.main-footer-component/>
</div>

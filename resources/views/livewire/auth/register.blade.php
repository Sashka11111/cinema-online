<div class="auth-page">
    <div class="auth-page__container">
        <div class="auth-page__form-section">
            <livewire:components.logo-component/>

            <p class="auth-page__subtitle">Приєднуйтесь до нас, щоб насолоджуватися різноманітними
                шоу та фільмами!</p>

            @if (session('status'))
                <div class="auth-page__status">{{ session('status') }}</div>
            @endif
            @if (session('error'))
                <div class="auth-form__error">{{ session('error') }}</div>
            @endif

            <form wire:submit.prevent="register" class="auth-form">
                <div class="auth-form__group">
                    <label for="name" class="auth-form__label">Ім'я</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        wire:model.live="name"
                        required
                        autocomplete="name"
                        class="auth-form__input"
                    >
                    @error('name') <span class="auth-form__error">{{ $message }}</span> @enderror
                </div>

                <div class="auth-form__group">
                    <label for="email" class="auth-form__label">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        wire:model.live="email"
                        required
                        autocomplete="username"
                        class="auth-form__input"
                    >
                    @error('email') <span class="auth-form__error">{{ $message }}</span> @enderror
                </div>

                <div class="auth-form__group">
                    <label for="password" class="auth-form__label">Пароль</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        wire:model.live="password"
                        required
                        autocomplete="new-password"
                        class="auth-form__input"
                    >
                    @error('password') <span
                        class="auth-form__error">{{ $message }}</span> @enderror
                </div>

                <div class="auth-form__group">
                    <label for="password_confirmation" class="auth-form__label">Повторити
                        пароль</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        wire:model.live="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="auth-form__input"
                    >
                    @error('password_confirmation') <span
                        class="auth-form__error">{{ $message }}</span> @enderror
                </div>

                <div class="flex items-center justify-end">
                    <button type="submit" class="auth-form__button">Зареєструватися</button>
                </div>
            </form>

            <livewire:components.social-login-component/>

            <livewire:components.footer-component :route="route('auth.login')"
                                                  :text="'Вже зареєстровані?'"
                                                  :linkText="'Авторизуватися'"/>
        </div>

        <livewire:components.promo-section-component/>
    </div>
</div>

<div class="auth-page">
    <div class="auth-page__container">
        <div class="auth-page__form-section">
            <livewire:components.logo-component/>

            <p class="auth-page__subtitle">Приготуйтеся насолоджуватися різноманітними шоу, які
                будуть супроводжувати Ваше повсякденне життя!</p>
            <form wire:submit.prevent="login" class="auth-form">
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
                        autocomplete="current-password"
                        class="auth-form__input"
                    >
                    @error('password') <span
                        class="auth-form__error">{{ $message }}</span> @enderror
                </div>

                @if (session('status'))
                    <div class="auth-page__status">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="auth-form__error">{{ session('error') }}</div>
                @endif

                <div class="auth-form__checkbox-group">
                    <input wire:model.live="remember" id="remember" type="checkbox"
                           class="auth-form__checkbox">
                    <label for="remember" class="auth-page__checkbox-label">Запам'ятати мене</label>
                </div>
                <div class="auth-form__forgot-password">
                    <a href="{{ route('password.request') }}" class="auth-form__forgot-link">Забули
                        пароль?</a>
                </div>

                <button type="submit" class="auth-form__button">Ввійти</button>
            </form>

            <livewire:components.social-login-component/>

            <livewire:components.footer-component
                :route="route('register')"
                :text="'Не маєте облікового запису?'"
                :linkText="'Зареєструватися'"
            />
        </div>

        <livewire:components.promo-section-component/>
    </div>
</div>

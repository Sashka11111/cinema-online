<div class="auth-page">
    <div class="auth-page__container">
        <div class="auth-page__form-section">
            <livewire:components.logo-component/>

            <h2 class="auth-page__title">Скидання пароля</h2>
            <p class="auth-page__subtitle">Введіть новий пароль для вашого облікового запису.</p>

            <form wire:submit.prevent="performReset" class="auth-form">
                @if (session('message'))
                    <div class="auth-page__status">{{ session('message') }}</div>
                @endif
                <div class="auth-form__group">
                    <label for="password" class="auth-form__label">Новий пароль</label>
                    <input
                        id="password"
                        type="password"
                        wire:model="password"
                        class="auth-form__input"
                        required
                        autocomplete="new-password"
                    >
                </div>
                @error('password')
                <div class="auth-form__error">{{ $message }}</div>
                @enderror
                <div class="auth-form__group">
                    <label for="password_confirmation" class="auth-form__label">Підтвердити
                        пароль</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        wire:model="password_confirmation"
                        class="auth-form__input"
                        required
                        autocomplete="new-password"
                    >
                </div>
                @error('token')
                <div class="auth-form__error">{{ $message }}</div>
                @enderror
                <button type="submit" class="auth-form__button">Змінити пароль</button>
            </form>

            <livewire:components.footer-component
                :route="route('login')"
                :text="'Повернутися до входу?'"
                :linkText="'Авторизуватися'"
            />
        </div>
        <livewire:components.promo-section-component/>
    </div>
</div>

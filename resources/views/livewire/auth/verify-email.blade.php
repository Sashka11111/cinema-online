<div class="auth-page">
    <div class="auth-page__container">
        <div class="auth-page__form-section">
            <livewire:components.logo-component/>

            <p class="auth-page__subtitle">Дякуємо за реєстрацію! Будь ласка, перевірте ваш email
                для верифікації.</p>

            <form wire:submit.prevent="resend" class="auth-form">
                @if (session('message'))
                    <div class="auth-page__status">{{ session('message') }}</div>
                @endif
                @if (session('error'))
                    <div class="auth-form__error">{{ session('error') }}</div>
                @endif

                <button type="submit" class="auth-form__button">Надіслати повторне посилання
                </button>
            </form>

            <livewire:components.footer-component
                :route="route('auth.login')"
                :text="'Повернутися до входу?'"
                :linkText="'Увійти'"
            />
        </div>

        <livewire:components.promo-section-component/>
    </div>
</div>

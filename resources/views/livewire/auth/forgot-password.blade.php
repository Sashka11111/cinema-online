<div class="auth-page">
    <div class="auth-page__container">
        <div class="auth-page__form-section">
            <livewire:components.logo-component/>

            <p class="auth-page__subtitle">Забули свій пароль? Нічого. Просто повідомте нам свою
                електронну адресу, і ми надішлемо вам посилання, яке дозволить змінити пароль.</p>

            <form wire:submit="sendResetLink" class="auth-form">
                <livewire:components.form-field-component
                    label="Email"
                    id="email"
                    type="email"
                    name="email"
                    wire:model="email"
                    required="true"
                    autocomplete="email"
                    :error="$errors->first('email')"
                />

                @if (session('status'))
                    <div class="auth-page__status">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="auth-form__error">{{ session('error') }}</div>
                @endif

                <button type="submit" class="auth-form__button">Надіслати посилання для скидання
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

<div class="verify-page">
    <div class="verify-card">
        <img src="{{ asset('images/verify.jpg') }}" alt="Відновлення пароля" class="verify-image">

        <h2 class="verify-title">Відновлення пароля</h2>
        <p class="verify-description">
            Забули свій пароль? Нічого страшного. Просто введіть свою електронну адресу, і ми
            надішлемо вам посилання для скидання пароля, яке дозволить вам створити новий.
        </p>

        <form wire:submit="sendResetLink" class="verify-form">
            <div class="verify-form__input-container">
                <input
                    id="email"
                    type="email"
                    name="email"
                    wire:model="email"
                    required
                    autocomplete="email"
                    class="verify-form__input"
                    placeholder="Ваша електронна адреса"
                >
                @error('email')
                <div class="verify-error">{{ $message }}</div>
                @enderror
            </div>

            @if (session('status'))
                <div class="verify-status">{{ session('status') }}</div>
            @endif

            @if (session('error'))
                <div class="verify-error">{{ session('error') }}</div>
            @endif

            <button type="submit" class="verify-button">
                Надіслати посилання для скидання
            </button>
        </form>

        <livewire:components.footer-component
            :route="route('login')"
            :text="'Повернутися до входу?'"
            :linkText="'Авторизуватися'"
        />
    </div>
</div>

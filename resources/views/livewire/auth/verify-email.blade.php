<div class="verify-page">
    <div class="verify-card">
        <img src="{{ asset('images/verify.jpg') }}" alt="Підтвердження Email" class="verify-image">

        <h2 class="verify-title">Підтвердження електронної пошти</h2>
        <p class="verify-description">
            Вітаємо на нашій платформі! Перш ніж продовжити, будь ласка, підтвердіть
            свою електронну адресу, натиснувши на посилання, яке ми щойно надіслали Вам. Якщо ви не
            отримали листа, ми із задоволенням надішлемо вам ще один.
        </p>
        <form wire:submit.prevent="resend" class="verify-form">
            @if (session('message'))
                <div class="verify-status">{{ session('message') }}</div>
            @endif
            @if (session('error'))
                <div class="verify-error">{{ session('error') }}</div>
            @endif
            <button type="submit" class="verify-button">
                Відправити лист із підтвердженням
            </button>
        </form>
        <livewire:components.footer-component
            :route="route('login')"
            :text="'Повернутися до входу?'"
            :linkText="'Авторизуватися'"
        />
    </div>
</div>

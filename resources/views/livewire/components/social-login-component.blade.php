<div class="auth-page__social-login">
    <p class="auth-page__social-login-text">АБО</p>
    <div class="auth-page__social-buttons">
        <!-- Telegram button from Socialite -->
        {!! Socialite::driver('telegram')->getButton() !!}
        <!-- Discord button -->
        <a class="auth-page__social-button auth-page__social-button--discord"
           href="{{ route('auth.redirect', 'discord') }}">
            <img src="{{ asset('images/discord.svg') }}" alt="Discord Icon"
                 class="auth-page__social-icon">
            Увійти через Discord
        </a>
        <!-- Google button -->
        <a class="auth-page__social-button auth-page__social-button--google"
           href="{{ route('auth.redirect', 'google') }}">
            <img src="{{ asset('images/google.png') }}" alt="Google Icon"
                 class="auth-page__social-icon">
            Увійти через Google
        </a>

    </div>
</div>

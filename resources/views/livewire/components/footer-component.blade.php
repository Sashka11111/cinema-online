<div class="auth-page__footer">
    <p class="auth-page__footer-text">
        {{ $text }}
        <a href="{{ $route }}" wire:navigate class="auth-page__footer-link" wire:navigate>
            {{ $linkText }}
        </a>
    </p>
</div>

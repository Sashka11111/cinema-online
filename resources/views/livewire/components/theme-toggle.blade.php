<div class="theme-toggle">
    <button wire:click="toggleTheme" class="theme-toggle__button" aria-label="Toggle theme">
        @if ($theme === 'light')
            <span class="theme-toggle__icon theme-toggle__icon--moon"></span>
        @else
            <span class="theme-toggle__icon theme-toggle__icon--sun"></span>
        @endif
    </button>
</div>

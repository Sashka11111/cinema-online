document.addEventListener('DOMContentLoaded', () => {
    const theme = localStorage.getItem('theme')
        || document.documentElement.getAttribute('data-theme') || 'light';
    document.documentElement.setAttribute('data-theme', theme);

    const themeToggleButton = document.querySelector('.theme-toggle__button');
    themeToggleButton?.addEventListener('click', () => {
        const newTheme = document.documentElement.getAttribute('data-theme')
        === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        Livewire.dispatch('toggleTheme');
    });
});

document.addEventListener('livewire:init', () => {
    Livewire.on('theme-changed', (event) => {
        document.documentElement.setAttribute('data-theme', event.theme);
        localStorage.setItem('theme', event.theme);
    });
});

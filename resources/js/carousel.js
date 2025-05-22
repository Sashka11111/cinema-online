function initTrendingSlider() {
    const
        track = document.getElementById('trendingTrack');
    const prevBtn = document.getElementById('trendingPrev');
    const nextBtn = document.getElementById('trendingNext');

    if (!track || !prevBtn || !nextBtn) {
        return;
    }

    const items = track.querySelectorAll('.trending-movie-card');
    if (items.length === 0) {
        return;
    }

    let itemWidth = items[0].offsetWidth + 16; // ширина + відступ
    const carousel = track.parentElement;
    let visibleItems = Math.floor(carousel.offsetWidth / itemWidth);
    let maxScroll = Math.max(0, items.length - visibleItems);
    let currentPosition = 0;

    function updateNavButtons() {
        prevBtn.disabled = currentPosition <= 0;
        nextBtn.disabled = currentPosition >= maxScroll;
    }

    function scrollTrack(direction) {
        if (direction === 'prev' && currentPosition > 0) {
            currentPosition--;
        } else if (direction === 'next' && currentPosition < maxScroll) {
            currentPosition++;
        }

        const offset = -currentPosition * itemWidth;
        track.style.transform = `translateX(${offset}px)`;
        updateNavButtons();
    }

    // Видаляємо старі обробники подій, щоб уникнути дублювання
    prevBtn.removeEventListener('click', () => scrollTrack('prev'));
    nextBtn.removeEventListener('click', () => scrollTrack('next'));

    // Додаємо нові обробники
    prevBtn.addEventListener('click', () => scrollTrack('prev'));
    nextBtn.addEventListener('click', () => scrollTrack('next'));

    // Ініціалізація
    updateNavButtons();

    // Повертаємо функцію для оновлення при зміні розміру вікна
    return function updateSlider() {
        itemWidth = items[0].offsetWidth + 16;
        visibleItems = Math.floor(carousel.offsetWidth / itemWidth);
        maxScroll = Math.max(0, items.length - visibleItems);

        if (currentPosition > maxScroll) {
            currentPosition = maxScroll;
        }

        const offset = -currentPosition * itemWidth;
        track.style.transform = `translateX(${offset}px)`;
        updateNavButtons();
    };
}

// Ініціалізація при завантаженні DOM
document.addEventListener('DOMContentLoaded', function () {
    const updateSlider = initTrendingSlider();
    if (updateSlider) {
        window.addEventListener('resize', updateSlider);
    }
});

// Ініціалізація після оновлення Livewire компонента
document.addEventListener('livewire:initialized', function () {
    const updateSlider = initTrendingSlider();
    if (updateSlider) {
        window.addEventListener('resize', updateSlider);
    }
});

// Ініціалізація після навігації Livewire
document.addEventListener('livewire:navigated', function () {
    const updateSlider = initTrendingSlider();
    if (updateSlider) {
        window.addEventListener('resize', updateSlider);
    }
});

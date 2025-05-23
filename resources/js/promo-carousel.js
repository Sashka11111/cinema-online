function initPromoSlider() {
    const track = document.getElementById('promoTrack');
    const prevBtn = document.getElementById('promoPrev');
    const nextBtn = document.getElementById('promoNext');
    const promoSection = document.querySelector('.home-promo');

    if (!track || !prevBtn || !nextBtn || !promoSection) {
        return;
    }

    const items = track.querySelectorAll('.home-promo__featured-card');
    if (items.length === 0) {
        return;
    }

    // Додаємо стрілочки для кнопок навігації
    prevBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>`;
    nextBtn.innerHTML = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>`;

    let itemWidth = items[0].offsetWidth + parseInt(
        getComputedStyle(items[0]).marginRight || 0);
    const carousel = track.parentElement;
    let visibleItems = Math.floor(carousel.offsetWidth / itemWidth);
    let maxScroll = Math.max(0, items.length - visibleItems);
    let currentPosition = 0;

    function updateActiveCard() {
        items.forEach((item, index) => {
            const isActive = index === currentPosition;
            item.classList.toggle('active', isActive);
            if (visibleItems === 1) {
                item.style.visibility = 'visible';
                item.style.opacity = isActive ? '1' : '0.7';
                item.style.transform = isActive ? 'scale(1)' : 'scale(0.9)';
                item.style.filter = isActive ? 'blur(0)' : 'blur(1px)';
            } else {
                item.style.visibility = 'visible';
                item.style.opacity = isActive ? '1' : '0.7';
                item.style.transform = isActive ? 'scale(1)' : 'scale(0.9)';
            }
        });
    }

    function updateBackground() {
        const activeItem = items[currentPosition];
        const backgroundUrl = activeItem.getAttribute('data-poster');
        if (backgroundUrl) {
            promoSection.style.backgroundImage = `linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('${backgroundUrl}')`;
            promoSection.style.backgroundSize = 'cover';
            promoSection.style.backgroundPosition = 'center';
        }

        // Оновлюємо кнопку перегляду
        const movieSlug = activeItem.getAttribute('data-slug');
        const watchButton = document.getElementById('promoWatchButton');
        if (watchButton && movieSlug) {
            watchButton.href = `/movies/${movieSlug}`;
        }
    }

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
        updateBackground();
        updateActiveCard();
    }

    // Видаляємо старі обробники подій, щоб уникнути дублювання
    prevBtn.removeEventListener('click', () => scrollTrack('prev'));
    nextBtn.removeEventListener('click', () => scrollTrack('next'));

    // Додаємо нові обробники
    prevBtn.addEventListener('click', () => scrollTrack('prev'));
    nextBtn.addEventListener('click', () => scrollTrack('next'));

    // Додаємо обробники для карток
    items.forEach((item, index) => {
        item.addEventListener('click', () => {
            currentPosition = index;
            const offset = -currentPosition * itemWidth;
            track.style.transform = `translateX(${offset}px)`;
            updateNavButtons();
            updateBackground();
            updateActiveCard();
        });
    });

    // Ініціалізація
    updateNavButtons();
    updateBackground();
    updateActiveCard();

    // Повертаємо функцію для оновлення при зміні розміру вікна
    return function updateSlider() {
        itemWidth = items[0].offsetWidth + parseInt(
            getComputedStyle(items[0]).marginRight || 0);
        visibleItems = Math.floor(carousel.offsetWidth / itemWidth);
        maxScroll = Math.max(0, items.length - visibleItems);

        if (currentPosition > maxScroll) {
            currentPosition = maxScroll;
        }

        const offset = -currentPosition * itemWidth;
        track.style.transform = `translateX(${offset}px)`;
        updateNavButtons();
        updateBackground();
        updateActiveCard();
    };
}

// Ініціалізація при завантаженні DOM
document.addEventListener('DOMContentLoaded', function () {
    const updateSlider = initPromoSlider();
    if (updateSlider) {
        window.addEventListener('resize', updateSlider);
    }
});

// Ініціалізація після оновлення Livewire компонента
document.addEventListener('livewire:initialized', function () {
    const updateSlider = initPromoSlider();
    if (updateSlider) {
        window.addEventListener('resize', updateSlider);
    }
});

// Ініціалізація після навігації Livewire
document.addEventListener('livewire:navigated', function () {
    const updateSlider = initPromoSlider();
    if (updateSlider) {
        window.addEventListener('resize', updateSlider);
    }
});

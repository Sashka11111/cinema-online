function initPromoSlider() {
    const track = document.getElementById('promoTrack');
    const prevBtn = document.getElementById('promoPrev');
    const nextBtn = document.getElementById('promoNext');
    const promoSection = document.querySelector('.home-promo');

    if (!track || !prevBtn || !nextBtn || !promoSection) {
        return;
    }
    prevBtn.innerHTML = `<img src="/images/arrow-left.svg" alt="Попередній" width="24" height="24">`;
    nextBtn.innerHTML = `<img src="/images/arrow-right.svg" alt="Наступний" width="24" height="24">`;
    const items = Array.from(
        track.querySelectorAll('.home-promo__featured-card'));
    const carousel = track.parentElement;

    let itemWidth = items[0].offsetWidth + parseInt(
        getComputedStyle(items[0]).marginRight || 0);
    let currentPosition = 0;
    const LEFT_SHIFT = 60;
    const LAST_CARD_SHIFT = 80;

    function getOffset() {
        const carouselWidth = carousel.offsetWidth;
        const totalWidth = itemWidth * items.length;
        const maxPosition = items.length - 1;

        // якщо карток менше ніж влізе в карусель — не зміщуємо
        if (totalWidth <= carouselWidth) {
            return 0;
        }

        if (currentPosition === 0) {
            // Перша картка повністю зліва
            return 0;
        } else if (currentPosition === maxPosition) {
            const totalWidth = itemWidth * items.length;
            return carouselWidth - totalWidth - LAST_CARD_SHIFT;
        } else {
            // Центруємо інші (додаємо легкий зсув вліво)
            return (carouselWidth / 2) - (itemWidth / 2) - (currentPosition
                * itemWidth) - LEFT_SHIFT;
        }
    }

    function updateActiveCard() {
        items.forEach((item, index) => {
            item.classList.toggle('active', index === currentPosition);
        });
    }

    function updateBackground() {
        const activeItem = items[currentPosition];
        const backgroundUrl = activeItem.getAttribute('data-poster');
        if (backgroundUrl) {
            promoSection.style.backgroundImage =
                `linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('${backgroundUrl}')`;
        }

        const movieSlug = activeItem.getAttribute('data-slug');
        const watchButton = document.getElementById('promoWatchButton');
        if (watchButton && movieSlug) {
            watchButton.href = `/movies/${movieSlug}`;
        }
    }

    function centerCard() {
        track.style.transition = 'transform 0.7s ease'; // плавніше
        track.style.transform = `translateX(${getOffset()}px)`;
        updateActiveCard();
        updateBackground();
        updateButtons();
    }

    function scroll(direction) {
        if (
            (direction === -1 && currentPosition === 0) ||
            (direction === 1 && currentPosition === items.length - 1)
        ) {
            return; // не дозволяємо вихід за межі
        }

        currentPosition += direction;
        centerCard();
    }

    function updateButtons() {
        prevBtn.disabled = currentPosition === 0;
        nextBtn.disabled = currentPosition === items.length - 1;
    }

    prevBtn.addEventListener('click', () => scroll(-1));
    nextBtn.addEventListener('click', () => scroll(1));

    items.forEach((item, index) => {
        item.addEventListener('click', () => {
            currentPosition = index;
            centerCard();
        });
    });

    // Початкова позиція
    centerCard();

    return function updateSlider() {
        itemWidth = items[0].offsetWidth + parseInt(
            getComputedStyle(items[0]).marginRight || 0);
        centerCard();
    };
}

document.addEventListener('DOMContentLoaded', function () {
    const updateSlider = initPromoSlider();
    if (updateSlider) {
        window.addEventListener('resize', updateSlider);
    }
});

document.addEventListener('livewire:initialized', function () {
    const updateSlider = initPromoSlider();
    if (updateSlider) {
        window.addEventListener('resize', updateSlider);
    }
});

document.addEventListener('livewire:navigated', function () {
    const updateSlider = initPromoSlider();
    if (updateSlider) {
        window.addEventListener('resize', updateSlider);
    }
});

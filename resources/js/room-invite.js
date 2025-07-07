// Відкриття модального вікна запрошення
function openInviteModal() {
    const modal = document.getElementById('inviteModal');
    if (modal) {
        modal.style.display = 'flex';
        generateInviteLink();
    }
}

// Закриття модального вікна запрошення
function closeInviteModal() {
    const modal = document.getElementById('inviteModal');
    if (modal) {
        modal.style.display = 'none';
    }
}

// Генерація посилання для запрошення
function generateInviteLink() {
    // Отримуємо дані з глобальної змінної
    const roomData = window.roomData;
    if (!roomData) {
        console.error('roomData not found');
        return;
    }

    console.log('roomData:', roomData);

    // Правильний URL згідно з маршрутами: /movies/{movie}/watch/{episodeNumber}/{room}
    const inviteLink = `${window.location.origin}/movies/${roomData.movieSlug}/watch/${roomData.episodeNumber}/${roomData.roomSlug}`;

    console.log('Generated invite link:', inviteLink);

    const inviteLinkInput = document.getElementById('inviteLink');
    if (inviteLinkInput) {
        inviteLinkInput.value = inviteLink;
        console.log('Set invite link input value:', inviteLinkInput.value);
    } else {
        console.error('inviteLink input not found');
    }
}

// Копіювання посилання запрошення
function copyInviteLink() {
    const inviteLink = document.getElementById('inviteLink');
    if (!inviteLink) return;

    inviteLink.select();
    inviteLink.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(inviteLink.value).then(() => {
        showCopySuccess('Посилання скопійовано!');
    }).catch(() => {
        // Fallback для старих браузерів
        try {
            document.execCommand('copy');
            showCopySuccess('Посилання скопійовано!');
        } catch (err) {
            console.error('Помилка копіювання:', err);
            showCopySuccess('Помилка копіювання');
        }
    });
}

// Генерація QR-коду
function generateQRCode() {
    const qrContainer = document.getElementById('qrCodeContainer');
    const qrCode = document.getElementById('qrCode');
    const inviteLink = document.getElementById('inviteLink')?.value;

    if (!qrContainer || !qrCode || !inviteLink) return;

    if (qrContainer.style.display === 'none') {
        // Показуємо QR-код
        qrContainer.style.display = 'block';

        // Очищуємо попередній QR-код
        qrCode.innerHTML = '';

        // Генеруємо QR-код через API
        const qrApiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(inviteLink)}`;
        const qrImg = document.createElement('img');
        qrImg.src = qrApiUrl;
        qrImg.alt = 'QR код для запрошення';
        qrImg.className = 'room-watch__qr-image';
        qrImg.onerror = () => {
            qrCode.innerHTML = '<p style="color: red;">Помилка завантаження QR-коду</p>';
        };
        qrCode.appendChild(qrImg);
    } else {
        // Приховуємо QR-код
        qrContainer.style.display = 'none';
    }
}

// Копіювання пароля кімнати
function copyRoomPassword() {
    const roomPassword = document.getElementById('roomPassword');
    if (!roomPassword) return;

    roomPassword.select();
    roomPassword.setSelectionRange(0, 99999);

    navigator.clipboard.writeText(roomPassword.value).then(() => {
        showCopySuccess('Пароль скопійовано!');
    }).catch(() => {
        try {
            document.execCommand('copy');
            showCopySuccess('Пароль скопійовано!');
        } catch (err) {
            console.error('Помилка копіювання пароля:', err);
            showCopySuccess('Помилка копіювання');
        }
    });
}

// Показ повідомлення про успішне копіювання
function showCopySuccess(message) {
    // Видаляємо попередні toast повідомлення
    const existingToasts = document.querySelectorAll('.room-watch__copy-toast');
    existingToasts.forEach(toast => toast.remove());

    // Створюємо нове повідомлення
    const toast = document.createElement('div');
    toast.className = 'room-watch__copy-toast';
    toast.textContent = message;
    document.body.appendChild(toast);

    // Видаляємо через 2 секунди
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 2000);
}

// Отримання даних для поділення
function getShareData() {
    const roomData = window.roomData || {};
    const inviteLink = document.getElementById('inviteLink')?.value || '';
    const password = document.getElementById('roomPassword')?.value || '';

    return {
        inviteLink,
        movieName: roomData.movieName || '',
        roomName: roomData.roomName || '',
        password
    };
}

// Поділення в Telegram
function shareToTelegram() {
    const { inviteLink, movieName, roomName, password } = getShareData();

    let text = `🎬 Запрошую тебе подивитися "${movieName}" разом зі мною!\n\n`;
    text += `🏠 Кімната: ${roomName}\n`;
    text += `🔗 Посилання: ${inviteLink}`;

    if (password && password !== 'Пароль встановлений власником кімнати') {
        text += `\n🔐 Пароль: ${password}`;
    }

    const url = `https://t.me/share/url?url=${encodeURIComponent(inviteLink)}&text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
}

// Поділення в Viber
function shareToViber() {
    const { inviteLink, movieName, roomName, password } = getShareData();

    let text = `🎬 Запрошую тебе подивитися "${movieName}" разом зі мною!\n\n`;
    text += `🏠 Кімната: ${roomName}\n`;
    text += `🔗 Посилання: ${inviteLink}`;

    if (password && password !== 'Пароль встановлений власником кімнати') {
        text += `\n🔐 Пароль: ${password}`;
    }

    const url = `viber://forward?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
}

// Поділення в WhatsApp
function shareToWhatsApp() {
    const { inviteLink, movieName, roomName, password } = getShareData();

    let text = `🎬 Запрошую тебе подивитися "${movieName}" разом зі мною!\n\n`;
    text += `🏠 Кімната: ${roomName}\n`;
    text += `🔗 Посилання: ${inviteLink}`;

    if (password && password !== 'Пароль встановлений власником кімнати') {
        text += `\n🔐 Пароль: ${password}`;
    }

    const url = `https://wa.me/?text=${encodeURIComponent(text)}`;
    window.open(url, '_blank');
}

// Копіювання всієї інформації про запрошення
function copyAllInviteInfo() {
    const { inviteLink, movieName, roomName, password } = getShareData();

    let text = `🎬 Запрошення до перегляду фільму\n\n`;
    text += `Фільм: ${movieName}\n`;
    text += `Кімната: ${roomName}\n`;
    text += `Посилання: ${inviteLink}\n`;

    if (password && password !== 'Пароль встановлений власником кімнати') {
        text += `Пароль: ${password}\n`;
    }

    text += `\nПриєднуйся до спільного перегляду! 🍿`;

    navigator.clipboard.writeText(text).then(() => {
        showCopySuccess('Вся інформація скопійована!');
    }).catch(() => {
        // Fallback для старих браузерів
        try {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.opacity = '0';
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            showCopySuccess('Вся інформація скопійована!');
        } catch (err) {
            console.error('Помилка копіювання всієї інформації:', err);
            showCopySuccess('Помилка копіювання');
        }
    });
}

// Ініціалізація обробників подій
document.addEventListener('DOMContentLoaded', function() {
    // Закриття модального вікна клавішею Escape
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modal = document.getElementById('inviteModal');
            if (modal && modal.style.display === 'flex') {
                closeInviteModal();
            }
        }
    });

    // Закриття модального вікна при кліку поза ним
    const modal = document.getElementById('inviteModal');
    if (modal) {
        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeInviteModal();
            }
        });
    }
});

// Експорт функцій для глобального використання
window.openInviteModal = openInviteModal;
window.closeInviteModal = closeInviteModal;
window.copyInviteLink = copyInviteLink;
window.generateQRCode = generateQRCode;
window.copyRoomPassword = copyRoomPassword;
window.shareToTelegram = shareToTelegram;
window.shareToViber = shareToViber;
window.shareToWhatsApp = shareToWhatsApp;
window.copyAllInviteInfo = copyAllInviteInfo;

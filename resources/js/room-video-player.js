/**
 * Room Video Player Management
 * Handles video source switching and player controls for room watching
 */

document.addEventListener('DOMContentLoaded', function() {
    initializeVideoPlayer();
});

function initializeVideoPlayer() {
    const videoPlayer = document.getElementById('videoPlayer');
    if (!videoPlayer) return;

    // Store current time when switching sources
    let currentTime = 0;
    
    // Add event listeners for video events
    videoPlayer.addEventListener('loadedmetadata', function() {
        if (currentTime > 0) {
            videoPlayer.currentTime = currentTime;
        }
    });

    videoPlayer.addEventListener('timeupdate', function() {
        currentTime = videoPlayer.currentTime;
    });

    // Initialize first source button as active
    const firstButton = document.querySelector('.room-watch__source-button');
    if (firstButton && !firstButton.classList.contains('room-watch__source-button--active')) {
        firstButton.classList.add('room-watch__source-button--active');
    }
}

/**
 * Change video source and update UI
 * @param {string} newSource - New video source URL
 * @param {HTMLElement} buttonElement - Clicked button element
 * @param {number} playerIndex - Index of the selected player
 */
function changeVideoSource(newSource, buttonElement, playerIndex) {
    const videoPlayer = document.getElementById('videoPlayer');
    if (!videoPlayer || !newSource) {
        console.error('Video player not found or invalid source');
        return;
    }

    // Store current playback time
    const currentTime = videoPlayer.currentTime;
    const wasPlaying = !videoPlayer.paused;

    // Update video source
    videoPlayer.src = newSource;
    
    // Update active button state
    updateActiveButton(buttonElement);
    
    // Show loading state
    showLoadingState(buttonElement);
    
    // Handle video load
    videoPlayer.addEventListener('loadedmetadata', function onLoad() {
        // Restore playback position
        if (currentTime > 0) {
            videoPlayer.currentTime = currentTime;
        }
        
        // Resume playback if it was playing
        if (wasPlaying) {
            videoPlayer.play().catch(error => {
                console.warn('Auto-play failed:', error);
            });
        }
        
        // Hide loading state
        hideLoadingState(buttonElement);
        
        // Remove event listener
        videoPlayer.removeEventListener('loadedmetadata', onLoad);
    }, { once: true });

    // Handle load errors
    videoPlayer.addEventListener('error', function onError() {
        console.error('Failed to load video source:', newSource);
        hideLoadingState(buttonElement);
        showErrorState(buttonElement);
        videoPlayer.removeEventListener('error', onError);
    }, { once: true });

    // Load the new source
    videoPlayer.load();
    
    // Log player change for debugging
    console.log(`Switched to player ${playerIndex + 1}:`, newSource);
}

/**
 * Update active button state
 * @param {HTMLElement} activeButton - The button that was clicked
 */
function updateActiveButton(activeButton) {
    // Remove active class from all source buttons
    const allButtons = document.querySelectorAll('.room-watch__source-button');
    allButtons.forEach(button => {
        button.classList.remove('room-watch__source-button--active');

        // Reset any error states
        button.classList.remove('room-watch__source-button--error');
    });

    // Add active class to clicked button
    activeButton.classList.add('room-watch__source-button--active');

    // Log the change for debugging
    const playerIndex = activeButton.dataset.playerIndex;
    const playerName = activeButton.dataset.playerName;
    console.log(`Active player changed to: ${playerName} (index: ${playerIndex})`);
}

/**
 * Show loading state on button
 * @param {HTMLElement} button - Button element
 */
function showLoadingState(button) {
    const originalText = button.textContent;
    button.dataset.originalText = originalText;
    button.textContent = '...';
    button.style.pointerEvents = 'none';
    button.style.opacity = '0.6';
}

/**
 * Hide loading state on button
 * @param {HTMLElement} button - Button element
 */
function hideLoadingState(button) {
    if (button.dataset.originalText) {
        button.innerHTML = button.dataset.originalText;
        delete button.dataset.originalText;
    }
    button.style.pointerEvents = 'auto';
    button.style.opacity = '1';
}

/**
 * Show error state on button
 * @param {HTMLElement} button - Button element
 */
function showErrorState(button) {
    button.classList.add('room-watch__source-button--error');
    button.style.pointerEvents = 'auto';
    button.style.opacity = '1';

    // Reset after 3 seconds
    setTimeout(() => {
        button.classList.remove('room-watch__source-button--error');
    }, 3000);
}

/**
 * Get player info by index
 * @param {number} index - Player index
 * @returns {Object|null} Player information
 */
function getPlayerInfo(index) {
    const button = document.querySelector(`[data-player-index="${index}"]`);
    if (!button) return null;
    
    return {
        index: index,
        name: button.dataset.playerName || `Player ${index + 1}`,
        element: button
    };
}

/**
 * Get currently active player
 * @returns {Object|null} Active player information
 */
function getActivePlayer() {
    const activeButton = document.querySelector('.room-watch__player-button--active');
    if (!activeButton) return null;
    
    const index = parseInt(activeButton.dataset.playerIndex) || 0;
    return getPlayerInfo(index);
}

// Export functions for global access
window.changeVideoSource = changeVideoSource;
window.getPlayerInfo = getPlayerInfo;
window.getActivePlayer = getActivePlayer;

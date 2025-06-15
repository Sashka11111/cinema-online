// Room video synchronization functionality
document.addEventListener('DOMContentLoaded', function() {
    const video = document.getElementById('videoPlayer');
    const roomSlug = video?.dataset.roomSlug;

    // Video source change function
    window.changeVideoSource = function(url, button) {
        if (!video) return;

        video.src = url;
        video.load();

        // Update active button
        document.querySelectorAll('.room-watch__player-button').forEach(btn => {
            btn.classList.remove('room-watch__player-button--active');
        });
        button.classList.add('room-watch__player-button--active');
    };

    // Video synchronization with Echo
    if (video && roomSlug && window.Echo) {
        let ignoreNextEvent = false;

        // Listen for sync events from other users
        Echo.channel(`room.${roomSlug}`)
            .listen('.VideoSyncEvent', (e) => {
                console.log('🎬 Received VideoSyncEvent:', e.action, 'time:', e.data?.currentTime);

                ignoreNextEvent = true;

                if (e.action === 'play') {
                    console.log('▶️ Playing video from sync');
                    video.currentTime = e.data?.currentTime || video.currentTime;
                    video.play().catch(err => console.log('Play failed:', err));
                } else if (e.action === 'pause') {
                    console.log('⏸️ Pausing video from sync');
                    video.currentTime = e.data?.currentTime || video.currentTime;
                    video.pause();
                } else if (e.action === 'seek') {
                    console.log('⏭️ Seeking video from sync');
                    video.currentTime = e.data?.currentTime || 0;
                }

                // Reset ignore flag after a short delay
                setTimeout(() => {
                    ignoreNextEvent = false;
                }, 200);
            });

        // Send sync events to other users
        video.addEventListener('play', () => {
            if (!ignoreNextEvent) {
                console.log('📤 Sending PLAY event, time:', video.currentTime);
                Livewire.dispatch('sync-video', {
                    action: 'play',
                    data: { currentTime: video.currentTime }
                });
            } else {
                console.log('🔇 Ignoring PLAY event (from sync)');
            }
        });

        video.addEventListener('pause', () => {
            if (!ignoreNextEvent) {
                console.log('📤 Sending PAUSE event, time:', video.currentTime);
                Livewire.dispatch('sync-video', {
                    action: 'pause',
                    data: { currentTime: video.currentTime }
                });
            } else {
                console.log('🔇 Ignoring PAUSE event (from sync)');
            }
        });

        // Sync seeking
        video.addEventListener('seeked', () => {
            if (!ignoreNextEvent) {
                console.log(' Sending SEEK event, time:', video.currentTime);
                Livewire.dispatch('sync-video', {
                    action: 'seek',
                    data: { currentTime: video.currentTime }
                });
            } else {
                console.log(' Ignoring SEEK event (from sync)');
            }

            // Reset ignore flag after a short delay
            setTimeout(() => {
                ignoreNextEvent = false;
            }, 200);
        });
    }
});

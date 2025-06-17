import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/room-echo.js',
                'resources/js/room-video-player.js',
                'resources/js/room-video-sync.js',
            ],
            refresh: true,
        }),
    ],
});

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Only initialize Echo for room pages
if (!window.Echo) {
  window.Pusher = Pusher;

  // Логування для дебагу
  console.log('Room Echo config:', {
    key: import.meta.env.VITE_REVERB_APP_KEY,
    host: import.meta.env.VITE_REVERB_HOST,
    port: import.meta.env.VITE_REVERB_PORT,
    scheme: import.meta.env.VITE_REVERB_SCHEME
  });

  window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST ?? 'localhost',
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8081,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 8081,
    forceTLS: false, // Для локальної розробки завжди false
    enabledTransports: ['ws'],
    disableStats: true,
    encrypted: false,
    cluster: '',
    authEndpoint: '/broadcasting/auth',
  });

  // Логування подій з'єднання
  window.Echo.connector.pusher.connection.bind('connected', () => {
    console.log('🟢 Room WebSocket connected successfully!');
  });

  window.Echo.connector.pusher.connection.bind('disconnected', () => {
    console.log('🔴 Room WebSocket disconnected');
  });

  window.Echo.connector.pusher.connection.bind('error', (error) => {
    console.error('❌ Room WebSocket error:', error);
  });

  // Логування стану з'єднання
  console.log('🚀 Echo initialized for room page');
  console.log('🔗 Connection state:', window.Echo.connector.pusher.connection.state);
} else {
  console.log('Echo already initialized');
}

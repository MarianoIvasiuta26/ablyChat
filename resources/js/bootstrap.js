import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'ably',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: 'realtime.ably.io',
    httpHost: 'rest.ably.io',
    wsPort: 443,
    wssPort: 443,
    httpPort: 443,
    httpsPort: 443,
    forceTLS: true,
    encrypted: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});

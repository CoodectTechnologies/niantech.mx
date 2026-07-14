import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

if (import.meta.env.VITE_REVERB_STATUS === 'true') {
    window.Pusher = Pusher;
    window.Pusher.logToConsole = import.meta.env.VITE_REVERB_ENV == 'local';
    window.Echo = new Echo({
        broadcaster: 'reverb',
        key: import.meta.env.VITE_REVERB_APP_KEY,
        wsHost: import.meta.env.VITE_REVERB_HOST,
        wsPort: import.meta.env.VITE_REVERB_PORT,
        wssPort: import.meta.env.VITE_REVERB_PORT,
        forceTLS: import.meta.env.VITE_REVERB_SCHEME === 'https',
        enabledTransports: ['ws', 'wss'],
    });
}
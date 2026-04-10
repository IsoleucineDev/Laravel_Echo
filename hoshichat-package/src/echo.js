import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

/**
 * Create and configure a Laravel Echo instance.
 *
 * @param {object} options
 * @param {string} options.key            - Pusher app key (VITE_PUSHER_APP_KEY)
 * @param {string} [options.cluster]      - Pusher cluster (default: 'mt1')
 * @param {string} [options.wsHost]       - Custom WebSocket host
 * @param {number} [options.wsPort]       - Custom WebSocket port
 * @param {string} [options.scheme]       - 'https' or 'http' (default: 'https')
 * @param {string} [options.authEndpoint] - Broadcasting auth endpoint (default: '/broadcasting/auth')
 * @param {string} [options.token]        - Bearer token for auth header
 * @returns {Echo}
 */
export function createEcho(options = {}) {
    const {
        key,
        cluster = 'mt1',
        wsHost,
        wsPort,
        scheme = 'https',
        authEndpoint = '/broadcasting/auth',
        token = null,
    } = options;

    if (!key) {
        throw new Error('[hoshichat] createEcho: "key" option is required (your Pusher app key).');
    }

    // Pusher must be on the global scope for Laravel Echo's Pusher broadcaster
    if (typeof window !== 'undefined') {
        window.Pusher = Pusher;
    }

    const authHeaders = {};
    if (token) {
        authHeaders['Authorization'] = 'Bearer ' + token;
    }

    return new Echo({
        broadcaster: 'pusher',
        key,
        cluster,
        wsHost: wsHost ?? undefined,
        wsPort: wsPort ?? undefined,
        forceTLS: scheme === 'https',
        encrypted: true,
        authEndpoint,
        auth: {
            headers: authHeaders,
        },
    });
}

/**
 * Update the Authorization token on an existing Echo instance.
 *
 * @param {Echo}   echoInstance
 * @param {string} token
 */
export function setEchoToken(echoInstance, token) {
    if (echoInstance && echoInstance.connector && echoInstance.connector.options) {
        echoInstance.connector.options.auth = {
            headers: { Authorization: 'Bearer ' + token },
        };
    }
}

/**
 * Composable for managing WebSocket subscriptions via a Laravel Echo instance.
 *
 * @param {import('laravel-echo').default} echoInstance - The Echo instance created by createEcho()
 */
export function useWebSocket(echoInstance) {
    if (!echoInstance) {
        throw new Error('[hoshichat] useWebSocket: an Echo instance is required.');
    }

    /** @type {Record<string|number, import('laravel-echo').default>} */
    const listeners = {};

    /**
     * Subscribe to a private conversation channel and register event callbacks.
     *
     * @param {string|number} conversationId
     * @param {object} callbacks
     * @param {function} [callbacks.onMessageSent]
     * @param {function} [callbacks.onMessageUpdated]
     * @param {function} [callbacks.onMessageDeleted]
     * @param {function} [callbacks.onUserJoined]
     * @param {function} [callbacks.onUserLeft]
     * @param {function} [callbacks.onConversationUpdated]
     * @param {function} [callbacks.onUserTyping]
     */
    const listenToConversation = (conversationId, callbacks = {}) => {
        if (listeners[conversationId]) {
            return;
        }

        const channel = echoInstance.private(`conversation.${conversationId}`);

        channel.listen('MessageSent', (event) => {
            if (callbacks.onMessageSent) callbacks.onMessageSent(event);
        });

        channel.listen('MessageUpdated', (event) => {
            if (callbacks.onMessageUpdated) callbacks.onMessageUpdated(event);
        });

        channel.listen('MessageDeleted', (event) => {
            if (callbacks.onMessageDeleted) callbacks.onMessageDeleted(event);
        });

        channel.listen('UserJoinedConversation', (event) => {
            if (callbacks.onUserJoined) callbacks.onUserJoined(event);
        });

        channel.listen('UserLeftConversation', (event) => {
            if (callbacks.onUserLeft) callbacks.onUserLeft(event);
        });

        channel.listen('ConversationUpdated', (event) => {
            if (callbacks.onConversationUpdated) callbacks.onConversationUpdated(event);
        });

        channel.listen('UserTyping', (event) => {
            if (callbacks.onUserTyping) callbacks.onUserTyping(event);
        });

        listeners[conversationId] = channel;
    };

    /**
     * Unsubscribe from a specific conversation channel.
     *
     * @param {string|number} conversationId
     */
    const stopListeningToConversation = (conversationId) => {
        if (listeners[conversationId]) {
            echoInstance.leaveChannel(`private-conversation.${conversationId}`);
            delete listeners[conversationId];
        }
    };

    /**
     * Unsubscribe from all conversation channels.
     */
    const stopListeningToAll = () => {
        Object.keys(listeners).forEach((id) => stopListeningToConversation(id));
    };

    /**
     * Check whether the Echo/Pusher connection is active.
     *
     * @returns {boolean}
     */
    const isConnected = () => {
        return echoInstance?.connector?.pusher?.connection?.state === 'connected';
    };

    return {
        listenToConversation,
        stopListeningToConversation,
        stopListeningToAll,
        isConnected,
    };
}

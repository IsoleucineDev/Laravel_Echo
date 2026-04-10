import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: 'test_key',
    cluster: 'mt1',
    forceTLS: false,
    encrypted: false,
});

window.conversationListeners = {};

window.subscribeToConversation = (conversationId, callbacks = {}) => {
    const channelName = `conversation.${conversationId}`;
    
    if (window.conversationListeners[conversationId]) {
        return;
    }
    
    const channel = window.Echo.channel(channelName);
    
    channel.listen('MessageSent', (event) => {
        console.log('Nuevo mensaje:', event);
        if (callbacks.onMessageSent) callbacks.onMessageSent(event);
    });
    
    channel.listen('MessageUpdated', (event) => {
        console.log('Mensaje actualizado:', event);
        if (callbacks.onMessageUpdated) callbacks.onMessageUpdated(event);
    });
    
    channel.listen('MessageDeleted', (event) => {
        console.log('Mensaje eliminado:', event);
        if (callbacks.onMessageDeleted) callbacks.onMessageDeleted(event);
    });
    
    channel.listen('UserJoinedConversation', (event) => {
        console.log('Usuario se unio:', event);
        if (callbacks.onUserJoined) callbacks.onUserJoined(event);
    });
    
    channel.listen('UserLeftConversation', (event) => {
        console.log('Usuario se fue:', event);
        if (callbacks.onUserLeft) callbacks.onUserLeft(event);
    });
    
    channel.listen('ConversationUpdated', (event) => {
        console.log('Conversacion actualizada:', event);
        if (callbacks.onConversationUpdated) callbacks.onConversationUpdated(event);
    });
    
    window.conversationListeners[conversationId] = channel;
    console.log(`Suscrito a conversacion ${conversationId}`);
};

window.unsubscribeFromConversation = (conversationId) => {
    if (window.conversationListeners[conversationId]) {
        window.Echo.leaveChannel(`conversation.${conversationId}`);
        delete window.conversationListeners[conversationId];
        console.log(`Desuscrito de conversacion ${conversationId}`);
    }
};

window.unsubscribeFromAllConversations = () => {
    Object.keys(window.conversationListeners).forEach((conversationId) => {
        window.unsubscribeFromConversation(conversationId);
    });
};

export default window.Echo;

import axios from 'axios';

/**
 * Composable for CRUD operations on Messages within a Conversation.
 *
 * @param {object} config
 * @param {string} config.baseURL - Base API URL, e.g. 'http://localhost:8000/api'
 * @param {string} config.token  - Bearer token for authenticated requests
 */
export function useMessages({ baseURL, token } = {}) {
    const authHeaders = () => ({
        Authorization: 'Bearer ' + token,
        'Content-Type': 'application/json',
    });

    const fetchMessages = async (conversationId) => {
        const response = await axios.get(
            baseURL + '/conversations/' + conversationId + '/messages',
            { headers: authHeaders() }
        );
        return response.data;
    };

    const createMessage = async (conversationId, content) => {
        const response = await axios.post(
            baseURL + '/conversations/' + conversationId + '/messages',
            { content },
            { headers: authHeaders() }
        );
        return response.data;
    };

    const updateMessage = async (messageId, content) => {
        const response = await axios.put(
            baseURL + '/messages/' + messageId,
            { content },
            { headers: authHeaders() }
        );
        return response.data;
    };

    const deleteMessage = async (messageId) => {
        await axios.delete(baseURL + '/messages/' + messageId, {
            headers: authHeaders(),
        });
    };

    return {
        fetchMessages,
        createMessage,
        updateMessage,
        deleteMessage,
    };
}

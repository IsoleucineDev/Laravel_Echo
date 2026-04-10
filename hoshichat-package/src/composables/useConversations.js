import axios from 'axios';

/**
 * Composable for CRUD operations on Conversations.
 *
 * @param {object} config
 * @param {string} config.baseURL - Base API URL, e.g. 'http://localhost:8000/api'
 * @param {string} config.token  - Bearer token for authenticated requests
 */
export function useConversations({ baseURL, token } = {}) {
    const authHeaders = () => ({
        Authorization: 'Bearer ' + token,
        'Content-Type': 'application/json',
    });

    const fetchConversations = async () => {
        const response = await axios.get(baseURL + '/conversations', {
            headers: authHeaders(),
        });
        return response.data;
    };

    const getConversation = async (id) => {
        const response = await axios.get(baseURL + '/conversations/' + id, {
            headers: authHeaders(),
        });
        return response.data;
    };

    const createConversation = async (data) => {
        const response = await axios.post(baseURL + '/conversations', data, {
            headers: authHeaders(),
        });
        return response.data;
    };

    const updateConversation = async (id, data) => {
        const response = await axios.put(baseURL + '/conversations/' + id, data, {
            headers: authHeaders(),
        });
        return response.data;
    };

    const deleteConversation = async (id) => {
        await axios.delete(baseURL + '/conversations/' + id, {
            headers: authHeaders(),
        });
    };

    return {
        fetchConversations,
        getConversation,
        createConversation,
        updateConversation,
        deleteConversation,
    };
}

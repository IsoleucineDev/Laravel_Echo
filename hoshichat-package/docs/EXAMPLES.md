# Examples — @hoshichat/laravel-echo-ui

## Basic App.vue (recommended)

```vue
<template>
  <div id="app" style="width:100%;height:100vh;">
    <router-view />
  </div>
</template>

<script>
export default { name: 'App' };
</script>
```

---

## LoginPage wrapper

```vue
<!-- src/pages/LoginPage.vue -->
<template>
  <HoshiLogin
    app-name="MiChat"
    :api-base-url="apiBaseUrl"
    :demo-users="[1, 2, 3]"
    @login="onLogin"
  />
</template>

<script>
import { LoginPage as HoshiLogin } from '@hoshichat/laravel-echo-ui';
import { createEcho } from '@hoshichat/laravel-echo-ui';

export default {
  components: { HoshiLogin },
  data: () => ({ apiBaseUrl: 'http://localhost:8000/api' }),
  methods: {
    onLogin({ token, userId }) {
      localStorage.setItem('auth_token', token);
      localStorage.setItem('user_id', userId);
      // Initialise Echo with the fresh token
      window._echo = createEcho({
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        token,
      });
      this.$router.push({ name: 'Chat' });
    },
  },
};
</script>
```

---

## ChatPage wrapper

```vue
<!-- src/pages/ChatPage.vue -->
<template>
  <HoshiChat
    app-name="MiChat"
    :api-base-url="apiBaseUrl"
    :token="token"
    :user-id="userId"
    :echo-instance="echo"
    @logout="onLogout"
  />
</template>

<script>
import { ChatPage as HoshiChat, createEcho } from '@hoshichat/laravel-echo-ui';

export default {
  components: { HoshiChat },
  data() {
    const token  = localStorage.getItem('auth_token');
    const userId = parseInt(localStorage.getItem('user_id'));
    const echo   = createEcho({
      key:     import.meta.env.VITE_PUSHER_APP_KEY,
      cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
      token,
    });
    return { apiBaseUrl: 'http://localhost:8000/api', token, userId, echo };
  },
  methods: {
    onLogout() {
      localStorage.removeItem('auth_token');
      localStorage.removeItem('user_id');
      this.$router.push({ name: 'Login' });
    },
  },
};
</script>
```

---

## Using composables without components

```js
import {
  createEcho,
  useConversations,
  useMessages,
  useWebSocket,
} from '@hoshichat/laravel-echo-ui';

const token = localStorage.getItem('auth_token');
const BASE   = 'http://localhost:8000/api';

// 1. Echo
const echo = createEcho({
  key:     import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: 'mt1',
  token,
});

// 2. Conversations
const { fetchConversations, createConversation } = useConversations({ baseURL: BASE, token });
const { data: conversations } = await fetchConversations();

// 3. Messages
const { fetchMessages, createMessage } = useMessages({ baseURL: BASE, token });
const { data: messages } = await fetchMessages(conversations[0].id);

// 4. WebSocket
const ws = useWebSocket(echo);
ws.listenToConversation(conversations[0].id, {
  onMessageSent: (msg) => console.log('New message:', msg),
});
```

---

## Using with Pinia (recommended for larger apps)

```js
// stores/chat.js
import { defineStore } from 'pinia';
import { createEcho, useConversations, useMessages, useWebSocket } from '@hoshichat/laravel-echo-ui';

export const useChatStore = defineStore('chat', {
  state: () => ({
    token: null,
    userId: null,
    echo: null,
    conversations: [],
    messages: [],
  }),
  actions: {
    init(token, userId) {
      this.token  = token;
      this.userId = userId;
      this.echo   = createEcho({
        key:     import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: 'mt1',
        token,
      });
    },
    async loadConversations() {
      const { fetchConversations } = useConversations({ baseURL: '/api', token: this.token });
      const res = await fetchConversations();
      this.conversations = res.data;
    },
  },
});
```

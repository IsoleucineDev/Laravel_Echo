# @hoshichat/laravel-echo-ui

[![npm version](https://img.shields.io/npm/v/@hoshichat/laravel-echo-ui.svg)](https://www.npmjs.com/package/@hoshichat/laravel-echo-ui)
[![license](https://img.shields.io/npm/l/@hoshichat/laravel-echo-ui.svg)](LICENSE)

Reusable **Vue 3** chat UI components and composables for **Laravel Echo** / WebSocket (Pusher & Soketi) applications.

---

## Features

- 💬 Ready-to-use `ChatPage` and `LoginPage` components
- 🔌 `createEcho()` factory — no globals, fully configurable
- 🧩 Independent composables (`useConversations`, `useMessages`, `useWebSocket`)
- 📦 Works in any Vue 3 + Laravel project — drop in and go
- 🎨 Scoped styles — won't leak into your app

---

## Installation

```bash
npm install @hoshichat/laravel-echo-ui
```

> **Peer dependency:** `vue ^3.0.0` must already be installed in your project.

---

## Quick Start

### 1. Login page

```vue
<template>
  <HoshiLogin
    app-name="MiApp"
    :api-base-url="apiBaseUrl"
    :demo-users="[1, 2, 3]"
    @login="onLogin"
  />
</template>

<script>
import { LoginPage as HoshiLogin } from '@hoshichat/laravel-echo-ui';

export default {
  components: { HoshiLogin },
  data: () => ({ apiBaseUrl: 'http://localhost:8000/api' }),
  methods: {
    onLogin({ token, userId }) {
      localStorage.setItem('auth_token', token);
      localStorage.setItem('user_id', userId);
      this.$router.push({ name: 'Chat' });
    },
  },
};
</script>
```

### 2. Chat page

```vue
<template>
  <HoshiChat
    app-name="MiApp"
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
      cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
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

## API Reference

### `createEcho(options)`

Creates and returns a configured Laravel Echo instance.

| Option          | Type     | Default              | Description                               |
|-----------------|----------|----------------------|-------------------------------------------|
| `key`           | `string` | **required**         | Pusher app key                            |
| `cluster`       | `string` | `'mt1'`              | Pusher cluster                            |
| `wsHost`        | `string` | `undefined`          | Custom WebSocket host (Soketi)            |
| `wsPort`        | `number` | `undefined`          | Custom WebSocket port (Soketi)            |
| `scheme`        | `string` | `'https'`            | `'https'` or `'http'`                     |
| `authEndpoint`  | `string` | `'/broadcasting/auth'` | Broadcasting auth endpoint             |
| `token`         | `string` | `null`               | Bearer token injected into auth headers   |

---

### `<LoginPage>` props & events

| Prop           | Type       | Default      | Description                          |
|----------------|------------|--------------|--------------------------------------|
| `app-name`     | `string`   | `'HoshiChat'`| Title shown on the login card        |
| `api-base-url` | `string`   | **required** | Base URL of the Laravel API          |
| `demo-users`   | `number[]` | `[]`         | Quick-select demo user IDs           |
| `default-user-id` | `number` | `null`      | Pre-filled user ID                   |

| Event   | Payload                    | Description               |
|---------|----------------------------|---------------------------|
| `login` | `{ token, userId }`        | Emitted on successful login |

---

### `<ChatPage>` props & events

| Prop            | Type     | Default      | Description                                    |
|-----------------|----------|--------------|------------------------------------------------|
| `app-name`      | `string` | `'HoshiChat'`| Title shown in the sidebar                     |
| `api-base-url`  | `string` | **required** | Base URL of the Laravel API                    |
| `token`         | `string` | **required** | Bearer token                                   |
| `user-id`       | `number` | **required** | Current user's ID                              |
| `echo-instance` | `object` | **required** | Echo instance created with `createEcho()`      |

| Event    | Payload | Description                    |
|----------|---------|--------------------------------|
| `logout` | —       | Emitted when the user logs out |

---

### `useConversations({ baseURL, token })`

| Function                         | Returns        |
|----------------------------------|----------------|
| `fetchConversations()`           | API response   |
| `getConversation(id)`            | API response   |
| `createConversation(data)`       | API response   |
| `updateConversation(id, data)`   | API response   |
| `deleteConversation(id)`         | `void`         |

---

### `useMessages({ baseURL, token })`

| Function                                 | Returns      |
|------------------------------------------|--------------|
| `fetchMessages(conversationId)`          | API response |
| `createMessage(conversationId, content)` | API response |
| `updateMessage(messageId, content)`      | API response |
| `deleteMessage(messageId)`               | `void`       |

---

### `useWebSocket(echoInstance)`

| Function                                    | Description                                  |
|---------------------------------------------|----------------------------------------------|
| `listenToConversation(id, callbacks)`        | Subscribe to a private conversation channel  |
| `stopListeningToConversation(id)`            | Unsubscribe from one channel                 |
| `stopListeningToAll()`                       | Unsubscribe from all channels                |
| `isConnected()`                              | `true` when Pusher connection is active      |

**Callbacks** (all optional):
`onMessageSent`, `onMessageUpdated`, `onMessageDeleted`, `onUserJoined`, `onUserLeft`, `onConversationUpdated`, `onUserTyping`

---

## Documentation

- [Installation Guide](docs/INSTALLATION.md)
- [Architecture](docs/ARCHITECTURE.md)
- [Examples](docs/EXAMPLES.md)

---

## License

[MIT](LICENSE)

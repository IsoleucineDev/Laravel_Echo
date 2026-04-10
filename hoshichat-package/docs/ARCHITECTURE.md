# Architecture — @hoshichat/laravel-echo-ui

## Overview

```
@hoshichat/laravel-echo-ui
│
├── src/echo.js              ← Echo factory (createEcho / setEchoToken)
│
├── src/composables/
│   ├── useConversations.js  ← Axios CRUD for /conversations
│   ├── useMessages.js       ← Axios CRUD for /messages
│   └── useWebSocket.js      ← Echo subscription manager
│
└── src/components/
    ├── ChatContainer.vue    ← Generic full-viewport wrapper
    ├── LoginPage.vue        ← Login form (emits 'login' event)
    └── ChatPage.vue         ← Full chat UI (requires token + Echo instance)
```

---

## Design Principles

### 1. No globals
The original application used `window.Echo`, `window.axios`, and `window.subscribeToConversation`.  
This package removes all globals:
- `createEcho(options)` returns a plain Echo instance.
- Composables receive `{ baseURL, token }` at call-time; no singletons.
- WebSocket state is managed inside `useWebSocket(echoInstance)`.

### 2. Prop-driven configuration
Components expose props instead of reading from `localStorage` directly, making them testable and reusable without side-effects:
- `ChatPage` accepts `token`, `userId`, `apiBaseUrl`, and `echoInstance` as props.
- `LoginPage` emits `{ token, userId }` on success — the parent decides what to do with it (localStorage, Pinia store, etc.).

### 3. Composable design
Each composable is a factory function that receives its dependencies as arguments.  
This means you can use them independently of the components:

```js
const { fetchMessages } = useMessages({ baseURL: 'http://api.test', token: myToken });
const data = await fetchMessages(conversationId);
```

### 4. Event-driven WebSocket
`useWebSocket(echoInstance)` returns four functions:
| Function | Description |
|---|---|
| `listenToConversation(id, callbacks)` | Subscribe and register event handlers |
| `stopListeningToConversation(id)` | Unsubscribe from one channel |
| `stopListeningToAll()` | Unsubscribe from all channels |
| `isConnected()` | Returns `true` when Pusher is connected |

---

## Data Flow

```
User action
    │
    ▼
Vue component (ChatPage / LoginPage)
    │  calls
    ▼
Composable (useConversations / useMessages)
    │  HTTP via Axios
    ▼
Laravel REST API  ──────────────────► Broadcasting event
                                          │
                                          ▼
                                      Pusher / Soketi
                                          │
                                          ▼
                                  useWebSocket (Echo listener)
                                          │  callback
                                          ▼
                                  Vue reactive state update
```

---

## Laravel Events Expected

| Event name              | Channel (private)          | Payload fields                         |
|-------------------------|----------------------------|----------------------------------------|
| `MessageSent`           | `conversation.{id}`        | `id`, `content`, `user_id`, `user`, `created_at` |
| `MessageUpdated`        | `conversation.{id}`        | `id`, `content`                        |
| `MessageDeleted`        | `conversation.{id}`        | `id`                                   |
| `UserJoinedConversation`| `conversation.{id}`        | `user`                                 |
| `UserLeftConversation`  | `conversation.{id}`        | `user`                                 |
| `ConversationUpdated`   | `conversation.{id}`        | conversation object                    |
| `UserTyping`            | `conversation.{id}`        | `user_id`                              |

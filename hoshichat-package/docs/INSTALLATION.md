# Installation Guide — @hoshichat/laravel-echo-ui

## Requirements

| Dependency   | Version  |
|--------------|----------|
| Node.js      | ≥ 16     |
| Vue          | ^3.0.0   |
| Laravel      | ^10 / ^11 |
| Laravel Echo | ≥ 1.13   |
| Pusher / Soketi | any   |

---

## 1. Install the package

```bash
npm install @hoshichat/laravel-echo-ui
```

---

## 2. Configure Laravel Broadcasting

In your Laravel project enable the `BroadcastServiceProvider` in `config/app.php` (Laravel 10) or ensure broadcasting is bootstrapped (Laravel 11).

```env
# .env
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1

# For Soketi (self-hosted)
# PUSHER_HOST=127.0.0.1
# PUSHER_PORT=6001
# PUSHER_SCHEME=http
```

---

## 3. Install a broadcasting backend

### Option A – Pusher (hosted)
Nothing extra needed; just use your Pusher credentials above.

### Option B – Soketi (self-hosted, free)
```bash
npm install -g @soketi/soketi
soketi start
```

---

## 4. Wire up the Vue application

```js
// main.js
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import App from './App.vue';
import LoginPage from './pages/LoginPage.vue'; // your wrapper
import ChatPage  from './pages/ChatPage.vue';  // your wrapper

const router = createRouter({
  history: createWebHistory(),
  routes: [
    { path: '/',     name: 'Login', component: LoginPage },
    { path: '/chat', name: 'Chat',  component: ChatPage, meta: { requiresAuth: true } },
  ],
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('auth_token');
  if (to.meta.requiresAuth && !token) return next({ name: 'Login' });
  if (to.name === 'Login' && token)   return next({ name: 'Chat' });
  next();
});

createApp(App).use(router).mount('#app');
```

---

## 5. See also

- [ARCHITECTURE.md](./ARCHITECTURE.md) — design decisions and data flow
- [EXAMPLES.md](./EXAMPLES.md) — ready-to-use code snippets

<template>
  <div id="app" style="width: 100%; height: 100vh;">
    <router-view />
  </div>
</template>

<script>
/**
 * Example root component for a project consuming @hoshichat/laravel-echo-ui.
 *
 * Expected router setup (main.js):
 *
 *   import { createApp }    from 'vue';
 *   import { createRouter, createWebHistory } from 'vue-router';
 *   import App              from './App.vue';
 *   import MyLoginPage      from './pages/MyLoginPage.vue';
 *   import MyChatPage       from './pages/MyChatPage.vue';
 *
 *   const router = createRouter({
 *     history: createWebHistory(),
 *     routes: [
 *       { path: '/',     name: 'Login', component: MyLoginPage },
 *       { path: '/chat', name: 'Chat',  component: MyChatPage, meta: { requiresAuth: true } },
 *     ],
 *   });
 *
 *   router.beforeEach((to, from, next) => {
 *     const token = localStorage.getItem('auth_token');
 *     if (to.meta.requiresAuth && !token) return next({ name: 'Login' });
 *     if (to.name === 'Login' && token)   return next({ name: 'Chat' });
 *     next();
 *   });
 *
 *   createApp(App).use(router).mount('#app');
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * MyLoginPage.vue
 * ─────────────────────────────────────────────────────────────────────────────
 *   <template>
 *     <HoshiLogin
 *       app-name="MiChat"
 *       :api-base-url="apiBaseUrl"
 *       :demo-users="[1, 2, 3]"
 *       @login="onLogin"
 *     />
 *   </template>
 *
 *   <script>
 *   import { LoginPage as HoshiLogin, createEcho } from '@hoshichat/laravel-echo-ui';
 *   export default {
 *     components: { HoshiLogin },
 *     data: () => ({ apiBaseUrl: 'http://localhost:8000/api' }),
 *     methods: {
 *       onLogin({ token, userId }) {
 *         localStorage.setItem('auth_token', token);
 *         localStorage.setItem('user_id', userId);
 *         this.$router.push({ name: 'Chat' });
 *       },
 *     },
 *   };
 *   <\/script>
 *
 * ─────────────────────────────────────────────────────────────────────────────
 * MyChatPage.vue
 * ─────────────────────────────────────────────────────────────────────────────
 *   <template>
 *     <HoshiChat
 *       app-name="MiChat"
 *       :api-base-url="apiBaseUrl"
 *       :token="token"
 *       :user-id="userId"
 *       :echo-instance="echo"
 *       @logout="onLogout"
 *     />
 *   </template>
 *
 *   <script>
 *   import { ChatPage as HoshiChat, createEcho } from '@hoshichat/laravel-echo-ui';
 *   export default {
 *     components: { HoshiChat },
 *     data() {
 *       const token  = localStorage.getItem('auth_token');
 *       const userId = parseInt(localStorage.getItem('user_id'));
 *       const echo   = createEcho({
 *         key:     import.meta.env.VITE_PUSHER_APP_KEY,
 *         cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
 *         token,
 *       });
 *       return { apiBaseUrl: 'http://localhost:8000/api', token, userId, echo };
 *     },
 *     methods: {
 *       onLogout() {
 *         localStorage.removeItem('auth_token');
 *         localStorage.removeItem('user_id');
 *         this.$router.push({ name: 'Login' });
 *       },
 *     },
 *   };
 *   <\/script>
 */
export default {
  name: 'App',
};
</script>

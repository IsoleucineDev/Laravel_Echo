<template>
  <div class="hoshi-login-container">
    <div class="login-box">
      <h1>{{ appName }}</h1>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label>Usuario ID:</label>
          <input
            v-model.number="userId"
            type="number"
            placeholder="Ingresa tu ID de usuario"
            required
          />
        </div>

        <button type="submit" class="btn-login" :disabled="loading">
          {{ loading ? 'Conectando...' : 'Ingresar' }}
        </button>

        <div v-if="error" class="error-message">{{ error }}</div>
      </form>

      <div v-if="demoUsers.length" class="demo-users">
        <p>Usuarios de demo:</p>
        <button
          v-for="user in demoUsers"
          :key="user"
          @click="userId = user"
          class="demo-btn"
        >
          Usuario {{ user }}
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  name: 'LoginPage',

  props: {
    /** Displayed app title */
    appName: {
      type: String,
      default: 'HoshiChat',
    },
    /** Base API URL, e.g. 'http://localhost:8000/api' */
    apiBaseUrl: {
      type: String,
      required: true,
    },
    /** List of demo user IDs shown as quick-select buttons */
    demoUsers: {
      type: Array,
      default: () => [],
    },
    /** Default user ID pre-filled in the input */
    defaultUserId: {
      type: Number,
      default: null,
    },
  },

  /**
   * Emitted when login succeeds.
   * Payload: { token: string, userId: number }
   */
  emits: ['login'],

  data() {
    return {
      userId: this.defaultUserId,
      loading: false,
      error: null,
    };
  },

  methods: {
    async handleLogin() {
      this.loading = true;
      this.error = null;

      try {
        const response = await axios.post(this.apiBaseUrl + '/login', {
          user_id: this.userId,
        });

        const token = response.data.token;
        this.$emit('login', { token, userId: this.userId });
      } catch (err) {
        this.error = 'Error al conectar. Verifica el ID de usuario.';
        console.error('[hoshichat] login:', err);
      } finally {
        this.loading = false;
      }
    },
  },
};
</script>

<style scoped>
.hoshi-login-container {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 100%;
  height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen,
    Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}
.login-box { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); width: 100%; max-width: 400px; }
.login-box h1 { text-align: center; margin-bottom: 30px; color: #333; font-size: 32px; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; margin-bottom: 8px; color: #555; font-weight: 500; }
.form-group input { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-size: 16px; box-sizing: border-box; }
.form-group input:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.1); }
.btn-login { width: 100%; padding: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 5px; font-size: 16px; font-weight: 600; cursor: pointer; transition: transform 0.2s; }
.btn-login:hover:not(:disabled) { transform: translateY(-2px); }
.btn-login:active:not(:disabled) { transform: translateY(0); }
.btn-login:disabled { opacity: 0.7; cursor: not-allowed; }
.error-message { margin-top: 15px; padding: 12px; background: #fee; color: #c33; border-radius: 5px; text-align: center; }
.demo-users { margin-top: 30px; padding-top: 30px; border-top: 1px solid #eee; }
.demo-users p { color: #666; margin-bottom: 10px; font-size: 14px; }
.demo-btn { display: inline-block; margin: 5px; padding: 8px 16px; background: #f0f0f0; border: 1px solid #ddd; border-radius: 5px; cursor: pointer; font-size: 14px; transition: all 0.2s; }
.demo-btn:hover { background: #667eea; color: white; border-color: #667eea; }
</style>

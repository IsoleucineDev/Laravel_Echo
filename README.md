<img src="https://capsule-render.vercel.app/api?type=waving&color=0:0f2027,50:203a43,100:2c5364&height=120&section=header&text=&animation=fadeIn" width="100%"/>

<div align="center">

# 💬 HoshiChat

### Real-time Chat con Laravel Echo + Vue 3 + WebSockets

![version](https://img.shields.io/badge/version-1.0.0-blue)
![license](https://img.shields.io/badge/license-MIT-green)
![laravel](https://img.shields.io/badge/Laravel-13-red)
![vue](https://img.shields.io/badge/Vue-3-42b883)
![vite](https://img.shields.io/badge/Vite-8-646cff)
![status](https://img.shields.io/badge/status-active-success)

</div>

---

# ✨ Características

⚡ Chat en tiempo real
🔌 WebSockets con Laravel Echo
📡 Broadcasting automático
⚙️ Vue 3 + Vite
📱 Responsive UI
📦 NPM Package reutilizable
🧩 Arquitectura limpia
🚀 Alta performance (<100ms)

---

# 🖼️ Demo Visual

```
┌──────────────────────┐        ┌──────────────────────┐
│      Usuario A       │        │      Usuario B       │
├──────────────────────┤        ├──────────────────────┤
│ Hola!                │  --->  │ Hola!                │
│ ¿Cómo estás?         │        │ ¿Cómo estás?         │
│ escribiendo...       │        │ escribiendo...       │
└──────────────────────┘        └──────────────────────┘

           Tiempo real (<100ms)
```

---

# 🏗️ Arquitectura

```
Frontend (Vue 3 + Vite)
        │
        │ HTTP + WebSocket
        ▼
Laravel Echo (Broadcasting)
        │
        │ Eventos en tiempo real
        ▼
Backend Laravel
        │
        ▼
SQLite / MySQL
```

---

# 🚀 Quick Start

## Requisitos

* PHP 8.3+
* Node 18+
* Composer
* Git

---

# 🪟 Windows

```bash
git clone https://github.com/IsoleucineDev/Laravel_Echo.git
cd Laravel_Echo/HoshiChat

composer install
npm install

copy .env.example .env
php artisan key:generate

New-Item database/database.sqlite -ItemType File

php artisan migrate
php artisan db:seed --class=ConversationSeeder

php artisan serve
npm run dev
```

Abrir:

```
http://localhost:8000/chat
```

---

# 🔐 Login de prueba

```
Email: user1@example.com
Password: password
```

Abrir 2 navegadores para probar tiempo real.

---

# ⚡ Cómo funciona

```
Usuario escribe mensaje
        ↓
Vue envía POST /api/messages
        ↓
Laravel guarda en BD
        ↓
broadcast(MessageSent)
        ↓
Laravel Echo emite evento
        ↓
WebSocket distribuye
        ↓
Usuarios reciben mensaje
        ↓
UI se actualiza instantáneamente
```

---

# 🔌 Laravel Echo

Backend:

```php
broadcast(new MessageSent($message));
```

Frontend:

```js
window.Echo.channel('conversation.1')
.listen('MessageSent', (event) => {
    this.messages.push(event);
});
```

---

# 📂 Estructura

```
HoshiChat
│
├── app
│   ├── Models
│   ├── Events
│   └── Controllers
│
├── resources/js
│   ├── pages
│   ├── composables
│   └── echo.js
│
├── routes
├── database
└── public
```

---

# 📦 NPM Package

```
npm install @hoshichat/laravel-echo-ui
```

Incluye:

* ChatPage
* LoginPage
* useMessages
* useConversations
* useWebSocket
* Echo instance

---

# 🧪 Comandos útiles

```bash
php artisan route:list
php artisan cache:clear
php artisan config:clear
php artisan optimize:clear
php artisan migrate:fresh --seed
```

---

# 🛠️ Troubleshooting

Error 500:

```bash
php artisan optimize:clear
```

Reset DB:

```bash
rm database/database.sqlite
touch database/database.sqlite

php artisan migrate
php artisan db:seed --class=ConversationSeeder
```

---

# ⚡ Performance

| Feature   | HoshiChat |
| --------- | --------- |
| Realtime  | ✅         |
| WebSocket | ✅         |
| Polling   | ❌         |
| Latency   | <100ms    |
| Scalable  | ✅         |

---

# 🤝 Contribuir

```bash
git checkout -b feature/new-feature
git commit -m "feature"
git push
```

Pull Request.

---

# 👨‍💻 Autores

Marco
Yael
Ileana

---

# ⭐ Soporte

Si te gustó el proyecto:

⭐ Dale Star en GitHub
🐛 Reporta bugs
🚀 Contribuye

---

<div align="center">

### HoshiChat — Real-time Laravel Echo Chat

</div>

<img src="https://capsule-render.vercel.app/api?type=waving&color=0:0f2027,50:203a43,100:2c5364&height=120&section=footer"/>

<img src="https://capsule-render.vercel.app/api?type=waving&color=0:0f2027,50:203a43,100:2c5364&height=120&section=header&text=&animation=fadeIn" width="100%"/>

<div align="center">

# HoshiChat

### Real-time Chat con Laravel Echo + Vue 3 + WebSockets

![version](https://img.shields.io/badge/version-1.0.0-blue)
![license](https://img.shields.io/badge/license-MIT-green)
![laravel](https://img.shields.io/badge/Laravel-13-red)
![vue](https://img.shields.io/badge/Vue-3-42b883)
![vite](https://img.shields.io/badge/Vite-8-646cff)
![status](https://img.shields.io/badge/status-active-success)

</div>

---

# (ﾉ◕ヮ◕)ﾉ*:･ﾟ✧ Links Importantes

Video Demo
https://www.youtube.com/watch?v=JxSNsFV6N2E&feature=youtu.be

Guías de Instalación
https://isoleucinedev.github.io/HoshiChat/

---

# (≧◡≦) Características

(づ｡◕‿‿◕｡)づ Chat en tiempo real
(ノ°〇°)ノ WebSockets con Laravel Echo
(☞ﾟヮﾟ)☞ Broadcasting automático
(￣▽￣) Vue 3 + Vite
(⌐■_■) Responsive UI
(っ◔◡◔)っ NPM Package reutilizable
(ﾉ´ヮ`)ﾉ*: ･ﾟ Arquitectura limpia
(⚡‿⚡) Alta performance (<100ms)

---

# (づ￣ ³￣)づ Demo Visual

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

# (☞ﾟヮﾟ)☞ Arquitectura

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

# (ノ^_^)ノ Inicio Rápido

## Requisitos

* PHP 8.3+
* Node 18+
* Composer
* Git

---

# (づ｡◕‿‿◕｡)づ Windows

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

# (¬‿¬ ) Login de prueba

```
Email: user1@example.com
Password: password
```

Abrir 2 navegadores para probar tiempo real.

---

# (づ｡◕‿‿◕｡)づ Cómo funciona

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

# (ﾉ◕ヮ◕)ﾉ*:･ﾟ✧ Laravel Echo

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

# (づ￣ ³￣)づ Estructura

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

# (っ◔◡◔)っ NPM Package

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

# (ノಠ益ಠ)ノ Comandos útiles

```bash
php artisan route:list
php artisan cache:clear
php artisan config:clear
php artisan optimize:clear
php artisan migrate:fresh --seed
```

---

# (⚡‿⚡) Performance

| Feature   | HoshiChat |
| --------- | --------- |
| Realtime  | Sí        |
| WebSocket | Sí        |
| Polling   | No        |
| Latency   | <100ms    |
| Scalable  | Sí        |

---

# (づ｡◕‿‿◕｡)づ Contribuir

```bash
git checkout -b feature/new-feature
git commit -m "feature"
git push
```

Pull Request.

---

# (ﾉ´ヮ`)ﾉ*: ･ﾟ Autores

Marco
Yael
Ileana

---

<div align="center">

HoshiChat — Real-time Laravel Echo Chat

</div>

<img src="https://capsule-render.vercel.app/api?type=waving&color=0:0f2027,50:203a43,100:2c5364&height=120&section=footer"/>


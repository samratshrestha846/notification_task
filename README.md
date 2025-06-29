
# 📬 Notification System – Laravel + Node.js Microservice

This is a mock **Notification System** built using **Laravel 12 (PHP 8.2)** as the primary API backend and a **Node.js Fastify microservice** (TypeScript) for queue-based notification processing. The architecture leverages **Redis + BullMQ** for message queuing, allowing scalable, decoupled, and extensible notification handling.

---

## 🧱 Tech Stack

| Layer         | Tech                              |
|--------------|------------------------------------|
| Backend API  | Laravel 12                         |
| Microservice | Node.js + Fastify + TypeScript     |
| Queue        | Redis + BullMQ                     |
| Logging      | Pino (Fastify), Laravel Log        |
| Validation   | Laravel FormRequest + TypeBox/Joi  |

---

## 📦 Folder Structure

```
notification_task/
├── notification_task_laravel/      # Laravel 12 API
└── notification_task_node/         # Node.js Fastify microservice
```

---

## 🚀 Features

- Publish notifications via Laravel API
- Redis queue integration with BullMQ
- Node.js service consumes messages and simulates notification delivery
- Retry logic using exponential backoff
- Laravel stores status updates (`pending` → `sent`)
- Summary and recent notifications API
- Rate limiting: Max 10 notifications per user/hour

---

## ⚙️ Setup Instructions

### 🐘 Laravel (notification_task_laravel)

#### 1. Install dependencies

```bash
cd notification_task_laravel
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

#### 2. Run Laravel server and queue

```bash
php artisan serve
php artisan queue:work
```

> Make sure Redis is installed and running on `localhost:6379`.

---

### 🟦 Node.js (notification_task_node)

#### 1. Install dependencies

```bash
cd notification_task_node
npm install
```

#### 2. Add `.env` file

```env
PORT=3000
LARAVEL_API_URL=http://127.0.0.1:8000/api
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

#### 3. Start the Node.js microservice

```bash
npm run dev
```

This starts the Fastify server and background worker to consume notifications.

---

## 📨 API Endpoints

### Laravel (http://127.0.0.1:8000/api)

| Method | Endpoint                     | Description                      |
|--------|------------------------------|----------------------------------|
| POST   | `/notifications`             | Publish a notification           |
| GET    | `/notifications/recent`      | Get recent notifications         |
| GET    | `/notifications/summary`     | Get summary (sent, pending etc.) |

### Node.js (http://127.0.0.1:3000)

| Method | Endpoint                     | Description                            |
|--------|------------------------------|----------------------------------------|
| POST   | `/notifications`             | Queue a notification                   |
| GET    | `/notifications/recent`      | Proxy Laravel's recent endpoint        |
| GET    | `/notifications/summary`     | Proxy Laravel's summary endpoint       |

> ✅ Successful queueing logs appear in both Laravel and Node.js console output.

---

## 🔁 How the Queue Works

1. **POST** to `/api/notifications` (Laravel) stores and queues the job.
2. Redis + BullMQ delivers the job to the Node.js worker.
3. Worker simulates sending (e.g., `console.log`).
4. Worker **PATCH**es Laravel to update notification status → `sent`.

---

## 🧪 Testing with curl

```bash
curl -X POST http://127.0.0.1:8000/api/notifications -H "Content-Type: application/json" -d '{"user_id": 1, "message": "Test from Laravel"}'
```

```bash
curl -X POST http://127.0.0.1:3000/notifications -H "Content-Type: application/json" -d '{"user_id": 1, "message": "Test from Node"}'
```

---

## 🧠 Future Improvements

- Add email/push/SMS notification channels
- Add Redis caching layer for frequent reads
- Admin dashboard for logs & failed jobs
- Rate-limiting middleware per user in Laravel
- Use Docker + docker-compose

---

## 📜 License

This project is open-sourced under the MIT License.

---

## 🤝 Contributing

PRs are welcome! Please fork the repo and open a pull request with clear commit messages.

---

## 🙋‍♂️ Maintainers

- [Samrat Shrestha](https://github.com/samratshrestha846)

---

## 💬 Need Help?

Open an [Issue](https://github.com/YOUR_REPO/issues) or drop a message on the discussion board.
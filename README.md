# 🚗 Parking API (Laravel + Docker + Nginx + MySQL)

This is a Laravel-based API for managing parking operations, containerized using Docker. It includes support for database migrations, seeders, and background job processing with Laravel Queues.

---

## 📦 Prerequisites

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- `.env` file configured properly (you can copy from `.env.example`)

---

## 🏁 Getting Started

### 1. Clone the Repository

```bash
git clone https://github.com/cody-gawr/parking-api.git
cd parking-api
```

### 2. Create and Configure `.env`

Copy the example environment file:

```bash
cp .env.example .env
```

Update the following variables if needed:

```env
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=parking
DB_USERNAME=parking_user
DB_PASSWORD=secret

QUEUE_CONNECTION=database
```

> ✅ Make sure to match `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` with values in `docker-compose.yml`.

---

## 🚀 Run the Containers

```bash
docker compose up -d --build
```

This will start 3 services:

- `app`: Laravel backend running PHP-FPM
- `mysql`: MySQL 8.0 database
- `nginx`: Web server accessible at [http://localhost](http://localhost)

---

## ⚙️ Setup Laravel (First Time Only)

### Run Composer (if needed)

```bash
docker compose exec app composer install
```

### Generate App Key

```bash
docker compose exec app php artisan key:generate
```

### Run Migrations

```bash
docker compose exec app php artisan migrate
```

### Run Seeders (optional)

```bash
docker compose exec app php artisan db:seed
```

---

## 🧵 Run the Queue Worker

To process queued jobs, run the queue worker in a separate terminal:

```bash
docker compose exec app php artisan queue:work
```

> 📌 Tip: You can also run `queue:work` in a Supervisor process for production environments.

---

## 🧪 Useful Commands

### Run Tests

```bash
docker compose exec app php artisan test
```

### Access Laravel Tinker

```bash
docker compose exec app php artisan tinker
```

### View Laravel Logs

```bash
docker compose exec app tail -f storage/logs/laravel.log
```

---

## 🧼 Stop and Clean Up

To stop all containers:

```bash
docker compose down
```

To stop and remove containers, volumes, and networks:

```bash
docker compose down -v
```

---

## 📁 Folder Structure

```
parking-api/
├── app/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── docker-compose.yml
├── nginx/
│   └── default.conf
├── public/
├── routes/
├── .env
├── Dockerfile
└── README.md
```

---

## 🌐 Access the API

- Base URL: [http://localhost](http://localhost)
- API routes are defined in `routes/api.php`

---

## 🧰 Troubleshooting

- If migrations fail, ensure the database container is fully initialized.
- Use `docker compose logs` to debug container issues.
- Check `.env` values and rebuild with `--build` if changes are made.

---

## 📜 License

MIT – free to use and modify.

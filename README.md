# LiftED — Education Management Platform

---

## Stack

| Layer        | Technology                        |
|--------------|-----------------------------------|
| Backend      | Laravel 11 (PHP 8.2+)             |
| Database     | PostgreSQL                        |
| Auth         | Laravel Sanctum — session/cookie  |
| Permissions  | Spatie Laravel Permission v6      |
| Frontend     | Blade + Tailwind CSS (CDN)        |
| File storage | Laravel local disk (protected)    |

---

## Live demo

- **URL**: `http://41.186.188.91:8080/`
- **Note (no HTTPS/TLS yet)**: this demo is served over plain HTTP. Some networks/extensions may warn or block it.

## Requirements

- **PHP**: 8.2+
- **Composer**: 2.x
- **Database**: PostgreSQL 14+ (or any recent Postgres)
- **Extensions**: typical Laravel PHP extensions (mbstring, openssl, pdo, **pdo_pgsql**, tokenizer, xml, ctype, curl, zip)
  - **Ubuntu/Debian**: install Postgres PHP driver with `sudo apt install php-pgsql` (or `php8.2-pgsql`)


## Quick Start

### 1. Clone & install dependencies

```bash
git clone https://github.com/Anglebert-Dev/liftED.git
cd liftED
composer install
cp .env.example .env
php artisan key:generate
```

### 2. Configure PostgreSQL

Edit `.env`:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=lifted
DB_USERNAME=postgres
DB_PASSWORD=your_password
```

Create the database:

```sql
CREATE DATABASE lifted;
```

### 3. Migrate, seed, and prep storage

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
```

This creates all tables, seeds all permissions from `config/access.php`,
and creates four demo accounts:

| Role       | Email                    | Password  |
|------------|--------------------------|-----------|
| SuperAdmin | admin@lifted.alu.edu     | password  |
| NGO Staff  | staff@lifted.alu.edu     | password  |
| Mentor     | mentor@lifted.alu.edu    | password  |
| Learner    | learner@lifted.alu.edu   | password  |

### 4. Run the server

```bash
php artisan serve
```

Visit: http://localhost:8000

---

## MVP Modules

| Module | Description |
|--------|-------------|
| **Auth & Roles**     | Sanctum session login, role-based dashboard redirect |
| **Programs**         | CRUD for NGO programs, soft delete |
| **Materials**        | File upload/serve, protected by enrollment for learners |
| **Enrollments**      | Enroll learners, assign mentors |
| **Progress**         | Auto-log views/downloads, mentor feedback |
| **Users**            | SuperAdmin user management, approve/ban |

---

## Out of Scope (Planned)

- In-app messaging (FR5)
- Reporting and analytics (FR6)
- Offline / low-bandwidth mode

---

*LiftED — African Leadership University — 2026*

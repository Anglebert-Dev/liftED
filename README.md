# LiftED — Education Management Platform

> Laravel 11 + PostgreSQL + Spatie Permission + Sanctum (session auth)
> Built for NGOs in Sub-Saharan Africa · African Leadership University · 2026

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

## Authentication

This project uses **Laravel Sanctum in session/cookie mode** — not JWT.

**Why Sanctum over JWT for this project:**
- Instant session revocation (JWT tokens can't be invalidated before expiry)
- Built-in CSRF protection via `@csrf` in all forms
- Native Laravel integration — no third-party token library
- `remember_me` support out of the box
- Correct choice for server-rendered Blade apps (no SPA/mobile API needed in MVP)

---

## Quick Start

### 1. Clone & install

```bash
git clone <repo>
cd lifted
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

### 3. Run migrations & seed

```bash
php artisan migrate
php artisan db:seed
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

## Architecture

Every HTTP request passes through layers **in strict order**. No layer skips another.

```
Request
  → FormRequest   (validation only)
  → Controller    (thin — calls one service method, returns response)
  → Service       (business logic, file handling, orchestration)
  → Repository    (all DB queries — no business logic)
  → Model         (relationships, casts, scopes, state helpers)
  → PostgreSQL
```

### Base classes

| Class            | Provides                                              |
|------------------|-------------------------------------------------------|
| `BaseModel`      | UUID auto-generation, audit fields, soft deletes      |
| `BaseRepository` | `find`, `findByUuid`, `all`, `save`, `delete`         |
| `BaseService`    | `success()` / `failure()` response helpers            |
| `BaseController` | `successRedirect()` / `errorRedirect()` helpers       |

---

## Permission System

All permissions are generated automatically from `config/access.php`.
**No seeders, no manual permission management.**

Permission name format: `{action} {module}.{controller}`

Examples:
- `create programs.program`
- `upload programs.material`
- `list learners.enrollment`
- `read learners.progress`

### AuthHelper (A::)

```php
use App\Helpers\AuthHelper as A;

A::can('create programs.program');                              // bool
A::can('create programs.program|update programs.program');     // OR logic
A::require('delete programs.program');                         // throws 403 redirect if denied
```

`A::require()` is called at the top of every controller method.
`A::can()` is used in Blade for conditional navigation/buttons.

### Adding a new module

1. Add it to `config/access.php` under `access_modules`
2. Create Model → Repository → Service → FormRequest → Controller → Policy
3. Add routes to `routes/web.php`
4. Add Blade views to `resources/views/`
5. Run:

```bash
php artisan tinker
>>> app(\App\Services\Permission\PermissionService::class)->initPermissions(true);
```

SuperAdmin gets all new permissions automatically.

---

## User Roles

| Role       | Spatie Role | Capabilities                                         |
|------------|-------------|------------------------------------------------------|
| superadmin | SuperAdmin  | Everything — all permissions auto-assigned           |
| ngo_staff  | NGO Staff   | Create programs, upload materials, manage enrollments|
| mentor     | Mentor      | View assigned learners' progress, add feedback       |
| learner    | Learner     | Access enrolled programs and materials               |

---

## File Storage

All uploaded materials are stored on the **local disk** (`storage/app/materials/`).
They are **never** served from the public disk.

Files are served through the protected route:
```
GET /programs/{program}/materials/{material}
```

This route:
1. Checks the user's `read programs.material` permission
2. For learners: verifies enrollment before serving
3. Logs a `progress` record (viewed_at) automatically
4. Streams the file as a download

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
- REST API layer

---

*LiftED — African Leadership University — 2026*

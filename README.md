# 📚 IAE-LMS (Integrated Academic Environment - Learning Management System)

Aplikasi Learning Management System berbasis Laravel dengan arsitektur multi-database untuk memisahkan data berdasarkan role (Admin, Guru, Siswa).

---

## 📋 Daftar Isi

- [Fitur Aplikasi](#-fitur-aplikasi)
- [Tech Stack](#-tech-stack)
- [Requirements](#-requirements)
- [Instalasi Lokal](#-instalasi-lokal)
- [Instalasi dengan Docker](#-instalasi-dengan-docker)
- [Akun Demo](#-akun-demo)
- [Struktur Folder](#-struktur-folder)
- [API GraphQL](#-api-graphql)

---

## ✨ Fitur Aplikasi

### 👨‍💼 Admin
- Dashboard statistik
- Manajemen user (CRUD)
- Monitoring sistem

### 👨‍🏫 Teacher (Guru)
- Manajemen kursus
- Upload materi pembelajaran
- Buat tugas (assignments)
- Buat kuis dengan berbagai tipe soal
- Lihat submission siswa
- Absensi siswa

### 👨‍🎓 Student (Siswa)
- Lihat dan enroll kursus
- Akses materi pembelajaran
- Submit tugas
- Mengerjakan kuis
- Lihat nilai dan progress

---

## 🛠 Tech Stack

| Teknologi | Versi |
|-----------|-------|
| PHP | >= 8.2 |
| Laravel | 11.x |
| MySQL | >= 8.0 |
| Node.js | >= 18.x |
| NPM | >= 9.x |
| Composer | >= 2.x |

---

## 📦 Requirements

Pastikan sudah terinstall:
- **PHP 8.2+** dengan extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`
- **Composer** (https://getcomposer.org/)
- **Node.js & NPM** (https://nodejs.org/)
- **MySQL 8.0+** atau **MariaDB 10.4+**
- **Git** (opsional)

---

## 🚀 Instalasi Lokal

### Step 1: Masuk ke Folder Project

```bash
cd IAE-TUBES-SUBMISSION
```

### Step 2: Install Dependencies PHP

```bash
composer install
```

### Step 3: Install Dependencies Node.js

```bash
npm install
```

### Step 4: Copy Environment File

```bash
# Windows (CMD)
copy .env.example .env

# Windows (PowerShell)
Copy-Item .env.example .env

# Linux/Mac
cp .env.example .env
```

### Step 5: Generate Application Key

```bash
php artisan key:generate
```

### Step 6: Buat Database di MySQL

Buka MySQL (phpMyAdmin/HeidiSQL/CLI) dan jalankan:

```sql
CREATE DATABASE lms_main;
CREATE DATABASE lms_guru;
CREATE DATABASE lms_siswa;
CREATE DATABASE lms_admin;
```

### Step 7: Konfigurasi Database

Edit file `.env` dan sesuaikan:

```env
# Main Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lms_main
DB_USERNAME=root
DB_PASSWORD=

# Database Guru
DB_GURU_HOST=127.0.0.1
DB_GURU_PORT=3306
DB_GURU_DATABASE=lms_guru
DB_GURU_USERNAME=root
DB_GURU_PASSWORD=

# Database Siswa
DB_SISWA_HOST=127.0.0.1
DB_SISWA_PORT=3306
DB_SISWA_DATABASE=lms_siswa
DB_SISWA_USERNAME=root
DB_SISWA_PASSWORD=

# Database Admin
DB_ADMIN_HOST=127.0.0.1
DB_ADMIN_PORT=3306
DB_ADMIN_DATABASE=lms_admin
DB_ADMIN_USERNAME=root
DB_ADMIN_PASSWORD=
```

### Step 8: Jalankan Migrasi Database

```bash
# Migrasi main database
php artisan migrate

# Migrasi guru database
php artisan migrate --database=guru --path=database/migrations/multi_db/2025_12_19_000001_create_guru_database_tables.php

# Migrasi siswa database
php artisan migrate --database=siswa --path=database/migrations/multi_db/2025_12_19_000002_create_siswa_database_tables.php

# Migrasi admin database
php artisan migrate --database=admin --path=database/migrations/multi_db/2025_12_19_000003_create_admin_database_tables.php
```

### Step 9: Seed Data Demo (Opsional)

```bash
php artisan db:seed
```

### Step 10: Buat Storage Link

```bash
php artisan storage:link
```

### Step 11: Build Assets Frontend

```bash
npm run build
```

### Step 12: Jalankan Development Server

```bash
php artisan serve
```

Buka browser dan akses: **http://localhost:8000**

---

## 🐳 Instalasi dengan Docker

### Step 1: Copy Environment Docker

```bash
# Windows (PowerShell)
Copy-Item .env.example .env

# Linux/Mac
cp .env.example .env
```

### Step 2: Build dan Jalankan Container

```bash
docker-compose up -d --build
```

### Step 3: Install Dependencies di Container

```bash
docker-compose exec app composer install
docker-compose exec app npm install
docker-compose exec app npm run build
```

### Step 4: Generate Key dan Migrasi

```bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan storage:link
```

Akses aplikasi di: **http://localhost:8080**

---

## 👤 Akun Demo

Setelah menjalankan `php artisan db:seed`, akun demo yang tersedia:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | password |
| Teacher | teacher@example.com | password |
| Student | student@example.com | password |
| Test User | test@example.com | password |

---

## 📁 Struktur Folder

```
IAE-TUBES-SUBMISSION/
├── app/                    # Source code PHP
│   ├── Console/            # Artisan commands
│   ├── GraphQL/            # GraphQL resolvers & scalars
│   ├── Http/
│   │   ├── Controllers/    # Controllers (Admin, Auth, Student, Teacher)
│   │   ├── Middleware/     # Custom middleware
│   │   └── Requests/       # Form requests validation
│   ├── Models/             # Eloquent models
│   ├── Policies/           # Authorization policies
│   ├── Providers/          # Service providers
│   └── View/               # View composers
├── bootstrap/              # Laravel bootstrap files
├── config/                 # Configuration files
├── database/
│   ├── factories/          # Model factories
│   ├── migrations/         # Database migrations
│   │   └── multi_db/       # Multi-database migrations
│   └── seeders/            # Database seeders
├── docker/                 # Docker configuration (nginx)
├── docs/                   # Documentation
├── graphql/                # GraphQL schema files
├── public/                 # Public assets (entry point)
├── resources/
│   ├── css/                # Stylesheet source
│   ├── js/                 # JavaScript source
│   └── views/              # Blade templates
│       ├── admin/          # Admin views
│       ├── auth/           # Authentication views
│       ├── components/     # Reusable components
│       ├── layouts/        # Layout templates
│       ├── student/        # Student views
│       └── teacher/        # Teacher views
├── routes/                 # Route definitions
│   ├── web.php             # Web routes
│   ├── auth.php            # Authentication routes
│   └── console.php         # Console routes
├── storage/                # Storage (logs, cache, sessions)
├── tests/                  # PHPUnit tests
│   ├── Feature/            # Feature tests
│   └── Unit/               # Unit tests
├── .env.example            # Environment template
├── artisan                 # Laravel CLI
├── composer.json           # PHP dependencies
├── docker-compose.yml      # Docker Compose configuration
├── Dockerfile              # Docker build instructions
├── package.json            # Node.js dependencies
├── phpunit.xml             # PHPUnit configuration
├── tailwind.config.js      # TailwindCSS configuration
└── vite.config.js          # Vite configuration
```

---

## 🔌 API GraphQL

Aplikasi menyediakan GraphQL API untuk integrasi.

### Endpoint
```
POST /graphql
```

### GraphQL Playground
```
GET /graphql-playground
```

### Contoh Query

```graphql
# Get all courses
query {
  courses {
    id
    name
    code
    teacher {
      name
    }
  }
}

# Get user by ID
query {
  user(id: 1) {
    id
    name
    email
    role
  }
}
```

---

## 🧪 Menjalankan Tests

```bash
# Jalankan semua tests
php artisan test

# Jalankan dengan coverage
php artisan test --coverage

# Jalankan specific test
php artisan test --filter=AuthenticationTest
```

---

## 🔧 Troubleshooting

### Error: SQLSTATE[HY000] [1045] Access denied
- Pastikan username dan password database di `.env` sudah benar

### Error: Class not found
- Jalankan: `composer dump-autoload`

### Error: Vite manifest not found
- Jalankan: `npm run build`

### Error: Permission denied (storage/logs)
- Windows: Pastikan folder storage dapat ditulis
- Linux/Mac: `chmod -R 775 storage bootstrap/cache`

---

## 📝 Perintah Artisan Berguna

```bash
# Clear semua cache
php artisan optimize:clear

# Cache config untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Rollback migration
php artisan migrate:rollback

# Fresh migration + seed
php artisan migrate:fresh --seed

# List semua routes
php artisan route:list
```

---

## 📄 License

MIT License - Silakan gunakan untuk keperluan akademik.

---

**Tugas Besar - Interaksi Aplikasi Enterprise**


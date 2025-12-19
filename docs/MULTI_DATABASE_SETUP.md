# Multi-Database Setup Guide

Aplikasi LMS ini mendukung arsitektur multi-database untuk memisahkan data berdasarkan role.

## 📁 Struktur Database

```
📁 Main Database (default)     → users, sessions, cache
📁 lms_guru (connection: guru) → courses, materials, assignments, quizzes
📁 lms_siswa (connection: siswa) → enrollments, submissions, quiz_attempts
📁 lms_admin (connection: admin) → activity_logs, settings, audit_trails
```

## 🛠️ Setup Steps

### 1. Buat Database di MySQL

```sql
CREATE DATABASE lms_main;
CREATE DATABASE lms_guru;
CREATE DATABASE lms_siswa;
CREATE DATABASE lms_admin;
```

### 2. Update .env

Tambahkan ke file `.env`:

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

### 3. Jalankan Migrations

```bash
# Main database (users, sessions)
php artisan migrate

# Guru database
php artisan migrate --database=guru --path=database/migrations/multi_db/2025_12_19_000001_create_guru_database_tables.php

# Siswa database
php artisan migrate --database=siswa --path=database/migrations/multi_db/2025_12_19_000002_create_siswa_database_tables.php

# Admin database
php artisan migrate --database=admin --path=database/migrations/multi_db/2025_12_19_000003_create_admin_database_tables.php
```

## 🔌 Penggunaan di Model

### Model dengan Connection Tertentu

```php
// Course.php (Guru Database)
class Course extends Model
{
    protected $connection = 'guru';
}

// Enrollment.php (Siswa Database)
class Enrollment extends Model
{
    protected $connection = 'siswa';
}

// ActivityLog.php (Admin Database)
class ActivityLog extends Model
{
    protected $connection = 'admin';
}
```

### Cross-Database Query

```php
// Dari siswa ke guru
$enrollment = Enrollment::on('siswa')->find(1);
$course = Course::on('guru')->find($enrollment->course_id);

// Menggunakan DB facade
$courses = DB::connection('guru')->table('courses')->get();
$enrollments = DB::connection('siswa')->table('enrollments')->get();
```

## ⚠️ Catatan Penting

1. **Foreign Key Constraints**: Tidak bisa menggunakan foreign key antar database berbeda
2. **Joins**: Tidak bisa langsung JOIN antar database, gunakan PHP untuk menggabungkan
3. **Transactions**: Setiap database punya transaction sendiri
4. **Backup**: Backup semua database secara terpisah

## 📋 Mapping Tabel

| Connection | Database   | Tables                                           |
|------------|------------|--------------------------------------------------|
| mysql      | lms_main   | users, sessions, cache, migrations               |
| guru       | lms_guru   | courses, materials, assignments, quizzes, dll    |
| siswa      | lms_siswa  | enrollments, submissions, quiz_attempts, dll     |
| admin      | lms_admin  | activity_logs, system_settings, audit_trails     |

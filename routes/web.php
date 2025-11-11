<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    DashboardController,
    CoursePublicController,
    ProfileController
};
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Teacher\CourseController as TeacherCourse;
use App\Http\Controllers\Student\EnrollController as StudentEnroll;
use App\Http\Middleware\RoleMiddleware; // ✅ Penting untuk rute middleware berdasarkan role

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//
// Halaman utama (Publik)
//
Route::get('/', [HomeController::class, 'index'])->name('home');

//
// Dashboard (butuh login)
//
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

//
// Admin-only
//
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin/users', AdminUser::class)->name('admin.users');
});



//
// Teacher-only
//
Route::middleware(['auth', RoleMiddleware::class . ':teacher'])->group(function () {
    Route::get('/teacher/courses', [TeacherCourse::class, 'index'])->name('teacher.courses');
    Route::get('/teacher/courses/create', [TeacherCourse::class, 'create'])->name('teacher.courses.create');
    Route::post('/teacher/courses', [TeacherCourse::class, 'store'])->name('teacher.courses.store');
    Route::get('/teacher/courses/{course}/edit', [TeacherCourse::class, 'edit'])->name('teacher.courses.edit');
    Route::put('/teacher/courses/{course}', [TeacherCourse::class, 'update'])->name('teacher.courses.update');
    Route::delete('/teacher/courses/{course}', [TeacherCourse::class, 'destroy'])->name('teacher.courses.destroy');
});

//
// Student-only
//
Route::middleware(['auth', RoleMiddleware::class . ':student'])->group(function () {
    Route::get('/student/my-courses', [StudentEnroll::class, 'myCourses'])->name('student.mycourses');
    Route::post('/student/enroll/{course}', [StudentEnroll::class, 'enroll'])->name('student.enroll');
});

//
// Publik (katalog & detail kursus)
//
Route::get('/courses', [CoursePublicController::class, 'index'])->name('courses.catalog');
Route::get('/courses/{course}', [CoursePublicController::class, 'show'])->name('courses.show');

//
// Profile (Breeze default)
//
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


//
// Auth scaffolding (login/register/logout)
//
require __DIR__ . '/auth.php';

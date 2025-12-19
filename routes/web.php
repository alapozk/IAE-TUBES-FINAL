<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;

// ================= Controllers =================
use App\Http\Controllers\{
    HomeController,
    DashboardController,
    CoursePublicController,
    ProfileController
};

// DEBUG: Test course query
Route::get('/debug-courses', function() {
    $courses = \App\Models\Course::all();
    return response()->json([
        'count' => $courses->count(),
        'courses' => $courses->toArray(),
        'connection' => (new \App\Models\Course)->getConnectionName()
    ]);
});

// Admin
use App\Http\Controllers\Admin\UserController as AdminUser;

// Teacher
use App\Http\Controllers\Teacher\{
    CourseController as TeacherCourseController,
    MaterialController as TeacherMaterialController,
    AssignmentController as TeacherAssignmentController,
    QuizController as TeacherQuizController,
    QuizQuestionController
};

// Student
use App\Http\Controllers\Student\{
    EnrollController as StudentEnrollController,
    CourseController as StudentCourseController,
    AssignmentController as StudentAssignmentController,
    SubmissionController as StudentSubmissionController,
    QuizAttemptController as StudentQuizAttemptController,
    MaterialController as StudentMaterialController
};

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::middleware('auth')
    ->get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class . ':admin'])
    ->group(function () {
        Route::get('/admin', function() {
            return view('admin.dashboard');
        })->name('admin.dashboard');
        Route::get('/admin/users', AdminUser::class)
            ->name('admin.users');
    });

/*
|--------------------------------------------------------------------------
| Teacher
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class . ':teacher'])
    ->prefix('teacher')
    ->group(function () {

        // ===== Courses =====
        Route::get('/courses', [TeacherCourseController::class, 'index'])
            ->name('teacher.courses');
        Route::get('/courses/create', [TeacherCourseController::class, 'create'])
            ->name('teacher.courses.create');
        Route::post('/courses', [TeacherCourseController::class, 'store'])
            ->name('teacher.courses.store');
        Route::get('/courses/{course}', [TeacherCourseController::class, 'show'])
            ->name('teacher.courses.show');
        Route::get('/courses/{course}/edit', [TeacherCourseController::class, 'edit'])
            ->name('teacher.courses.edit');
        Route::put('/courses/{course}', [TeacherCourseController::class, 'update'])
            ->name('teacher.courses.update');
        Route::delete('/courses/{course}', [TeacherCourseController::class, 'destroy'])
            ->name('teacher.courses.destroy');

        // ===== Materials =====
        Route::get('/courses/{course}/materials/create', [TeacherMaterialController::class, 'create'])
            ->name('teacher.materials.create');
        Route::post('/courses/{course}/materials', [TeacherMaterialController::class, 'store'])
            ->name('teacher.materials.store');
        Route::get('/courses/{course}/materials/{material}', [TeacherMaterialController::class, 'show'])
            ->name('teacher.materials.show');
        Route::get('/courses/{course}/materials/{material}/edit', [TeacherMaterialController::class, 'edit'])
            ->name('teacher.materials.edit');
        Route::put('/courses/{course}/materials/{material}', [TeacherMaterialController::class, 'update'])
            ->name('teacher.materials.update');
        Route::delete('/courses/{course}/materials/{material}', [TeacherMaterialController::class, 'destroy'])
            ->name('teacher.materials.destroy');

        // ===== Assignments =====
        Route::get('/courses/{course}/assignments/create', [TeacherAssignmentController::class, 'create'])
            ->name('teacher.assignments.create');
        Route::post('/courses/{course}/assignments', [TeacherAssignmentController::class, 'store'])
            ->name('teacher.assignments.store');
        Route::get('/courses/{course}/assignments/{assignment}', [TeacherAssignmentController::class, 'show'])
            ->name('teacher.assignments.show');
        Route::get('/courses/{course}/assignments/{assignment}/edit', [TeacherAssignmentController::class, 'edit'])
            ->name('teacher.assignments.edit');
        Route::put('/courses/{course}/assignments/{assignment}', [TeacherAssignmentController::class, 'update'])
            ->name('teacher.assignments.update');
        Route::delete('/courses/{course}/assignments/{assignment}', [TeacherAssignmentController::class, 'destroy'])
            ->name('teacher.assignments.destroy');

        // ===== Quizzes =====
        Route::get('/courses/{course}/quizzes/create', [TeacherQuizController::class, 'create'])
            ->name('teacher.courses.quizzes.create');
        Route::post('/courses/{course}/quizzes', [TeacherQuizController::class, 'store'])
            ->name('teacher.courses.quizzes.store');
        Route::get('/courses/{course}/quizzes/{quiz}/edit', [TeacherQuizController::class, 'edit'])
            ->name('teacher.courses.quizzes.edit');
        Route::put('/courses/{course}/quizzes/{quiz}', [TeacherQuizController::class, 'update'])
            ->name('teacher.courses.quizzes.update');
        Route::patch('/courses/{course}/quizzes/{quiz}/toggle', [TeacherQuizController::class, 'togglePublish'])
            ->name('teacher.courses.quizzes.toggle');
        Route::delete('/courses/{course}/quizzes/{quiz}', [TeacherQuizController::class, 'destroy'])
            ->name('teacher.courses.quizzes.destroy');

        // ===== Quiz Questions (MULTI SOAL) =====
        Route::get('/quizzes/{quiz}/questions/create', [QuizQuestionController::class, 'create'])
            ->name('teacher.quiz.questions.create');
        Route::post('/quizzes/{quiz}/questions', [QuizQuestionController::class, 'store'])
            ->name('teacher.quiz.questions.store');
    });

/*
|--------------------------------------------------------------------------
| Student
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', RoleMiddleware::class . ':student'])
    ->prefix('student')
    ->group(function () {

        Route::get('/my-courses', [StudentEnrollController::class, 'myCourses'])
            ->name('student.mycourses');

        Route::post('/enroll/{course}', [StudentEnrollController::class, 'enroll'])
            ->name('student.enroll');

        Route::get('/courses/{course}', [StudentCourseController::class, 'show'])
            ->name('student.courses.show');

        // ===== Assignments Index (FIX ERROR) =====
        Route::get('/assignments', [StudentAssignmentController::class, 'index'])
            ->name('student.assignments.index');

        // ===== Materials =====
        Route::get('/courses/{course}/materials/{material}', [StudentMaterialController::class, 'show'])
            ->name('student.materials.show');
        // Alias for /course singular
        Route::get('/course/{course}/materials/{material}', [StudentMaterialController::class, 'show']);

        // ===== Assignments Detail & Submit =====
        Route::get('/courses/{course}/assignments/{assignment}', [StudentAssignmentController::class, 'show'])
            ->name('student.assignments.show');
        // Alias for /course singular
        Route::get('/course/{course}/assignments/{assignment}', [StudentAssignmentController::class, 'show']);

        Route::get('/courses/{course}/assignments/{assignment}/submit', [StudentSubmissionController::class, 'create'])
            ->name('student.submissions.create');

        Route::post('/courses/{course}/assignments/{assignment}/submit', [StudentSubmissionController::class, 'store'])
            ->name('student.submissions.store');

        // ===== Quiz =====
        Route::get('/quiz/{quiz}/start', [StudentQuizAttemptController::class, 'start'])
            ->name('student.quiz.start');

        Route::get('/quiz/{quiz}/attempt/{attempt}', [StudentQuizAttemptController::class, 'attempt'])
            ->name('student.quiz.attempt');

        Route::get('/quiz/{quiz}/result/{attempt}', [StudentQuizAttemptController::class, 'result'])
            ->name('student.quiz.result');

        Route::post('/quiz/{quiz}/answer', [StudentQuizAttemptController::class, 'answer'])
            ->name('student.quiz.answer');
    });

/*
|--------------------------------------------------------------------------
| Public Course Catalog
|--------------------------------------------------------------------------
*/
Route::get('/courses', [CoursePublicController::class, 'index'])
    ->name('courses.catalog');

Route::get('/courses/{course}', [CoursePublicController::class, 'show'])
    ->name('courses.show');

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Auth (Laravel Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

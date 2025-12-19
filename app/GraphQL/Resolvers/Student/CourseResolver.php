<?php

namespace App\GraphQL\Resolvers\Student;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseResolver
{
    /**
     * Show course detail for enrolled student
     */
    public function show($_, array $args)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            throw new \Exception('Anda harus login terlebih dahulu.');
        }

        // Get course from guru database
        $course = Course::find($args['id']);

        if (!$course) {
            throw new \Exception('Kursus dengan ID ' . $args['id'] . ' tidak ditemukan.');
        }

        // Check enrollment using direct query to siswa database
        $isEnrolled = DB::connection('siswa')
            ->table('enrollments')
            ->where('course_id', $course->id)
            ->where('student_id', $userId)
            ->exists();

        if (!$isEnrolled) {
            throw new \Exception('Anda belum terdaftar di kursus ini.');
        }

        // Get teacher from main database
        $course->teacher = User::find($course->teacher_id);

        // Load materials, assignments, quizzes (from guru database)
        $course->load(['materials', 'assignments', 'quizzes.questions']);

        return $course;
    }
}

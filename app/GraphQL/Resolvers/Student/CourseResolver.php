<?php

namespace App\GraphQL\Resolvers\Student;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

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

        $course = Course::with(['teacher', 'materials', 'assignments', 'quizzes.questions'])
            ->find($args['id']);

        if (!$course) {
            throw new \Exception('Kursus dengan ID ' . $args['id'] . ' tidak ditemukan.');
        }

        // Check enrollment using the student_id pivot
        $isEnrolled = $course->students()
            ->where('users.id', $userId)
            ->exists();

        if (!$isEnrolled) {
            throw new \Exception('Anda belum terdaftar di kursus ini. (User ID: ' . $userId . ')');
        }

        return $course;
    }
}

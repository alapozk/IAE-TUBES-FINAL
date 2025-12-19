<?php

namespace App\GraphQL\Resolvers\Student;

use App\Models\Course;
use App\Models\Material;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class MaterialResolver
{
    /**
     * List materials for enrolled student
     */
    public function listByCourse($_, array $args)
    {
        $course = Course::findOrFail($args['course_id']);

        // Check enrollment
        $isEnrolled = $course->students()
            ->where('users.id', Auth::id())
            ->exists();

        if (!$isEnrolled) {
            throw new \Exception('Anda belum terdaftar di kursus ini.');
        }

        return $course->materials()->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get single material by ID (for enrolled students)
     */
    public function show($_, array $args)
    {
        $material = Material::with('course')->findOrFail($args['id']);
        
        // Check if student is enrolled in the course
        $isEnrolled = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $material->course_id)
            ->exists();
            
        if (!$isEnrolled) {
            throw new \Exception('Anda belum terdaftar di kursus ini.');
        }
        
        return $material;
    }
}

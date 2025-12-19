<?php

namespace App\GraphQL\Resolvers\Student;

use App\Models\Course;
use App\Models\Material;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MaterialResolver
{
    /**
     * List materials for enrolled student
     */
    public function listByCourse($_, array $args)
    {
        $course = Course::findOrFail($args['course_id']);

        // Check enrollment using direct query to siswa database (cross-database)
        $isEnrolled = DB::connection('siswa')
            ->table('enrollments')
            ->where('course_id', $course->id)
            ->where('student_id', Auth::id())
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
        
        // Check if student is enrolled in the course (cross-database)
        $isEnrolled = DB::connection('siswa')
            ->table('enrollments')
            ->where('course_id', $material->course_id)
            ->where('student_id', Auth::id())
            ->exists();
            
        if (!$isEnrolled) {
            throw new \Exception('Anda belum terdaftar di kursus ini.');
        }
        
        return $material;
    }
}

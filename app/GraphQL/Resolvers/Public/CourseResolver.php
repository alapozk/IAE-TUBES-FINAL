<?php

namespace App\GraphQL\Resolvers\Public;

use App\Models\Course;
use App\Models\User;

class CourseResolver
{
    // Public: daftar semua course yang tersedia
    public function list($_, array $args)
    {
        try {
            // Get courses from guru database
            $courses = Course::whereIn('status', ['active', 'published'])->get();
            
            // If no active courses, get all
            if ($courses->isEmpty()) {
                $courses = Course::all();
            }
            
            // Add teacher from main database
            foreach ($courses as $course) {
                $course->teacher = User::find($course->teacher_id);
            }
            
            return $courses;
        } catch (\Exception $e) {
            return [];
        }
    }

    // Public: detail course
    public function show($_, array $args)
    {
        try {
            $course = Course::findOrFail($args['id']);
            $course->teacher = User::find($course->teacher_id);
            return $course;
        } catch (\Exception $e) {
            return null;
        }
    }
}

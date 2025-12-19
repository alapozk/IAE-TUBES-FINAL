<?php

namespace App\GraphQL\Resolvers\Admin;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseResolver
{
    /**
     * List all courses (admin only)
     */
    public function list($_, array $args)
    {
        // Verify admin role
        if (Auth::user()->role !== 'admin') {
            throw new \Exception('Unauthorized. Admin access required.');
        }

        return Course::with(['teacher', 'enrollments'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($course) {
                $course->student_count = $course->enrollments->count();
                return $course;
            });
    }
}

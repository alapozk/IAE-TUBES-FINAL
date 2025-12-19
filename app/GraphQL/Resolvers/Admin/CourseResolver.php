<?php

namespace App\GraphQL\Resolvers\Admin;

use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseResolver
{
    /**
     * List all courses (admin only)
     */
    public function list($_, array $args)
    {
        // Verify admin role
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            throw new \Exception('Unauthorized. Admin access required.');
        }

        try {
            $courses = Course::orderBy('created_at', 'desc')->get();
            
            // Add teacher and student_count
            foreach ($courses as $course) {
                // Get teacher from main database
                $course->teacher = User::find($course->teacher_id);
                
                // Get student count from siswa database
                $course->student_count = DB::connection('siswa')
                    ->table('enrollments')
                    ->where('course_id', $course->id)
                    ->count();
            }
            
            return $courses;
        } catch (\Exception $e) {
            return [];
        }
    }
}

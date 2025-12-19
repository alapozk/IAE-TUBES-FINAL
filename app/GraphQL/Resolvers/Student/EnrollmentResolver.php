<?php

namespace App\GraphQL\Resolvers\Student;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class EnrollmentResolver
{
    /**
     * Get student's enrolled courses
     */
    public function myCourses($_, array $args)
    {
        return Enrollment::with('course')
            ->where('student_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Enroll in a course
     */
    public function enroll($_, array $args)
    {
        $course = Course::findOrFail($args['course_id']);

        $enrollment = Enrollment::firstOrCreate([
            'course_id' => $course->id,
            'student_id' => Auth::id(),
        ], [
            'status' => 'enrolled'
        ]);

        return $enrollment->load('course');
    }
}

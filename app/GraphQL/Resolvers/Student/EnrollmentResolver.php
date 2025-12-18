<?php

namespace App\GraphQL\Resolvers\Student;

use App\GraphQL\Resolvers\BaseResolver;
use App\Models\Enrollment;
use App\Models\Course;

class EnrollmentResolver extends BaseResolver
{
    // Enroll ke course
    public function enroll($_, array $args)
    {
        $this->authorizeStudent();

        $course = Course::findOrFail($args['course_id']);

        return Enrollment::firstOrCreate(
            [
                'user_id'   => $args['user_id'],
                'course_id' => $course->id,
            ],
            [
                'status' => 'active',
            ]
        );
    }

    // List course yang diikuti
    public function myCourses($_, array $args)
    {
        $this->authorizeStudent();

        return Enrollment::with('course')
            ->where('user_id', $args['user_id'])
            ->get();
    }
}

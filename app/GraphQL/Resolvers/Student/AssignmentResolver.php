<?php

namespace App\GraphQL\Resolvers\Student;

use App\GraphQL\Resolvers\BaseResolver;
use App\Models\Assignment;

class AssignmentResolver extends BaseResolver
{
    // Student: lihat assignment per course
    public function listByCourse($_, array $args)
    {
        $this->authorizeStudent();

        return Assignment::where('course_id', $args['course_id'])->get();
    }
}

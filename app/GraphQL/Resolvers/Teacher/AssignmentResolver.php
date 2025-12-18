<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\GraphQL\Resolvers\BaseResolver;
use App\Models\Assignment;

class AssignmentResolver extends BaseResolver
{
    // Teacher: buat assignment
    public function store($_, array $args)
    {
        $this->authorizeTeacher();

        return Assignment::create([
            'course_id'   => $args['course_id'],
            'title'       => $args['title'],
            'description' => $args['description'] ?? null,
            'deadline'    => $args['deadline'],
        ]);
    }

    // Teacher: list assignment per course
    public function listByCourse($_, array $args)
    {
        $this->authorizeTeacher();

        return Assignment::where('course_id', $args['course_id'])->get();
    }
}

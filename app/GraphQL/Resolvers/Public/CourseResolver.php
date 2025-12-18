<?php

namespace App\GraphQL\Resolvers\Public;

use App\Models\Course;

class CourseResolver
{
    // Public: daftar course
    public function list($_, array $args)
    {
        return Course::where('is_published', true)->get();
    }

    // Public: detail course
    public function show($_, array $args)
    {
        return Course::where('is_published', true)
            ->findOrFail($args['course_id']);
    }
}

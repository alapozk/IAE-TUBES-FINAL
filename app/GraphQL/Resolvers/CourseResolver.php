<?php

namespace App\GraphQL\Resolvers;

use App\Models\Course;

class CourseResolver
{
    /**
     * List all published courses (public)
     */
    public function list($_, array $args)
    {
        return Course::where('status', 'published')
            ->with('teacher')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Show single course (public)
     */
    public function show($_, array $args)
    {
        return Course::with(['teacher', 'materials', 'assignments', 'quizzes'])
            ->findOrFail($args['id']);
    }
}

<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\Models\Course;
use App\Models\Assignment;
use Illuminate\Support\Facades\Auth;

class AssignmentResolver
{
    /**
     * List assignments by course
     */
    public function listByCourse($_, array $args)
    {
        $course = Course::findOrFail($args['course_id']);
        
        if ($course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        return $course->assignments()->with('submissions')->orderBy('due_at', 'asc')->get();
    }

    /**
     * Show single assignment
     */
    public function show($_, array $args)
    {
        $assignment = Assignment::with(['course', 'submissions.student'])->findOrFail($args['id']);
        
        if ($assignment->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        return $assignment;
    }

    /**
     * Create assignment
     */
    public function store($_, array $args)
    {
        $course = Course::findOrFail($args['course_id']);
        
        if ($course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        return Assignment::create([
            'course_id' => $course->id,
            'title' => $args['title'],
            'instructions' => $args['instructions'] ?? null,
            'due_at' => $args['due_at'] ?? null,
            'submission_mode' => $args['submission_mode'],
            'max_points' => $args['max_points'] ?? null,
        ]);
    }

    /**
     * Update assignment
     */
    public function update($_, array $args)
    {
        $assignment = Assignment::with('course')->findOrFail($args['id']);
        
        if ($assignment->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        $assignment->update([
            'title' => $args['title'],
            'instructions' => $args['instructions'] ?? $assignment->instructions,
            'due_at' => $args['due_at'] ?? $assignment->due_at,
            'submission_mode' => $args['submission_mode'],
            'max_points' => $args['max_points'] ?? $assignment->max_points,
        ]);

        return $assignment->fresh();
    }

    /**
     * Delete assignment
     */
    public function delete($_, array $args)
    {
        $assignment = Assignment::with('course')->findOrFail($args['id']);
        
        if ($assignment->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        $assignment->delete();
        return true;
    }
}

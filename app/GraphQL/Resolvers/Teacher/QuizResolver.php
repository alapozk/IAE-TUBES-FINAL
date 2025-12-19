<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Support\Facades\Auth;

class QuizResolver
{
    /**
     * List quizzes by course
     */
    public function listByCourse($_, array $args)
    {
        $course = Course::findOrFail($args['course_id']);
        
        if ($course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        return $course->quizzes()->with('questions')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Show single quiz
     */
    public function show($_, array $args)
    {
        $quiz = Quiz::with(['course', 'questions'])->findOrFail($args['id']);
        
        if ($quiz->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        return $quiz;
    }

    /**
     * Create quiz
     */
    public function store($_, array $args)
    {
        $course = Course::findOrFail($args['course_id']);
        
        if ($course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        return Quiz::create([
            'course_id' => $course->id,
            'title' => $args['title'],
            'max_attempt' => $args['max_attempt'],
            'duration' => $args['duration'] ?? null,
            'deadline' => $args['deadline'] ?? null,
            'show_review' => $args['show_review'],
            'is_published' => $args['is_published'] ?? false,
        ]);
    }

    /**
     * Update quiz
     */
    public function update($_, array $args)
    {
        $quiz = Quiz::with('course')->findOrFail($args['id']);
        
        if ($quiz->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        $quiz->update([
            'title' => $args['title'],
            'max_attempt' => $args['max_attempt'],
            'duration' => $args['duration'] ?? $quiz->duration,
            'deadline' => $args['deadline'] ?? $quiz->deadline,
            'show_review' => $args['show_review'],
            'is_published' => $args['is_published'],
        ]);

        return $quiz->fresh();
    }

    /**
     * Delete quiz
     */
    public function delete($_, array $args)
    {
        $quiz = Quiz::with('course')->findOrFail($args['id']);
        
        if ($quiz->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        $quiz->questions()->delete();
        $quiz->delete();
        return true;
    }

    /**
     * Toggle quiz publish status
     */
    public function togglePublish($_, array $args)
    {
        $quiz = Quiz::with('course')->findOrFail($args['id']);
        
        if ($quiz->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        $quiz->update(['is_published' => !$quiz->is_published]);
        return $quiz->fresh();
    }
}

<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Support\Facades\Auth;

class QuizQuestionResolver
{
    /**
     * List questions by quiz
     */
    public function list($_, array $args)
    {
        $quiz = Quiz::with('course')->findOrFail($args['quiz_id']);
        
        if ($quiz->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        return $quiz->questions;
    }

    /**
     * Create multiple questions at once
     */
    public function storeMany($_, array $args)
    {
        $quiz = Quiz::with('course')->findOrFail($args['quiz_id']);
        
        if ($quiz->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        $created = [];
        foreach ($args['questions'] as $q) {
            $created[] = QuizQuestion::create([
                'quiz_id' => $quiz->id,
                'question' => $q['question'],
                'option_a' => $q['option_a'],
                'option_b' => $q['option_b'],
                'option_c' => $q['option_c'],
                'option_d' => $q['option_d'],
                'correct_answer' => $q['correct_answer'],
            ]);
        }

        return $created;
    }

    /**
     * Show single question for editing
     */
    public function show($_, array $args)
    {
        $question = QuizQuestion::with('quiz.course')->findOrFail($args['id']);
        
        if ($question->quiz->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        return $question;
    }

    /**
     * Update a quiz question
     */
    public function update($_, array $args)
    {
        $question = QuizQuestion::with('quiz.course')->findOrFail($args['id']);
        
        if ($question->quiz->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        $question->update([
            'question' => $args['question'],
            'option_a' => $args['option_a'],
            'option_b' => $args['option_b'],
            'option_c' => $args['option_c'],
            'option_d' => $args['option_d'],
            'correct_answer' => $args['correct_answer'],
        ]);

        return $question->fresh();
    }

    /**
     * Delete a quiz question
     */
    public function delete($_, array $args)
    {
        $question = QuizQuestion::with('quiz.course')->findOrFail($args['id']);
        
        if ($question->quiz->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        $question->delete();
        return true;
    }
}

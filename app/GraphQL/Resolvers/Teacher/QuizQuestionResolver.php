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
     * Create single question
     */
    public function store($_, array $args)
    {
        $quiz = Quiz::with('course')->findOrFail($args['quiz_id']);
        
        if ($quiz->course->teacher_id !== Auth::id()) {
            throw new \Exception('Unauthorized');
        }

        return QuizQuestion::create([
            'quiz_id' => $quiz->id,
            'question' => $args['question'],
        ]);
    }
}

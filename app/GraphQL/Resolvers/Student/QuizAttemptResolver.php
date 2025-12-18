<?php

namespace App\GraphQL\Resolvers\Student;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Support\Facades\Auth;

class QuizAttemptResolver
{
    public function start($_, array $args)
    {
        $quiz = Quiz::findOrFail($args['quiz_id']);

        return QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => Auth::id(),
            'is_finished' => false,
        ]);
    }

    public function answer($_, array $args)
    {
        QuizAnswer::updateOrCreate(
            [
                'quiz_attempt_id' => $args['attempt_id'],
                'quiz_question_id' => $args['question_id'],
            ],
            [
                'quiz_option_id' => $args['option_id'],
            ]
        );

        return true;
    }
}

<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\GraphQL\Resolvers\BaseResolver;
use App\Models\Quiz;
use App\Models\QuizQuestion;

class QuizQuestionResolver extends BaseResolver
{
    public function list($_, array $args)
    {
        $this->authorizeTeacher();

        return QuizQuestion::where('quiz_id', $args['quiz_id'])->get();
    }

    public function store($_, array $args)
    {
        $this->authorizeTeacher();

        return QuizQuestion::create([
            'quiz_id'  => $args['quiz_id'],
            'question' => $args['question'],
        ]);
    }
}

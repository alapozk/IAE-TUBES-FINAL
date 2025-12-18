<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\GraphQL\Resolvers\BaseResolver;
use App\Models\QuizOption;

class QuizOptionResolver extends BaseResolver
{
    public function store($_, array $args)
    {
        $this->authorizeTeacher();

        return QuizOption::create([
            'quiz_question_id' => $args['quiz_question_id'],
            'option_text'      => $args['option_text'],
            'is_correct'       => $args['is_correct'],
        ]);
    }
}

<?php

namespace App\GraphQL\Resolvers\Teacher;

use Illuminate\Support\Facades\DB;

class QuizFieldResolver
{
    /**
     * Get total attempts count for this quiz
     */
    public function attemptsCount($quiz, array $args)
    {
        return DB::connection('siswa')
            ->table('quiz_attempts')
            ->where('quiz_id', $quiz->id)
            ->count();
    }
}

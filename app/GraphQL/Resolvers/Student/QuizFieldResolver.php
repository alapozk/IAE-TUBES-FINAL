<?php

namespace App\GraphQL\Resolvers\Student;

use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizFieldResolver
{
    /**
     * Get current user's attempt count for this quiz
     */
    public function myAttempts($quiz, array $args)
    {
        if (!Auth::check()) {
            return 0;
        }

        return DB::connection('siswa')
            ->table('quiz_attempts')
            ->where('quiz_id', $quiz->id)
            ->where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('student_id', Auth::id());
            })
            ->count();
    }

    /**
     * Get current user's best score for this quiz
     */
    public function myScore($quiz, array $args)
    {
        if (!Auth::check()) {
            return null;
        }

        $attempt = DB::connection('siswa')
            ->table('quiz_attempts')
            ->where('quiz_id', $quiz->id)
            ->where(function($query) {
                $query->where('user_id', Auth::id())
                      ->orWhere('student_id', Auth::id());
            })
            ->whereNotNull('score')
            ->orderBy('score', 'desc')
            ->first();

        return $attempt ? $attempt->score : null;
    }
}

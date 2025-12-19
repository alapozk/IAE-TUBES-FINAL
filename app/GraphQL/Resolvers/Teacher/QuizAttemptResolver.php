<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizAttemptResolver
{
    /**
     * List all quiz attempts for a quiz (for teacher)
     */
    public function list($_, array $args)
    {
        $quizId = $args['quiz_id'];

        // Get attempts from siswa database
        $attempts = DB::connection('siswa')
            ->table('quiz_attempts')
            ->where('quiz_id', $quizId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get student names from main database
        $studentIds = $attempts->pluck('student_id')->unique()->filter();
        $students = User::whereIn('id', $studentIds)->pluck('name', 'id');

        return $attempts->map(function ($attempt) use ($students) {
            return [
                'id' => $attempt->id,
                'student_id' => $attempt->student_id,
                'student_name' => $students[$attempt->student_id] ?? 'Siswa #' . $attempt->student_id,
                'score' => $attempt->score,
                'status' => $attempt->status ?? 'completed',
                'started_at' => $attempt->started_at,
                'finished_at' => $attempt->finished_at,
            ];
        });
    }
}

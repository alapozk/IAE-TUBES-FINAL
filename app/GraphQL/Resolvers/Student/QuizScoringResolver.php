<?php

namespace App\GraphQL\Resolvers\Student;

use App\GraphQL\Resolvers\BaseResolver;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use App\Models\QuizOption;
use Illuminate\Support\Facades\DB;

class QuizScoringResolver extends BaseResolver
{
    /**
     * Finish attempt dan hitung nilai.
     */
    public function finish($_, array $args)
    {
        $this->authorizeStudent();

        return DB::transaction(function () use ($args) {
            $attempt = QuizAttempt::findOrFail($args['quiz_attempt_id']);

            // Cegah submit ulang
            if ($attempt->is_finished) {
                abort(422, 'Attempt already finished');
            }

            // Ambil semua jawaban attempt
            $answers = QuizAnswer::where('quiz_attempt_id', $attempt->id)->get();

            if ($answers->isEmpty()) {
                abort(422, 'No answers submitted');
            }

            // Hitung benar
            $correctCount = 0;

            foreach ($answers as $answer) {
                $option = QuizOption::find($answer->quiz_option_id);
                if ($option && $option->is_correct) {
                    $correctCount++;
                }
            }

            $totalQuestions = $answers->count();

            // Skor sederhana (persen)
            $score = round(($correctCount / $totalQuestions) * 100, 2);

            // Update attempt
            $attempt->update([
                'is_finished' => true,
                'score'       => $score,
                'finished_at' => now(),
            ]);

            return $attempt;
        });
    }
}

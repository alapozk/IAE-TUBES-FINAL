<?php

namespace App\GraphQL\Resolvers\Student;

use App\GraphQL\Resolvers\BaseResolver;
use App\Models\QuizAnswer;
use App\Models\QuizAttempt;

class QuizAnswerResolver extends BaseResolver
{
    /**
     * Simpan / update jawaban siswa untuk 1 soal.
     */
    public function answer($_, array $args)
    {
        // Auth token (student)
        $this->authorizeStudent();

        // Pastikan attempt ada
        $attempt = QuizAttempt::findOrFail($args['quiz_attempt_id']);

        // Upsert jawaban (1 soal = 1 jawaban per attempt)
        return QuizAnswer::updateOrCreate(
            [
                'quiz_attempt_id'  => $attempt->id,
                'quiz_question_id' => $args['quiz_question_id'],
            ],
            [
                'quiz_option_id' => $args['quiz_option_id'],
            ]
        );
    }
}

<?php

namespace App\GraphQL\Resolvers\Student;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Support\Facades\Auth;

class QuizAttemptResolver
{
    /**
     * Show quiz attempt with answers
     */
    public function show($_, array $args)
    {
        $attempt = QuizAttempt::with(['quiz.questions', 'answers', 'user'])
            ->findOrFail($args['id']);

        if ($attempt->user_id !== Auth::id()) {
            throw new \Exception('Attempt ini bukan milik Anda.');
        }

        return $attempt;
    }

    /**
     * Start quiz attempt
     */
    public function start($_, array $args)
    {
        $quiz = Quiz::with('course')->findOrFail($args['quiz_id']);

        // Check enrollment
        $isEnrolled = $quiz->course->students()
            ->where('users.id', Auth::id())
            ->exists();

        if (!$isEnrolled) {
            throw new \Exception('Anda belum terdaftar di kursus ini.');
        }

        if (!$quiz->is_published) {
            throw new \Exception('Quiz ini belum dibuka.');
        }

        if ($quiz->questions->count() == 0) {
            throw new \Exception('Quiz ini belum memiliki soal.');
        }

        // Check attempt count
        $attemptCount = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', Auth::id())
            ->count();

        if ($attemptCount >= $quiz->max_attempt) {
            throw new \Exception("Kesempatan mengerjakan quiz sudah habis ({$quiz->max_attempt}x)");
        }

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => Auth::id(),
        ]);

        return $attempt->load(['quiz.questions', 'answers']);
    }

    /**
     * Answer a quiz question
     */
    public function answer($_, array $args)
    {
        $attempt = QuizAttempt::findOrFail($args['attempt_id']);

        if ($attempt->user_id !== Auth::id()) {
            throw new \Exception('Attempt ini bukan milik Anda.');
        }

        return QuizAnswer::updateOrCreate(
            [
                'quiz_attempt_id' => $attempt->id,
                'quiz_question_id' => $args['question_id'],
            ],
            [
                'selected_answer' => $args['selected_answer']
            ]
        );
    }

    /**
     * Finish quiz and calculate score
     */
    public function finish($_, array $args)
    {
        $attempt = QuizAttempt::with(['answers.question', 'quiz.questions'])
            ->findOrFail($args['attempt_id']);

        if ($attempt->user_id !== Auth::id()) {
            throw new \Exception('Attempt ini bukan milik Anda.');
        }

        // Calculate score
        $score = 0;
        foreach ($attempt->answers as $answer) {
            if ($answer->question && $answer->selected_answer === $answer->question->correct_answer) {
                $score++;
            }
        }

        $attempt->update(['score' => $score]);

        return $attempt->fresh(['quiz.questions', 'answers']);
    }
}

<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;

class QuizAttemptController extends Controller
{
    public function start(Quiz $quiz)
    {
        $attemptCount = QuizAttempt::where('quiz_id', $quiz->id)
            ->where('user_id', auth()->id())
            ->count();

        if ($attemptCount >= $quiz->max_attempt) {
            return back()->with('error', 'Kesempatan mengerjakan quiz sudah habis');
        }

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('student.quiz.start', [
            'quiz' => $quiz->id,
            'attempt' => $attempt->id,
            'q' => 0,
        ]);
    }

    public function answer(Request $request, Quiz $quiz)
    {
        $attempt = QuizAttempt::findOrFail($request->attempt);
        $questions = $quiz->questions;
        $index = (int) $request->q;

        if ($request->option) {
            QuizAnswer::updateOrCreate(
                [
                    'quiz_attempt_id' => $attempt->id,
                    'quiz_question_id' => $questions[$index]->id,
                ],
                ['quiz_option_id' => $request->option]
            );
        }

        if ($request->action === 'next') $index++;
        if ($request->action === 'prev') $index--;

        if ($index >= count($questions)) {
            $score = 0;
            foreach ($attempt->answers as $ans) {
                if ($ans->option && $ans->option->is_correct) {
                    $score++;
                }
            }

            $attempt->update(['score' => $score]);

            return view('quizzes.result', compact('attempt'));
        }

        return view('quizzes.attempt', compact(
            'quiz', 'questions', 'index', 'attempt'
        ));
    }
}

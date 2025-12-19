<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use Illuminate\Http\Request;

class QuizAttemptController extends Controller
{
    /**
     * Start a quiz - redirects to attempt page
     * GraphQL handles the actual attempt creation
     */
    public function start(Request $request, Quiz $quiz)
    {
        // Just return the view - GraphQL will handle creating or loading the attempt
        return view('student.quizzes.start', ['quizId' => $quiz->id]);
    }

    /**
     * Show quiz attempt page
     * GraphQL handles loading the attempt and questions
     */
    public function attempt(Request $request, $quizId, $attemptId)
    {
        return view('student.quizzes.attempt', [
            'quizId' => $quizId,
            'attemptId' => $attemptId
        ]);
    }

    /**
     * Show quiz result
     * GraphQL handles loading the result data
     */
    public function result(Request $request, $quizId, $attemptId)
    {
        return view('student.quizzes.result', [
            'quizId' => $quizId,
            'attemptId' => $attemptId
        ]);
    }
}

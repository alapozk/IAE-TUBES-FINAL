<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class StudentQuizAttemptController extends Controller
{
    public function start(Quiz $quiz)
    {
        $this->ensureEnrolled($quiz);

        $questions = $quiz->questions;
        return view('student.quiz.start', compact('quiz', 'questions'));
    }

    public function answer(Request $request, Quiz $quiz)
    {
        $this->ensureEnrolled($quiz);

        return redirect()
            ->route('student.courses.show', $quiz->course_id)
            ->with('success', 'Quiz berhasil dikumpulkan.');
    }

    private function ensureEnrolled(Quiz $quiz)
    {
        $course = $quiz->course;

        abort_if(
            !$course->students()->where('user_id', auth()->id())->exists(),
            403,
            'Anda belum terdaftar pada kursus ini.'
        );
    }
}

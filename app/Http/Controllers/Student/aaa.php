<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use Illuminate\Http\Request;

class StudentQuizAttemptController extends Controller
{
    /**
     * Mulai quiz
     */
    public function start(Quiz $quiz)
    {
        $this->ensureEnrolled($quiz);

        $questions = $quiz->questions;

        abort_if($questions->isEmpty(), 404, 'Quiz belum memiliki soal');

        return view('student.quiz.start', compact('quiz', 'questions'));
    }

    /**
     * Submit jawaban quiz
     */
    public function answer(Request $request, Quiz $quiz)
    {
        $this->ensureEnrolled($quiz);

        $request->validate([
            'answers' => 'required|array',
        ]);

        $questions = $quiz->questions;
        $score = 0;

        foreach ($questions as $question) {
            $studentAnswer = $request->answers[$question->id] ?? null;

            if ($studentAnswer === $question->correct_answer) {
                $score++;
            }
        }

        $total = $questions->count();
        $percentage = round(($score / $total) * 100);

        // 👉 sementara simpan di session (AMAN UNTUK TUGAS)
        session()->flash('quiz_result', [
            'score' => $score,
            'total' => $total,
            'percentage' => $percentage,
        ]);

        return redirect()
            ->route('student.courses.show', $quiz->course_id)
            ->with('success', "Quiz selesai. Skor: $percentage%");
    }

    /**
     * Validasi enrolment
     */
    private function ensureEnrolled(Quiz $quiz)
    {
        abort_if(
            !$quiz->course->students()
                ->where('user_id', auth()->id())
                ->exists(),
            403
        );
    }
}

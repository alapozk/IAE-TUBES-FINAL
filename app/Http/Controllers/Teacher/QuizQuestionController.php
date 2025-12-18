<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quiz;

class QuizQuestionController extends Controller
{
    /**
     * Tampilkan form tambah soal (multi-soal)
     */
    public function create(Quiz $quiz)
    {
       return view('teacher.quizzes.questions.create', compact('quiz'));

    }

    /**
     * Simpan banyak soal sekaligus
     */
    public function store(Request $request, Quiz $quiz)
    {
        $request->validate([
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.option_a' => 'required|string',
            'questions.*.option_b' => 'required|string',
            'questions.*.option_c' => 'required|string',
            'questions.*.option_d' => 'required|string',
            'questions.*.correct_answer' => 'required|in:a,b,c,d',
        ]);

        foreach ($request->questions as $q) {
            $quiz->questions()->create([
                'question'       => $q['question'],
                'option_a'       => $q['option_a'],
                'option_b'       => $q['option_b'],
                'option_c'       => $q['option_c'],
                'option_d'       => $q['option_d'],
                'correct_answer' => $q['correct_answer'],
            ]);
        }

        return redirect()
            ->route('teacher.courses.show', $quiz->course_id)
            ->with('success', 'Soal berhasil ditambahkan');
    }
}

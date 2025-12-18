<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Http\Request;


class QuizController extends Controller
{
    // ===============================
    // FORM BUAT QUIZ
    // ===============================
    public function create(Course $course)
    {
        return view('teacher.quizzes.create', compact('course'));
    }

    // ===============================
    // SIMPAN QUIZ BARU
    // ===============================
    public function store(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required',
            'max_attempt' => 'required|in:1,2',
            'duration' => 'required|integer|min:1',
            'deadline' => 'nullable|date',
            'show_review' => 'required|boolean',
        ]);

        $course->quizzes()->create([
            'title'        => $request->title,
            'max_attempt'  => $request->max_attempt,
            'duration'     => $request->duration,
            'deadline'     => $request->deadline,
            'show_review'  => $request->show_review,
            'is_published' => false, // default disembunyikan
            'created_by'   => auth()->id(),
        ]);

        return redirect()
            ->route('teacher.courses.show', $course->id)
            ->with('success', 'Quiz berhasil dibuat');
    }

    // ===============================
    // FORM KELOLA / EDIT QUIZ
    // ===============================
    public function edit(Course $course, Quiz $quiz)
    {
        return view('teacher.quizzes.edit', compact('course', 'quiz'));
    }

    // ===============================
    // UPDATE QUIZ
    // ===============================
    public function update(Request $request, Course $course, Quiz $quiz)
    {
        $request->validate([
            'title'        => 'required',
            'max_attempt'  => 'required|in:1,2',
            'duration'     => 'required|integer|min:1',
            'deadline'     => 'nullable|date',
            'show_review'  => 'required|boolean',
            'is_published' => 'required|boolean',
        ]);

        $quiz->update([
            'title'        => $request->title,
            'max_attempt'  => $request->max_attempt,
            'duration'     => $request->duration,
            'deadline'     => $request->deadline,
            'show_review'  => $request->show_review,
            'is_published' => $request->is_published,
        ]);

        return redirect()
            ->route('teacher.courses.show', $course->id)
            ->with('success', 'Quiz berhasil diperbarui');
    }

    // ===============================
    // TOGGLE BUKA / TUTUP KE SISWA
    // ===============================
    public function togglePublish(Course $course, Quiz $quiz)
    {
        $quiz->update([
            'is_published' => ! $quiz->is_published
        ]);

        return back()->with('success', 'Status quiz diperbarui');
    }
    public function destroy(Course $course, Quiz $quiz)
    {
        // Optional: hapus relasi soal dulu (kalau ada)
        if (method_exists($quiz, 'questions')) {
            $quiz->questions()->delete();
        }

        $quiz->delete();

        return redirect()
            ->route('teacher.courses.show', $course->id)
            ->with('success', 'Quiz berhasil dihapus');
    }
}

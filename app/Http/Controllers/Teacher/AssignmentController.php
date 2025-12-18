<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\{Course, Assignment};
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    private function authorizeOwner(Course $course, $uid) {
        if ($course->teacher_id !== $uid) abort(403);
    }

    public function create(Course $course, Request $r) {
        $this->authorizeOwner($course, $r->user()->id);
        return view('teacher.assignments-create', compact('course'));
    }

    public function store(Course $course, Request $r)
    {
        // pakai fungsi yang sudah kamu buat untuk memastikan owner (guru yg login = pemilik course)
        $this->authorizeOwner($course, $r->user()->id);

        $data = $r->validate([
            'title'           => 'required|string|max:255',
            'instructions'    => 'nullable|string',
            'due_at'          => 'nullable|date',
            'submission_mode' => 'required|in:text,file,both',
            'max_points'      => 'nullable|integer|min:1|max:10000',
            // 'status'          => 'required|in:draft,published,closed',
        ]);

        // simpan via relasi (otomatis set course_id)
        $course->assignments()->create($data);

        return redirect()
            ->route('teacher.courses.show', $course)
            ->with('ok', 'Tugas berhasil dibuat.');
    }

    public function show(Course $course, Assignment $assignment, Request $r) {
        $this->authorizeOwner($course, $r->user()->id);
        if ($assignment->course_id !== $course->id) abort(404);
        return view('teacher.assignments-show', compact('course','assignment'));
    }

    public function edit(Course $course, Assignment $assignment, Request $r) {
        $this->authorizeOwner($course, $r->user()->id);
        if ($assignment->course_id !== $course->id) abort(404);
        return view('teacher.assignments-edit', compact('course','assignment'));
    }

    public function update(Course $course, Assignment $assignment, Request $r)
    {
        $this->authorizeOwner($course, $r->user()->id);
        if ($assignment->course_id !== $course->id) abort(404);

        $data = $r->validate([
            'title'           => 'required|string|max:255',
            'instructions'    => 'nullable|string',
            'due_at'          => 'nullable|date',
            'submission_mode' => 'required|in:text,file,both',
            'max_points'      => 'nullable|integer|min:1|max:10000',
            // 'status'          => 'required|in:draft,published,closed',
        ]);

        $assignment->update($data);

        return redirect()
            ->route('teacher.courses.show', $course)
            ->with('ok', 'Tugas diperbarui.');
    }
    public function destroy(Course $course, Assignment $assignment, Request $r)
    {
        $this->authorizeOwner($course, $r->user()->id);
        if ($assignment->course_id !== $course->id) abort(404);

        $assignment->delete();

        return back()->with('ok', 'Tugas dihapus.');
    }
    public function submissions()
    {
    return $this->hasMany(Submission::class);
    }
}

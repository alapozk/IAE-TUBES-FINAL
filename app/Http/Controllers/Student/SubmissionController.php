<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Submission;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SubmissionController extends Controller
{
    // Form upload tugas
    public function create(Course $course, Assignment $assignment)
    {
        // Pastikan tugas valid untuk kursus ini
        if ($assignment->course_id !== $course->id) {
            abort(404);
        }

        return view('student.submissions-create', compact('course', 'assignment'));
    }

    // Proses simpan upload tugas
    public function store(Request $request, Course $course, Assignment $assignment)
    {
        if ($assignment->course_id !== $course->id) {
            abort(404);
        }

        $student = $request->user();
        if ($student->role !== 'student') {
            abort(403);
        }

        $data = $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,ppt,pptx,zip,rar,mp4,mkv,avi,wmv|max:5120',
        ]);

        $file = $data['file'];
        $path = $file->store('submissions', 'public');

        Submission::create([
            'course_id'     => $course->id,
            'assignment_id' => $assignment->id,
            'student_id'    => $student->id,
            'title'         => $file->getClientOriginalName(),
            'file_path'     => $path,
            'mime'          => $file->getClientMimeType(),
            'size'          => $file->getSize(),
            'extension'     => $file->getClientOriginalExtension(),
        ]);

        return redirect()
            ->route('courses.catalog', $course)
            ->with('ok', 'Tugas berhasil diupload ✅');
    }
}

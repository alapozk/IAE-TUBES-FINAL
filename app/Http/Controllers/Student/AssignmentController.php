<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Http\Request;

class AssignmentController extends Controller
{
    /**
     * Daftar semua assignment dari course yang di-enroll student
     */
    public function index()
    {
        $student = auth()->user();

        // Ambil semua course_id yang diikuti student
        $courseIds = $student->enrollments()
            ->pluck('course_id');

        // Ambil semua assignment dari course tersebut
        $assignments = Assignment::with(['course', 'submissions'])
            ->whereIn('course_id', $courseIds)
            ->orderBy('due_at', 'asc')
            ->get()
            ->map(function ($assignment) {
                $assignment->status = $assignment->submissions
                    ->where('student_id', auth()->id())
                    ->isNotEmpty()
                        ? 'Dikerjakan'
                        : 'Belum Dikerjakan';

                return $assignment;
            });

        return view('student.assignments.index', compact('assignments'));
    }

    /**
     * Detail assignment
     */
    public function show(Course $course, Assignment $assignment)
    {
        // Pastikan assignment milik course tsb
        abort_if($assignment->course_id !== $course->id, 404);

        // 🔒 Validasi enrolment
        $this->ensureEnrolled($course);

        return view('student.assignments.show', compact('assignment', 'course'));
    }

    /**
     * Validasi enrolment student
     */
    private function ensureEnrolled(Course $course)
    {
        $isEnrolled = $course->students()
            ->where('user_id', auth()->id())
            ->exists();

        abort_if(!$isEnrolled, 403, 'Anda belum terdaftar pada kursus ini.');
    }
}

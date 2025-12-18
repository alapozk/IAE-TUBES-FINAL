<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Detail kursus student + progress assignment
     */
    public function show(Course $course)
    {
        // 🔒 Validasi enrolment
        $this->ensureEnrolled($course);

        // Total assignment di course
        $totalAssignments = $course->assignments()->count();

        // Assignment yang sudah dikumpulkan student
        $submittedAssignments = $course->assignments()
            ->whereHas('submissions', function ($q) {
                $q->where('student_id', auth()->id());
            })
            ->count();

        // Progress (%)
        $progress = $totalAssignments > 0
            ? round(($submittedAssignments / $totalAssignments) * 100)
            : 0;

        return view('student.courses.show', compact(
            'course',
            'totalAssignments',
            'submittedAssignments',
            'progress'
        ));
    }

    /**
     * Helper validasi enrolment
     */
    private function ensureEnrolled(Course $course)
    {
        $isEnrolled = $course->students()
            ->where('user_id', auth()->id())
            ->exists();

        abort_if(!$isEnrolled, 403, 'Anda belum terdaftar pada kursus ini.');
    }
}

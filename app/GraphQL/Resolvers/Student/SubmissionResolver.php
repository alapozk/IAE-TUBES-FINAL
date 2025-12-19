<?php

namespace App\GraphQL\Resolvers\Student;

use App\Models\Course;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubmissionResolver
{
    /**
     * Submit assignment with file upload
     */
    public function submit($_, array $args)
    {
        $course = Course::findOrFail($args['course_id']);
        $assignment = Assignment::findOrFail($args['assignment_id']);

        // Check enrollment using direct query to siswa database (cross-database)
        $isEnrolled = DB::connection('siswa')
            ->table('enrollments')
            ->where('course_id', $course->id)
            ->where('student_id', Auth::id())
            ->exists();

        if (!$isEnrolled) {
            throw new \Exception('Anda belum terdaftar di kursus ini.');
        }

        if ($assignment->course_id !== $course->id) {
            throw new \Exception('Assignment tidak ditemukan di course ini.');
        }

        $file = $args['file'];
        $path = $file->store('submissions', 'public');

        return Submission::create([
            'course_id' => $course->id,
            'assignment_id' => $assignment->id,
            'student_id' => Auth::id(),
            'title' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'extension' => $file->getClientOriginalExtension(),
        ]);
    }
}

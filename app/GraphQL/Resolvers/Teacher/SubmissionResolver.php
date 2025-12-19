<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubmissionResolver
{
    /**
     * List all submissions for an assignment (for teacher)
     */
    public function list($_, array $args)
    {
        $assignmentId = $args['assignment_id'];

        // Get submissions from siswa database
        $submissions = DB::connection('siswa')
            ->table('submissions')
            ->where('assignment_id', $assignmentId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get student names from main database
        $studentIds = $submissions->pluck('student_id')->unique()->filter();
        $students = User::whereIn('id', $studentIds)->pluck('name', 'id');

        return $submissions->map(function ($sub) use ($students) {
            return [
                'id' => $sub->id,
                'student_id' => $sub->student_id,
                'student_name' => $students[$sub->student_id] ?? 'Siswa #' . $sub->student_id,
                'title' => $sub->title,
                'file_path' => $sub->file_path,
                'grade' => $sub->grade,
                'feedback' => $sub->feedback,
                'created_at' => $sub->created_at,
            ];
        });
    }
}

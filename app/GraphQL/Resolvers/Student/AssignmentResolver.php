<?php

namespace App\GraphQL\Resolvers\Student;

use App\Models\Assignment;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;

class AssignmentResolver
{
    /**
     * Get all assignments from enrolled courses
     */
    public function myAssignments($_, array $args)
    {
        $courseIds = Enrollment::where('student_id', Auth::id())
            ->pluck('course_id');

        return Assignment::with(['course', 'submissions'])
            ->whereIn('course_id', $courseIds)
            ->orderBy('due_at', 'asc')
            ->get()
            ->map(function ($assignment) {
                $assignment->status = $assignment->submissions
                    ->where('student_id', Auth::id())
                    ->isNotEmpty() ? 'Dikerjakan' : 'Belum Dikerjakan';
                return $assignment;
            });
    }

    /**
     * List assignments by course
     */
    public function listByCourse($_, array $args)
    {
        $courseIds = Enrollment::where('student_id', Auth::id())
            ->pluck('course_id');

        if (!in_array($args['course_id'], $courseIds->toArray())) {
            throw new \Exception('Anda belum terdaftar di kursus ini.');
        }

        return Assignment::with('submissions')
            ->where('course_id', $args['course_id'])
            ->orderBy('due_at', 'asc')
            ->get();
    }

    /**
     * Get single assignment by ID (for enrolled students)
     */
    public function show($_, array $args)
    {
        $assignment = Assignment::with(['course', 'submissions'])->findOrFail($args['id']);
        
        // Check if student is enrolled in the course
        $isEnrolled = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $assignment->course_id)
            ->exists();
            
        if (!$isEnrolled) {
            throw new \Exception('Anda belum terdaftar di kursus ini.');
        }
        
        return $assignment;
    }
}

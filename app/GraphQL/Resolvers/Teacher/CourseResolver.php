<?php

namespace App\GraphQL\Resolvers\Teacher;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseResolver
{
    /**
     * List teacher's courses
     */
    public function myList($_, array $args)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            throw new \Exception('Anda harus login sebagai guru.');
        }

        return Course::where('teacher_id', $userId)
            ->with(['materials', 'assignments', 'quizzes'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Show single course (with ownership check)
     */
    public function show($_, array $args)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            throw new \Exception('Anda harus login sebagai guru.');
        }

        $course = Course::with(['materials', 'assignments', 'quizzes.questions'])
            ->find($args['id']);
        
        if (!$course) {
            throw new \Exception('Kursus tidak ditemukan.');
        }
        
        if ($course->teacher_id !== $userId) {
            throw new \Exception('Anda bukan pemilik kursus ini. (Teacher: ' . $course->teacher_id . ', You: ' . $userId . ')');
        }

        return $course;
    }

    /**
     * Create new course
     */
    public function store($_, array $args)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            throw new \Exception('Anda harus login sebagai guru.');
        }

        return Course::create([
            'title' => $args['title'],
            'code' => $args['code'],
            'status' => $args['status'] ?? 'draft',
            'teacher_id' => $userId,
        ]);
    }

    /**
     * Update course
     */
    public function update($_, array $args)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            throw new \Exception('Anda harus login sebagai guru.');
        }

        $course = Course::findOrFail($args['id']);
        
        if ($course->teacher_id !== $userId) {
            throw new \Exception('Anda bukan pemilik kursus ini.');
        }

        $course->update([
            'title' => $args['title'] ?? $course->title,
            'code' => $args['code'] ?? $course->code,
            'status' => $args['status'] ?? $course->status,
        ]);

        return $course->fresh();
    }

    /**
     * Delete course
     */
    public function delete($_, array $args)
    {
        $userId = Auth::id();
        
        if (!$userId) {
            throw new \Exception('Anda harus login sebagai guru.');
        }

        $course = Course::findOrFail($args['id']);
        
        if ($course->teacher_id !== $userId) {
            throw new \Exception('Anda bukan pemilik kursus ini.');
        }

        $course->delete();
        return true;
    }
}

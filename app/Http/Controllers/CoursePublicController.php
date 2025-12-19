<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;

class CoursePublicController extends Controller 
{
    public function index() 
    {
        // Not using anymore - using GraphQL via catalog view
        return view('course.catalog');
    }
    
    public function show($courseId) 
    {
        // Manual find instead of route model binding for multi-database
        $course = Course::find($courseId);
        
        if (!$course) {
            abort(404, 'Kursus tidak ditemukan.');
        }
        
        // Check status
        if (!in_array($course->status, ['published', 'active'])) {
            abort(404, 'Kursus tidak tersedia.');
        }
        
        // Get teacher from main database
        $course->teacher = User::find($course->teacher_id);
        
        // Load relations from guru database
        $course->load(['materials', 'assignments', 'quizzes']);
        
        return view('course.show', compact('course'));
    }
}

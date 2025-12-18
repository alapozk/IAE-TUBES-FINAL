<?php

namespace App\Http\Controllers;

use App\Models\Course;

class CoursePublicController extends Controller {
    public function index() {
        $courses = Course::where('status','published')->paginate(10);
        return view('course.catalog', compact('courses'));
    }
    public function show(Course $course) {
        abort_unless($course->status==='published', 404);
        return view('teacher.course-show', compact('course'));
    }
}

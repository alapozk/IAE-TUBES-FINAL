<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Detail kursus student - data loaded via GraphQL
     */
    public function show(Course $course)
    {
        // Just return the view - GraphQL handles data fetching and enrollment check
        return view('student.courses.show');
    }
}

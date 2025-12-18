<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Material;

class MaterialController extends Controller
{
    /**
     * Tampilkan materi untuk student
     */
    public function show(Course $course, Material $material)
    {
        // Pastikan materi milik course tersebut
        if ($material->course_id !== $course->id) {
            abort(404);
        }

        return view('student.materials.show', compact('course', 'material'));
    }
}

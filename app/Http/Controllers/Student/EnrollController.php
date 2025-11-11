<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollController extends Controller {
    public function myCourses(Request $r) {
        $enrolled = Enrollment::with('course')
                    ->where('student_id',$r->user()->id)->get();
        return view('student.my-courses', compact('enrolled'));
    }
    public function enroll(Request $r, Course $course) {
        Enrollment::firstOrCreate([
            'course_id'=>$course->id,
            'student_id'=>$r->user()->id
        ], ['status'=>'enrolled']);
        return back()->with('ok','Enrolled!');
    }
}

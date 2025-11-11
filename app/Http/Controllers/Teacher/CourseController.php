<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller {
    public function index(Request $r) {
        $courses = Course::where('teacher_id',$r->user()->id)->get();
        return view('teacher.courses', compact('courses'));
    }
    public function create()  { return view('teacher.course-create'); }
    public function store(Request $r) {
        $data = $r->validate([
            'title'=>'required','code'=>'required|unique:courses,code',
            'status'=>'in:draft,published'
        ]);
        $data['teacher_id'] = $r->user()->id;
        Course::create($data);
        return redirect()->route('teacher.courses')->with('ok','Course dibuat');
    }
    public function edit(Course $course) {
        $this->authorizeOwner($course, request()->user()->id);
        return view('teacher.course-edit', compact('course'));
    }
    public function update(Request $r, Course $course) {
        $this->authorizeOwner($course, $r->user()->id);
        $course->update($r->validate(['title'=>'required','status'=>'in:draft,published']));
        return back()->with('ok','Updated');
    }
    public function destroy(Request $r, Course $course) {
        $this->authorizeOwner($course, $r->user()->id);
        $course->delete();
        return back()->with('ok','Deleted');
    }
    private function authorizeOwner(Course $c, $uid) {
        if ($c->teacher_id !== $uid) abort(403);
    }
}


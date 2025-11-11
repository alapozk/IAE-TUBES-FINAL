<?php

// app/Http/Controllers/Teacher/AssignmentController.php
namespace App\Http\Controllers\Teacher;
use App\Http\Controllers\Controller;
use App\Models\Assignment; use App\Models\Course;
use Illuminate\Http\Request;

class AssignmentController extends Controller {
  public function index(Request $r) {
    $courses = Course::where('teacher_id',$r->user()->id)->pluck('title','id');
    $items = Assignment::whereIn('course_id', $courses->keys())->latest()->get();
    return view('teacher.assignments.index', compact('items','courses'));
  }
  public function create(Request $r) {
    $courses = Course::where('teacher_id',$r->user()->id)->get();
    return view('teacher.assignments.create', compact('courses'));
  }
  public function store(Request $r) {
    $data = $r->validate([
      'course_id'=>'required|integer','title'=>'required',
      'instructions'=>'nullable','due_at'=>'nullable|date',
      'max_score'=>'nullable|integer|min:1'
    ]);
    // pastikan kursus milik guru ini
    abort_unless(Course::where('id',$data['course_id'])->where('teacher_id',$r->user()->id)->exists(),403);
    Assignment::create($data);
    return redirect()->route('assignments.index')->with('ok','Tugas dibuat');
  }
  public function edit(Assignment $assignment, Request $r) {
    abort_unless($assignment->course->teacher_id === $r->user()->id,403);
    $courses = Course::where('teacher_id',$r->user()->id)->get();
    return view('teacher.assignments.edit', compact('assignment','courses'));
  }
  public function update(Request $r, Assignment $assignment) {
    abort_unless($assignment->course->teacher_id === $r->user()->id,403);
    $assignment->update($r->validate([
      'course_id'=>'required|integer','title'=>'required',
      'instructions'=>'nullable','due_at'=>'nullable|date','max_score'=>'nullable|integer|min:1'
    ]));
    return back()->with('ok','Disimpan');
  }
  public function destroy(Assignment $assignment, Request $r) {
    abort_unless($assignment->course->teacher_id === $r->user()->id,403);
    $assignment->delete(); return back()->with('ok','Dihapus');
  }
}

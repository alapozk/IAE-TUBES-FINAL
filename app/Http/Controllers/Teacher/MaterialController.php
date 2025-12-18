<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\{Course, Material};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MaterialController extends Controller
{
    // pake fungsi authorizeOwner yang sama dari CourseController
    private function authorizeOwner(Course $course, $uid){
        if ($course->teacher_id !== $uid) abort(403);
    }

    public function create(Request $r, Course $course){
        $this->authorizeOwner($course, $r->user()->id);
        return view('teacher.materials-create', compact('course'));
    }

    public function store(Request $r, Course $course){
        $this->authorizeOwner($course, $r->user()->id);

        $data = $r->validate([
            'title' => ['required','string','max:255'],
            'file'  => [
                'required','file','max:51200', // 50MB
                // pdf, powerpoint, video umum
                'mimetypes:application/pdf,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint.presentation.macroEnabled.12,video/mp4,video/webm,video/quicktime,video/x-matroska'
            ],
        ]);

        $file = $r->file('file');
        $ext  = strtolower($file->getClientOriginalExtension());
        $path = $file->store("materials/{$course->id}", 'public');

        $material = Material::create([
            'course_id' => $course->id,
            'title'     => $data['title'],
            'file_path' => $path,
            'mime'      => $file->getMimeType(),
            'size'      => $file->getSize(),
            'extension' => $ext,
        ]);

        return redirect()->route('teacher.courses.show', $course)->with('ok','Materi berhasil diunggah.');
    }

    public function show(Request $r, Course $course, Material $material){
        $this->authorizeOwner($course, $r->user()->id);
        if ($material->course_id !== $course->id) abort(404);
        return view('teacher.materials-show', compact('course','material'));
    }

    public function edit(Request $r, Course $course, Material $material){
        $this->authorizeOwner($course, $r->user()->id);
        if ($material->course_id !== $course->id) abort(404);
        return view('teacher.materials-edit', compact('course','material'));
    }

    public function update(Request $r, Course $course, Material $material){
        $this->authorizeOwner($course, $r->user()->id);
        if ($material->course_id !== $course->id) abort(404);

        $data = $r->validate([
            'title' => ['required','string','max:255'],
            'file'  => [
                'nullable','file','max:51200',
                'mimetypes:application/pdf,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint.presentation.macroEnabled.12,video/mp4,video/webm,video/quicktime,video/x-matroska'
            ],
        ]);

        $update = ['title' => $data['title']];

        if ($r->hasFile('file')) {
            // hapus file lama
            if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            $file = $r->file('file');
            $ext  = strtolower($file->getClientOriginalExtension());
            $path = $file->store("materials/{$course->id}", 'public');

            $update += [
                'file_path' => $path,
                'mime'      => $file->getMimeType(),
                'size'      => $file->getSize(),
                'extension' => $ext,
            ];
        }

        $material->update($update);
        return redirect()->route('teacher.materials.show', [$course->id, $material->id])->with('ok','Materi diperbarui.');
    }

    public function destroy(Request $r, Course $course, Material $material){
        $this->authorizeOwner($course, $r->user()->id);
        if ($material->course_id !== $course->id) abort(404);

        if ($material->file_path && Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();

        return redirect()->route('teacher.courses.show', $course)->with('ok','Materi dihapus.');
    }
}

@extends('layouts.app')
@section('content')
<style>
  .wrap{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .box{max-width:700px;margin:0 auto;background:#fff;border-radius:16px;padding:24px;box-shadow:0 12px 30px rgba(0,0,0,.1)}
  .label{font-weight:800;margin-bottom:6px;display:block}
  .input,.file{width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:10px}
  .row{display:flex;gap:12px;margin-top:16px}
  .btn{padding:12px 18px;border-radius:10px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:800;text-decoration:none}
  .primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none}
</style>

<div class="wrap">
  <div class="box">
    <h2 style="font-weight:900;margin-bottom:12px">➕ Masukkan File — {{ $course->title }}</h2>

    <form method="POST" action="{{ route('student.submissions.store', ['course' => $course->id, 'assignment' => $assignment->id]) }}"enctype="multipart/form-data">
      @csrf

      <label class="label" style="margin-top:12px">File (PDF / PPT / Video)</label>
      <input class="file" type="file" name="file" accept=".pdf,.ppt,.pptx,video/*" required>

      <div class="row">
        <button class="btn primary" type="submit">💾 Simpan</button>
        <a class="btn" href="{{ route('courses.catalog', $course) }}">← Batal</a>
      </div>
    </form>
  </div>
</div>
@endsection

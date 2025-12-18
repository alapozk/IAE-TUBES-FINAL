@extends('layouts.app')

@section('content')
<style>
  .wrap{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .container{max-width:860px;margin:0 auto}
  .card{background:#fff;border-radius:16px;box-shadow:0 12px 30px rgba(0,0,0,.10);padding:20px}
  .row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .row-1{display:grid;grid-template-columns:1fr;gap:14px}
  label{font-weight:800;margin-bottom:6px;display:block}
  input[type="text"], input[type="datetime-local"], textarea, select {
    width:100%; padding:12px; border:1px solid #e5e7eb; border-radius:10px; background:#f9fafb
  }
  textarea{min-height:160px; resize:vertical}
  .actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:14px}
  .btn{padding:10px 14px;border-radius:10px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:800;text-decoration:none}
  .primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none}
</style>

<div class="wrap">
  <div class="container">
    <a href="{{ route('teacher.courses.show',$course) }}" class="btn">← Kembali</a>

    <div class="card" style="margin-top:12px">
      <h2 style="font-weight:900;margin:0 0 10px">Buat Tugas — {{ $course->title }}</h2>

      @if ($errors->any())
        <div style="background:#fee2e2;border:1px solid #fecaca;padding:10px;border-radius:10px;margin-bottom:10px">
          <b>Periksa form:</b>
          <ul style="margin:6px 0 0 18px">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('teacher.assignments.store',$course) }}">
        @csrf

        <div class="row-1">
          <div>
            <label>Judul</label>
            <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: Esai Bab 1">
          </div>

          <div>
            <label>Instruksi / Detail Tugas</label>
            <textarea name="instructions" placeholder="Tuliskan kriteria penilaian, langkah, sumber, dsb.">{{ old('instructions') }}</textarea>
          </div>
        </div>

        <div class="row" style="margin-top:6px">
          <div>
            <label>Deadline (opsional)</label>
            <input type="datetime-local" name="due_at" value="{{ old('due_at') }}">
          </div>

          <div>
            <label>Mode Pengumpulan</label>
            <select name="submission_mode" required>
              <option value="text"  {{ old('submission_mode')==='text'  ? 'selected':'' }}>Teks saja</option>
              <option value="file"  {{ old('submission_mode')==='file'  ? 'selected':'' }}>File saja (PDF/DOC/ZIP)</option>
              <option value="both"  {{ old('submission_mode')==='both'  ? 'selected':'' }}>Teks + File</option>
            </select>
          </div>
        </div>

        <div class="row" style="margin-top:6px">
          <div>
            <label>Skor Maks (opsional)</label>
            <input type="text" name="max_points" inputmode="numeric" value="{{ old('max_points') }}" placeholder="Mis. 100">
          </div>
          <div></div>
        </div>

        <div class="actions">
          <button class="btn primary" type="submit">Simpan</button>
          <a class="btn" href="{{ route('teacher.courses.show',$course) }}">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

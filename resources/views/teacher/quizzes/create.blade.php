@extends('layouts.app')

@section('content')
@php
    $user = auth()->user();
@endphp

<style>
  .page{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .container{max-width:900px;margin:0 auto}
  .back{display:inline-flex;gap:8px;align-items:center;color:#667eea;text-decoration:none;font-weight:700;margin-bottom:14px}

  .card{background:#fff;border-radius:18px;box-shadow:0 15px 40px rgba(0,0,0,.10);padding:26px}
  .card h3{font-weight:900;margin-bottom:18px;display:flex;align-items:center;gap:10px}

  .form-group{margin-bottom:16px}
  label{font-weight:800;margin-bottom:6px;display:block}
  .form-control{
    width:100%;padding:12px 14px;border-radius:12px;
    border:1px solid #e5e7eb;font-weight:600
  }
  .form-control:focus{
    outline:none;border-color:#667eea;
    box-shadow:0 0 0 3px rgba(102,126,234,.25)
  }

  .actions{display:flex;gap:12px;margin-top:20px;flex-wrap:wrap}
  .btn{
    display:inline-flex;gap:8px;align-items:center;
    padding:12px 18px;border-radius:12px;
    border:1px solid #e5e7eb;
    background:#f9fafb;color:#111827;
    text-decoration:none;font-weight:800
  }
  .btn:hover{background:#f3f4f6}
  .btn-primary{
    background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
    color:#fff;border:none;
    box-shadow:0 8px 20px rgba(102,126,234,.35)
  }
  .btn-primary:hover{transform:translateY(-1px)}
  .hint{color:#6b7280;font-size:.85rem;margin-top:4px}
</style>

<div class="page">
  <div class="container">

    {{-- Back --}}
    <a href="{{ route('teacher.courses.show', $course->id) }}"
       class="back">
       ← Kembali ke Kursus
    </a>

    <div class="card">
      <h3>🧠 Buat Quiz Baru</h3>

      <form method="POST"
            action="{{ route('teacher.courses.quizzes.store', $course->id) }}">
        @csrf

        {{-- Judul --}}
        <div class="form-group">
          <label>Judul Quiz</label>
          <input type="text"
                 name="title"
                 class="form-control"
                 placeholder="Contoh: Quiz Pertemuan 1"
                 required>
        </div>

        {{-- Attempt --}}
        <div class="form-group">
          <label>Jumlah Attempt</label>
          <select name="max_attempt" class="form-control">
            <option value="1">1x Attempt (Sekali saja)</option>
            <option value="2">2x Attempt</option>
          </select>
          <div class="hint">
            Berapa kali siswa boleh mengerjakan quiz
          </div>
        </div>

        {{-- Durasi --}}
        <div class="form-group">
          <label>Durasi Pengerjaan (menit)</label>
          <input type="number"
                 name="duration"
                 class="form-control"
                 value="30"
                 min="1">
          <div class="hint">
            Waktu maksimal pengerjaan quiz
          </div>
        </div>

        {{-- Deadline --}}
        <div class="form-group">
          <label>Deadline Quiz</label>
          <input type="datetime-local"
                 name="deadline"
                 class="form-control">
          <div class="hint">
            Quiz tidak bisa dikerjakan setelah waktu ini
          </div>
        </div>

        {{-- Review --}}
        <div class="form-group">
          <label>Akses Review Jawaban</label>
          <select name="show_review" class="form-control">
            <option value="1">Boleh lihat review</option>
            <option value="0">Tidak boleh lihat review</option>
          </select>
          <div class="hint">
            Jika tidak diizinkan, siswa hanya melihat nilai
          </div>
        </div>

        {{-- Publish --}}
        <div class="form-group">
          <label>Status Quiz</label>
          <select name="is_published" class="form-control">
            <option value="1">👁️ Dibuka untuk siswa</option>
            <option value="0">🔒 Disembunyikan</option>
          </select>
        </div>

        {{-- Actions --}}
        <div class="actions">
          <button type="submit" class="btn btn-primary">
            💾 Simpan Quiz
          </button>

          <a href="{{ route('teacher.courses.show', $course->id) }}"
             class="btn">
            ✖ Batal
          </a>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection

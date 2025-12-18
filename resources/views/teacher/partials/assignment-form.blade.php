@php
  // expects: $title, $action, $method ('POST'|'PUT'), $defaults (arr)
@endphp

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
      <h2 style="font-weight:900;margin:0 0 10px">{{ $title }}</h2>

      @if ($errors->any())
        <div style="background:#fee2e2;border:1px solid #fecaca;padding:10px;border-radius:10px;margin-bottom:10px">
          <b>Periksa form:</b>
          <ul style="margin:6px 0 0 18px">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
      @endif

      <form method="POST" action="{{ $action }}">
        @csrf
        @if($method === 'PUT') @method('PUT') @endif

        <div class="row-1">
          <div>
            <label>Judul</label>
            <input type="text" name="title" value="{{ $defaults['title'] ?? '' }}" required>
          </div>

          <div>
            <label>Instruksi / Detail Tugas</label>
            <textarea name="instructions">{{ $defaults['instructions'] ?? '' }}</textarea>
          </div>
        </div>

        <div class="row" style="margin-top:6px">
          <div>
            <label>Deadline (opsional)</label>
            <input type="datetime-local" name="due_at" value="{{ $defaults['due_at'] ?? '' }}">
          </div>

          <div>
            <label>Mode Pengumpulan</label>
            @php $mode = $defaults['submission_mode'] ?? 'text'; @endphp
            <select name="submission_mode" required>
              <option value="text" {{ $mode==='text'?'selected':'' }}>Teks saja</option>
              <option value="file" {{ $mode==='file'?'selected':'' }}>File saja (PDF/DOC/ZIP)</option>
              <option value="both" {{ $mode==='both'?'selected':'' }}>Teks + File</option>
            </select>
          </div>
        </div>

        <div class="row" style="margin-top:6px">
          <div>
            <label>Skor Maks (opsional)</label>
            <input type="text" name="max_points" value="{{ $defaults['max_points'] ?? '' }}">
          </div>

          @isset($defaults['status'])
          <div>
            <label>Status</label>
            <select name="status">
              <option value="active" {{ ($defaults['status']??'')==='active'?'selected':'' }}>Aktif</option>
              <option value="archived" {{ ($defaults['status']??'')==='archived'?'selected':'' }}>Arsip</option>
            </select>
          </div>
          @endisset
        </div>

        <div class="actions">
          <button class="btn primary" type="submit">Simpan</button>
          <a class="btn" href="{{ route('teacher.courses.show',$course) }}">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

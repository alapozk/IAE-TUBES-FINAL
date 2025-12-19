@extends('layouts.app')

@section('content')
<style>
  .wrap{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .container{max-width:1000px;margin:0 auto}
  .row{display:flex;gap:10px;margin-bottom:14px;flex-wrap:wrap}
  .btn{padding:10px 14px;border-radius:10px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:800;text-decoration:none}
  .primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none}
  .card{background:#fff;border-radius:16px;padding:20px;box-shadow:0 12px 30px rgba(0,0,0,.1)}
  .meta{color:#6b7280;font-size:.9rem;margin-bottom:10px}
</style>

<div class="wrap">
  <div class="container">
    <div class="row">
      <a class="btn" href="{{ route('teacher.courses.show', $course) }}">← Kembali</a>
      <a class="btn" href="{{ route('teacher.materials.edit', [$course->id, $material->id]) }}">✏️ Edit</a>
      <form method="POST" action="{{ route('teacher.materials.destroy', [$course->id, $material->id]) }}">
        @csrf @method('DELETE')
        <button class="btn" onclick="return confirm('Hapus materi ini?')">🗑️ Hapus</button>
      </form>
    </div>

    <div class="card">
      <h2 style="font-weight:900;margin:0 0 6px">{{ $material->title }}</h2>
      <div class="meta">
        {{ strtoupper($material->extension) }} • {{ number_format($material->size/1024,0) }} KB
      </div>

      @if($material->description)
        <div style="margin:16px 0;padding:16px;background:#f9fafb;border-radius:12px;">
          <div style="color:#4b5563;line-height:1.6">{!! nl2br(e($material->description)) !!}</div>
        </div>
      @endif

      @php $url = asset('storage/'.$material->file_path); @endphp

      @if (str_starts_with($material->mime,'video'))
        <video controls style="width:100%;max-height:70vh;border-radius:12px">
          <source src="{{ $url }}" type="{{ $material->mime }}">
        </video>
      @elseif (str_contains($material->mime,'pdf'))
        <iframe src="{{ $url }}" style="width:100%;height:80vh;border:none;border-radius:12px"></iframe>
      @elseif (in_array($material->extension, ['ppt','pptx','pps','ppsx']))
        <p>File PowerPoint tidak bisa dipratinjau langsung. Silakan gunakan tombol <b>Unduh</b> di atas.</p>
      @else
        <p>Tidak ada pratinjau untuk tipe ini. Silakan unduh berkas.</p>
      @endif
    </div>
  </div>
</div>
@endsection

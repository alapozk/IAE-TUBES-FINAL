@extends('layouts.app')

@section('content')
<style>
  .page{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .container{max-width:1100px;margin:0 auto}

  .back{display:inline-flex;gap:8px;align-items:center;color:#667eea;text-decoration:none;font-weight:700;margin-bottom:14px}

  .hero{border-radius:20px;overflow:hidden;box-shadow:0 15px 40px rgba(0,0,0,.12);margin-bottom:18px}
  .hero-top{height:160px;background:linear-gradient(135deg,#22c55e 0%,#16a34a 100%);display:flex;align-items:center;justify-content:center;font-size:3rem}
  .hero-bottom{background:#fff;padding:26px 28px;display:flex;align-items:center;gap:14px}
  .title{font-size:2rem;font-weight:900;color:#111827;margin:0}
  .muted{color:#6b7280}

  .btn{display:inline-flex;gap:8px;align-items:center;padding:10px 16px;border-radius:12px;border:1px solid #e5e7eb;background:#f9fafb;color:#111827;text-decoration:none;font-weight:700}
  .btn:hover{background:#f3f4f6}
  .btn-primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none;box-shadow:0 8px 20px rgba(102,126,234,.35)}
  .btn-xs{padding:6px 10px;border-radius:8px;font-size:.8rem}

  .card{background:#fff;border-radius:18px;box-shadow:0 15px 40px rgba(0,0,0,.10);padding:22px;margin-top:14px}

  .quiz-list{display:flex;flex-direction:column;gap:14px}
  .quiz-card{background:#fff;border-radius:14px;padding:16px 18px;box-shadow:0 8px 24px rgba(0,0,0,.08);display:flex;gap:14px}
  .quiz-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;color:#fff;background:linear-gradient(135deg,#22c55e 0%,#16a34a 100%)}
  .quiz-body{flex:1}
  .quiz-title{font-weight:800;color:#111827;margin:0 0 6px}
  .quiz-meta{font-size:.85rem;color:#6b7280;display:flex;gap:14px;flex-wrap:wrap}
</style>

<div class="page">
  <div class="container">

    {{-- Back --}}
    <a href="{{ url()->previous() }}" class="back">← Kembali</a>
      ← Kembali ke Kursus
    </a>

    {{-- Hero --}}
    <div class="hero">
      <div class="hero-top">🧠</div>
      <div class="hero-bottom">
        <h1 class="title">Quiz – {{ $course->title }}</h1>

        <a href="{{ route('teacher.courses.quizzes.create', $course->id) }}"
           class="btn btn-primary"
           style="margin-left:auto">
          ➕ Buat Quiz
        </a>
      </div>
    </div>

    {{-- List --}}
    <div class="card">
      <h3 style="font-weight:900;margin-bottom:12px">📋 Daftar Quiz</h3>

      @if($quizzes->count())
        <div class="quiz-list">
          @foreach($quizzes as $quiz)
            <div class="quiz-card">
              <div class="quiz-icon">📝</div>

              <div class="quiz-body">
                <p class="quiz-title">{{ $quiz->title }}</p>

                <div class="quiz-meta">
                  <span>⏱ {{ $quiz->duration }} menit</span>
                  <span>🔁 {{ $quiz->max_attempt }} attempt</span>

                  <span>
                    📅 Deadline:
                    <b>
                      {{ $quiz->deadline
                          ? \Carbon\Carbon::parse($quiz->deadline)->format('d M Y H:i')
                          : 'Tidak ada' }}
                    </b>
                  </span>

                  <span>
                    Status:
                    <b style="color:{{ $quiz->is_published ? '#16a34a' : '#dc2626' }};font-weight:800">
                      {{ $quiz->is_published ? 'Dibuka untuk siswa' : 'Disembunyikan' }}
                    </b>
                  </span>
                </div>

                <div style="margin-top:10px;display:flex;gap:10px;flex-wrap:wrap">
                  <a href="{{ route('teacher.courses.quizzes.edit', [$course->id, $quiz->id]) }}"
                     class="btn btn-xs">
                    ⚙️ Kelola
                  </a>

                  <form method="POST"
                        action="{{ route('teacher.courses.quizzes.toggle', [$course->id, $quiz->id]) }}">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                            class="btn btn-xs"
                            style="
                              background:{{ $quiz->is_published ? '#fee2e2' : '#dcfce7' }};
                              color:{{ $quiz->is_published ? '#991b1b' : '#166534' }};
                              border:none">
                      {{ $quiz->is_published ? '🔒 Tutup Quiz' : '👁️ Buka Quiz' }}
                    </button>
                  </form>
                </div>

              </div>
            </div>
          @endforeach
        </div>
      @else
        <p class="muted">Belum ada quiz pada kursus ini.</p>
      @endif

    </div>

  </div>
</div>
@endsection

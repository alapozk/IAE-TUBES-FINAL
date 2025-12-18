@extends('layouts.app')

@section('content')
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }

  .courses-wrapper {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 40px 20px;
  }

  .courses-container { max-width: 1200px; margin: 0 auto; }

  /* Header */
  .courses-header { margin-bottom: 40px; }
  .header-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: wrap;
  }

  .header-content h1 {
    font-size: 2.5rem;
    font-weight: 900;
    color: #1a202c;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 15px;
  }

  .header-icon { font-size: 2.5rem; }

  .header-content p { color: #718096; }

  .tasks-btn {
    padding: 10px 18px;
    border-radius: 999px;
    background: #4f46e5;
    color: white;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(79,70,229,.35);
  }

  /* Grid */
  .courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
  }

  .course-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0,0,0,.1);
    display: flex;
    flex-direction: column;
    transition: .3s;
  }

  .course-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 40px rgba(0,0,0,.15);
  }

  .course-image {
    height: 180px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
  }

  .course-content {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  /* ====== BAGIAN BARU (HASIL RAPING SS) ====== */
  .course-meta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
  }

  .course-code {
    font-size: 0.85rem;
    font-weight: 700;
    color: #4a5568;
  }

  .enrolled-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 12px;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 700;
    background: #e6fffa;
    color: #065f46;
  }
  /* ========================================= */

  .course-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 12px;
  }

  .course-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: .8rem;
    font-weight: 700;
    width: fit-content;
  }

  .status-active {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    color: white;
  }

  .course-footer {
    margin-top: auto;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
  }

  .course-btn {
    display: block;
    padding: 10px;
    text-align: center;
    border-radius: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    font-weight: 600;
  }
</style>

<div class="courses-wrapper">
  <div class="courses-container">

    {{-- Header --}}
    <div class="courses-header">
      <div class="header-top">
        <div class="header-content">
          <h1><span class="header-icon">📚</span> Kursus Saya</h1>
          <p>Kelola dan lanjutkan pembelajaran Anda</p>
        </div>
        <a href="{{ route('student.assignments.index') }}" class="tasks-btn">📝 Lihat Tugas</a>
      </div>
    </div>

    {{-- Courses --}}
    @if($enrolled->count())
      <div class="courses-grid">
        @foreach($enrolled as $enrollment)
          <div class="course-card">

            <div class="course-image">📖</div>

            <div class="course-content">

              {{-- === HASIL RAPING DARI SS === --}}
              <div class="course-meta">
                <span class="course-code">
                  #{{ $enrollment->course->id }}
                </span>
                <span class="enrolled-badge">
                  ✓ Enrolled
                </span>
              </div>
              {{-- =========================== --}}

              <h3 class="course-title">
                {{ $enrollment->course->title }}
              </h3>

              <span class="course-status status-{{ $enrollment->status }}">
                ✓ Sedang Belajar
              </span>

              <div class="course-footer">
                <a href="{{ route('courses.show', $enrollment->course) }}"
                   class="course-btn">
                  Lihat Kursus
                </a>
              </div>

            </div>
          </div>
        @endforeach
      </div>
    @endif

  </div>
</div>
@endsection

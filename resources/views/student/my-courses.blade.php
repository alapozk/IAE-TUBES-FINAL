@extends('layouts.app')

@section('content')
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .courses-wrapper {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 40px 20px;
  }

  .courses-container {
    max-width: 1200px;
    margin: 0 auto;
  }

  /* Header */
  .courses-header {
    margin-bottom: 40px;
    animation: slideDown 0.6s ease-out;
  }

  .header-top {
    display: flex;
    align-items: center;
    justify-content: space-between; /* penting: title kiri, tombol kanan */
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

  .header-icon {
    font-size: 2.5rem;
  }

  .header-content p {
    color: #718096;
    font-size: 1rem;
  }

  .header-actions {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .tasks-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 10px 18px;
    border-radius: 999px;
    background: #4f46e5;
    color: #fff;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
    transition: all 0.2s ease;
  }

  .tasks-btn:hover {
    background: #4338ca;
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(79, 70, 229, 0.45);
  }

  /* Courses Grid */
  .courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
    animation: fadeIn 0.7s ease-out;
  }

  .course-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
  }

  .course-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
  }

  .course-image {
    width: 100%;
    height: 180px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    position: relative;
    overflow: hidden;
  }

  .course-image::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    animation: shimmer 2s infinite;
  }

  .course-content {
    padding: 25px;
    flex: 1;
    display: flex;
    flex-direction: column;
  }

  .course-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 12px;
    line-height: 1.4;
  }

  .course-status {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 700;
    width: fit-content;
    margin-bottom: 15px;
  }

  .status-active {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    color: white;
  }

  .status-pending {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
    color: white;
  }

  .status-completed {
    background: linear-gradient(135deg, #30cfd0 0%, #330867 100%);
    color: white;
  }

  .course-footer {
    display: flex;
    gap: 10px;
    margin-top: auto;
    padding-top: 15px;
    border-top: 1px solid #e2e8f0;
  }

  .course-btn {
    flex: 1;
    padding: 10px 14px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
  }

  .btn-view {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
  }

  .btn-view:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    animation: fadeIn 0.7s ease-out;
  }

  .empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
  }

  .empty-state h3 {
    font-size: 1.5rem;
    font-weight: 800;
    color: #1a202c;
    margin-bottom: 10px;
  }

  .empty-state p {
    color: #718096;
    font-size: 1rem;
    margin-bottom: 25px;
  }

  .empty-btn {
    display: inline-block;
    padding: 12px 28px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 700;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
  }

  .empty-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
  }

  /* Animations */
  @keyframes slideDown {
    from { opacity: 0; transform: translateY(-20px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  @keyframes fadeIn {
    from { opacity: 0; }
    to   { opacity: 1; }
  }

  @keyframes shimmer {
    0%   { left: -100%; }
    100% { left: 100%; }
  }

  /* Responsive */
  @media (max-width: 768px) {
    .courses-wrapper { padding: 30px 15px; }
    .header-content h1 { font-size: 1.8rem; }
    .courses-grid {
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
    }
    .course-footer { flex-direction: column; }
    .course-btn { width: 100%; }
  }

  @media (max-width: 480px) {
    .courses-wrapper { padding: 20px 10px; }
    .header-content h1 { font-size: 1.4rem; }
    .header-icon { font-size: 1.8rem; }
    .courses-grid { grid-template-columns: 1fr; }
    .empty-state { padding: 50px 20px; }
    .empty-icon { font-size: 3rem; }
    .empty-state h3 { font-size: 1.2rem; }
  }
</style>

<div class="courses-wrapper">
  <div class="courses-container">

    <!-- Header -->
    <div class="courses-header">
      <div class="header-top">
        <div class="header-content">
          <h1>
            <span class="header-icon">📚</span>
            {{ __('Kursus Saya') }}
          </h1>
          <p>Kelola dan lanjutkan pembelajaran Anda</p>
        </div>

        {{-- Tombol Lihat Tugas di sebelah "Kursus Saya" --}}
        <div class="header-actions">
          <a href="{{ route('student.assignments.index') }}" class="tasks-btn">
            📝 Lihat Tugas
          </a>
        </div>
      </div>
    </div>

    {{-- Courses --}}
    @if($enrolled->count())
      <div class="courses-grid">
        @foreach($enrolled as $enrollment)
          <div class="course-card">
            <div class="course-image">📖</div>

            <div class="course-content">
              <h3 class="course-title">{{ $enrollment->course->title }}</h3>
              <span class="course-status status-{{ $enrollment->status }}">
                @if($enrollment->status === 'active')
                  ✓ Sedang Belajar
                @elseif($enrollment->status === 'pending')
                  ⏳ Pending
                @else
                  ✓✓ {{ ucfirst($enrollment->status) }}
                @endif
              </span>
            </div>

            <div class="course-footer">
              <a href="{{ route('courses.show', $enrollment->course) }}"
                 class="course-btn btn-view">
                Lihat Kursus
              </a>
            </div>
          </div>
        @endforeach
      </div>
    @else
      <div class="empty-state">
        <div class="empty-icon">📚</div>
        <h3>Belum Ada Kursus</h3>
        <p>Anda belum mendaftar di kursus manapun. Jelajahi katalog kursus kami dan mulai belajar hari ini!</p>
        <a href="{{ route('courses.catalog') }}" class="empty-btn">
          Jelajahi Kursus
        </a>
      </div>
    @endif

  </div>
</div>
@endsection

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
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
    animation: slideDown 0.6s ease-out;
  }

  .header-content h1 {
    font-size: 2.2rem;
    font-weight: 900;
    color: #1a202c;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .header-icon {
    font-size: 2.2rem;
  }

  .create-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  }

  .create-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(102, 126, 234, 0.6);
  }

  .create-btn svg {
    width: 18px;
    height: 18px;
  }

  /* Courses Grid */
  .courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
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

  .status-draft {
    background: #fee2e2;
    color: #991b1b;
  }

  .status-published {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    color: white;
  }

  .status-archived {
    background: #f3f4f6;
    color: #6b7280;
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

  .btn-edit {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
  }

  .btn-edit:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
  }

  .btn-delete {
    background: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
  }

  .btn-delete:hover {
    background: #fecaca;
    transform: translateY(-2px);
  }

  .btn-delete:active {
    transform: translateY(0);
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
    display: inline-flex;
    align-items: center;
    gap: 8px;
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

  .empty-btn svg {
    width: 18px;
    height: 18px;
  }

  /* Animations */
  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
    }
    to {
      opacity: 1;
    }
  }

  @keyframes shimmer {
    0% {
      left: -100%;
    }
    100% {
      left: 100%;
    }
  }

  /* Responsive */
  @media (max-width: 768px) {
    .courses-wrapper {
      padding: 30px 15px;
    }

    .courses-header {
      flex-direction: column;
      align-items: flex-start;
    }

    .header-content h1 {
      font-size: 1.8rem;
    }

    .create-btn {
      width: 100%;
      justify-content: center;
    }

    .courses-grid {
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 20px;
    }

    .course-footer {
      flex-direction: column;
    }

    .course-btn {
      width: 100%;
    }
  }

  @media (max-width: 480px) {
    .courses-wrapper {
      padding: 20px 10px;
    }

    .header-content h1 {
      font-size: 1.4rem;
    }

    .header-icon {
      font-size: 1.8rem;
    }

    .courses-grid {
      grid-template-columns: 1fr;
    }

    .empty-state {
      padding: 50px 20px;
    }

    .empty-icon {
      font-size: 3rem;
    }

    .empty-state h3 {
      font-size: 1.2rem;
    }
  }
</style>

<div class="courses-wrapper">
  <div class="courses-container">

    <!-- Header -->
    <div class="courses-header">
      <div class="header-content">
        <h1>
          <span class="header-icon">📚</span>
          {{ __('Kursus Saya') }}
        </h1>
      </div>
      <a href="{{ route('teacher.courses.create') }}" class="create-btn">
        <span>+</span>
        <span>Buat Kursus</span>
      </a>
    </div>

    <!-- Courses Grid -->
    @forelse($courses as $course)
      <div class="courses-grid">
        <div class="course-card">
          <!-- Course Image -->
          <div class="course-image">
            📖
          </div>

          <!-- Course Content -->
          <div class="course-content">
            <h3 class="course-title">{{ $course->title }}</h3>

            <!-- Status Badge -->
            <span class="course-status status-{{ $course->status }}">
              @if($course->status === 'draft')
                📝 Draft
              @elseif($course->status === 'published')
                ✓ Dipublikasikan
              @elseif($course->status === 'archived')
                📦 Diarsipkan
              @else
                {{ ucfirst($course->status) }}
              @endif
            </span>
          </div>

          <!-- Course Footer -->
          <div class="course-footer">
            <a href="{{ route('teacher.courses.edit', $course) }}" class="course-btn btn-edit">
              ✏️ Edit
            </a>
            <form method="POST" action="{{ route('teacher.courses.destroy', $course) }}" style="flex: 1;">
              @csrf
              @method('DELETE')
              <button type="submit" class="course-btn btn-delete" onclick="return confirm('Hapus kursus ini? Tindakan ini tidak dapat dibatalkan.')">
                🗑️ Hapus
              </button>
            </form>
          </div>
        </div>
      </div>
    @empty
      <div class="empty-state">
        <div class="empty-icon">📚</div>
        <h3>Belum Ada Kursus</h3>
        <p>Anda belum membuat kursus apapun. Mulai buat kursus pertama Anda sekarang!</p>
        <a href="{{ route('teacher.courses.create') }}" class="empty-btn">
          <span>+</span>
          <span>Buat Kursus Baru</span>
        </a>
      </div>
    @endforelse

  </div>
</div>

@endsection
@extends('layouts.app')

@section('content')
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .catalog-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 40px 20px;
  }

  .catalog-container {
    max-width: 1200px;
    margin: 0 auto;
  }

  /* Header */
  .catalog-header {
    margin-bottom: 40px;
    animation: slideDown 0.6s ease-out;
  }

  .catalog-header h1 {
    font-size: 2.5rem;
    font-weight: 900;
    color: #1a202c;
    margin-bottom: 8px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
  }

  .catalog-header p {
    font-size: 1rem;
    color: #718096;
  }

  /* Search & Filter Bar */
  .search-bar {
    background: white;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
    align-items: center;
    animation: slideUp 0.6s ease-out;
  }

  .search-input {
    flex: 1;
    min-width: 250px;
    padding: 12px 18px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
  }

  .search-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .filter-btn {
    padding: 10px 20px;
    background: #f7fafc;
    border: 1px solid #cbd5e0;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .filter-btn:hover {
    background: #edf2f7;
    border-color: #a0aec0;
  }

  /* Courses Grid */
  .courses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
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
    position: relative;
  }

  .course-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
  }

  .course-image {
    width: 100%;
    height: 200px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 3rem;
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

  .course-category {
    display: inline-block;
    background: #edf2f7;
    color: #667eea;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    margin-bottom: 12px;
    width: fit-content;
  }

  .course-title {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 8px;
    line-height: 1.4;
  }

  .course-description {
    font-size: 0.9rem;
    color: #718096;
    margin-bottom: 15px;
    flex: 1;
    line-height: 1.5;
  }

  .course-meta {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e2e8f0;
    font-size: 0.9rem;
    color: #718096;
  }

  .meta-item {
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .course-footer {
    display: flex;
    gap: 10px;
    align-items: center;
  }

  .course-link {
    flex: 1;
    padding: 12px 16px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    text-align: center;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
  }

  .course-link:hover {
    transform: translateX(2px);
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  }

  .enroll-btn {
    flex: 1;
    padding: 12px 16px;
    background: white;
    color: #667eea;
    border: 2px solid #667eea;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .enroll-btn:hover {
    background: #667eea;
    color: white;
    transform: scale(1.02);
  }

  .enroll-btn:active {
    transform: scale(0.98);
  }

  /* Empty State */
  .empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: 15px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
    animation: fadeIn 0.7s ease-out;
  }

  .empty-state svg {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    color: #cbd5e0;
    opacity: 0.6;
  }

  .empty-state h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
  }

  .empty-state p {
    color: #718096;
    font-size: 0.95rem;
  }

  /* Pagination */
  .pagination-wrapper {
    display: flex;
    justify-content: center;
    gap: 10px;
    margin-top: 40px;
    flex-wrap: wrap;
    animation: fadeIn 0.8s ease-out;
  }

  .pagination-wrapper a,
  .pagination-wrapper span {
    padding: 10px 14px;
    border-radius: 8px;
    border: 1px solid #cbd5e0;
    background: white;
    color: #4a5568;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    font-size: 0.9rem;
  }

  .pagination-wrapper a:hover {
    background: #667eea;
    color: white;
    border-color: #667eea;
    transform: translateY(-2px);
  }

  .pagination-wrapper .active span {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: transparent;
  }

  .pagination-wrapper .disabled {
    opacity: 0.5;
    cursor: not-allowed;
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

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(20px);
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
    .catalog-wrapper {
      padding: 30px 15px;
    }

    .catalog-header h1 {
      font-size: 1.8rem;
    }

    .courses-grid {
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
    }

    .search-bar {
      flex-direction: column;
    }

    .search-input {
      min-width: 100%;
    }

    .filter-btn {
      width: 100%;
    }

    .course-footer {
      flex-direction: column;
    }

    .course-link,
    .enroll-btn {
      width: 100%;
    }
  }

  @media (max-width: 480px) {
    .catalog-header h1 {
      font-size: 1.4rem;
    }

    .courses-grid {
      grid-template-columns: 1fr;
    }

    .course-image {
      height: 150px;
      font-size: 2rem;
    }

    .course-title {
      font-size: 1rem;
    }

    .course-meta {
      font-size: 0.8rem;
    }
  }
</style>

<div class="catalog-wrapper">
  <div class="catalog-container">
    
    <!-- Header -->
    <div class="catalog-header">
      <h1>📚 Katalog Kursus</h1>
      <p>Temukan dan ikuti ribuan kursus berkualitas tinggi untuk meningkatkan kemampuan Anda</p>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
      <input 
        type="text" 
        class="search-input" 
        placeholder="🔍 Cari kursus berdasarkan judul..." 
        id="searchInput"
      />
      <button class="filter-btn">Semua Kategori</button>
      <button class="filter-btn">Rating Tertinggi</button>
    </div>

    <!-- Courses Grid -->
    @forelse($courses as $course)
      <div class="courses-grid">
        <div class="course-card" data-course="{{ $course->title }}">
          <!-- Course Image -->
          <div class="course-image">
            📖
          </div>

          <!-- Course Content -->
          <div class="course-content">
            <span class="course-category">{{ $course->category ?? 'Umum' }}</span>
            
            <h3 class="course-title">{{ $course->title }}</h3>
            
            <p class="course-description">
              {{ \Illuminate\Support\Str::limit($course->description ?? 'Deskripsi tidak tersedia', 80) }}
            </p>

            <!-- Course Footer -->
            <div class="course-footer">
              <a href="{{ route('courses.show', $course) }}" class="course-link">
                Lihat Detail
              </a>

              @auth('web')
                @if(auth()->user()->role === 'student')
                  <form method="POST" action="{{ route('student.enroll', $course) }}" style="flex: 1;">
                    @csrf
                    <button type="submit" class="enroll-btn">
                      Daftar Sekarang
                    </button>
                  </form>
                @endif
              @endauth
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="empty-state">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                d="M12 6.253v13m0-13C6.5 6.253 2 10.998 2 17.001c0 5.591 3.824 10.29 9 11.622m0-13c5.5 0 10 4.745 10 10.999 0 5.591-3.824 10.29-9 11.622M9 19.128a9.38 9.38 0 01-3.154-1.597m0 0A9.321 9.321 0 012 17m6-2.252V6.972m0 0a9.305 9.305 0 015.814-2.097c3.528 0 6.579 1.694 8.297 4.222m0 0c5.5 0 10 4.745 10 10.999 0 5.591-3.824 10.29-9 11.622"></path>
        </svg>
        <h3>Tidak ada kursus tersedia</h3>
        <p>Saat ini belum ada kursus. Silahkan coba lagi nanti.</p>
      </div>
    @endforelse

    <!-- Pagination -->
    @if($courses->hasPages())
      <div class="pagination-wrapper">
        {{ $courses->links() }}
      </div>
    @endif

  </div>
</div>

<script>
  // Search functionality
  const searchInput = document.getElementById('searchInput');
  const courseCards = document.querySelectorAll('.course-card');

  searchInput?.addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    
    courseCards.forEach(card => {
      const courseTitle = card.dataset.course.toLowerCase();
      if (courseTitle.includes(searchTerm)) {
        card.style.display = '';
        card.style.animation = 'fadeIn 0.3s ease-out';
      } else {
        card.style.display = 'none';
      }
    });
  });
</script>

@endsection
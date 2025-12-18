@extends('layouts.app')

@section('content')
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .detail-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    padding: 40px 20px;
  }

  .detail-container {
    max-width: 1000px;
    margin: 0 auto;
  }

  /* Hero Section */
  .hero-section {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
    margin-bottom: 30px;
    animation: slideDown 0.6s ease-out;
  }

  .hero-image {
    width: 100%;
    height: 250px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 4rem;
    position: relative;
    overflow: hidden;
  }

  .hero-image::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    animation: shimmer 2s infinite;
  }

  .hero-content {
    padding: 40px;
    position: relative;
    z-index: 1;
  }

  .course-category {
    display: inline-block;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 700;
    margin-bottom: 15px;
  }

  .hero-content h1 {
    font-size: 2.2rem;
    font-weight: 900;
    color: #1a202c;
    margin-bottom: 20px;
    line-height: 1.3;
  }

  /* Main Content Grid */
  .content-grid {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 30px;
  }

  /* Description Section */
  .description-section {
    background: white;
    border-radius: 15px;
    padding: 35px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    animation: slideUp 0.6s ease-out;
  }

  .section-title {
    font-size: 1.3rem;
    font-weight: 800;
    color: #1a202c;
    margin-bottom: 20px;
  }

  .description-section p {
    font-size: 1rem;
    line-height: 1.8;
    color: #4a5568;
  }

  /* Sidebar */
  .sidebar {
    position: sticky;
    top: 20px;
  }

  .enroll-card {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12);
    text-align: center;
    animation: slideUp 0.6s ease-out;
  }

  .enroll-btn {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    border-radius: 10px;
    font-size: 1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-bottom: 15px;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  }

  .enroll-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 25px rgba(102, 126, 234, 0.6);
  }

  .enroll-btn:active {
    transform: translateY(-1px);
  }

  .info-box {
    background: #f7fafc;
    border-radius: 10px;
    padding: 15px;
    border-left: 4px solid #667eea;
    color: #718096;
    font-size: 0.9rem;
  }

  .info-box a {
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
  }

  .info-box a:hover {
    text-decoration: underline;
  }

  /* Back Button */
  .back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #667eea;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 20px;
    transition: all 0.3s ease;
  }

  .back-btn:hover {
    transform: translateX(-5px);
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
    .detail-wrapper {
      padding: 20px 15px;
    }

    .hero-image {
      height: 200px;
      font-size: 3rem;
    }

    .hero-content {
      padding: 25px;
    }

    .hero-content h1 {
      font-size: 1.6rem;
    }

    .content-grid {
      grid-template-columns: 1fr;
    }

    .sidebar {
      position: static;
    }

    .description-section,
    .enroll-card {
      padding: 25px;
    }

    .section-title {
      font-size: 1.1rem;
    }
  }

  @media (max-width: 480px) {
    .detail-wrapper {
      padding: 15px 10px;
    }

    .hero-image {
      height: 150px;
      font-size: 2rem;
    }

    .hero-content h1 {
      font-size: 1.3rem;
    }

    .hero-content {
      padding: 20px;
    }

    .description-section,
    .enroll-card {
      padding: 20px;
    }

    .section-title {
      font-size: 1rem;
    }

    .enroll-btn {
      padding: 12px;
      font-size: 0.95rem;
    }
  }
</style>

<div class="detail-wrapper">
  <div class="detail-container">
    
    <!-- Back Button -->
    <a href="{{ url()->previous() }}" class="back">← Kembali</a>

    <!-- Hero Section -->
    <div class="hero-section">
      <div class="hero-image">
        📖
      </div>
      <div class="hero-content">
        @if($course->category)
          <span class="course-category">{{ $course->category }}</span>
        @endif
        <h1>{{ $course->title }}</h1>
      </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
      
      <!-- Left: Description -->
      <div class="description-section">
        <div class="section-title">📝 Deskripsi</div>
        <p>{{ $course->description }}</p>
      </div>

      <!-- Right: Sidebar -->
      <div class="sidebar">
        <div class="enroll-card">
          @auth('web')
            @if(auth()->user()->role === 'student')
              <form method="POST" action="{{ route('student.enroll', $course) }}">
                @csrf
                <button type="submit" class="enroll-btn">
                  🚀 Daftar Sekarang
                </button>
              </form>
            @elseif(auth()->user()->role === 'teacher')
              <div class="info-box">
                <p>Anda adalah guru dan tidak dapat mendaftar untuk kursus ini.</p>
              </div>
            @endif
          @else
            <div class="info-box">
              <p><a href="{{ route('login') }}">Login</a> terlebih dahulu untuk mendaftar.</p>
            </div>
          @endauth
        </div>
      </div>

    </div>

  </div>
</div>

@endsection
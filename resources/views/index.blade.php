@extends('layouts.app')
@section('content')
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .dashboard-container {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px 20px;
  }

  .dashboard-header {
    text-align: center;
    color: white;
    margin-bottom: 50px;
    animation: fadeInDown 0.8s ease-out;
  }

  .dashboard-header h1 {
    font-size: 2.5rem;
    font-weight: 800;
    margin-bottom: 10px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
  }

  .dashboard-header p {
    font-size: 1.1rem;
    opacity: 0.95;
  }

  .cards-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 30px;
    max-width: 1200px;
    margin: 0 auto;
  }

  .card {
    background: white;
    border-radius: 15px;
    padding: 40px 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    animation: fadeInUp 0.8s ease-out;
  }

  .card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
  }

  .card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #667eea, #764ba2);
  }

  .card-icon {
    font-size: 3rem;
    margin-bottom: 15px;
    display: inline-block;
    animation: float 3s ease-in-out infinite;
  }

  .card-icon.student {
    color: #667eea;
  }

  .card-icon.teacher {
    color: #f093fb;
  }

  .card-icon.admin {
    color: #4facfe;
  }

  .card h2 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 12px;
    color: #2d3748;
  }

  .card-description {
    font-size: 0.95rem;
    color: #718096;
    margin-bottom: 25px;
    line-height: 1.6;
  }

  .card-link {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  }

  .card-link:hover {
    transform: translateX(5px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
  }

  .card-link svg {
    width: 18px;
    height: 18px;
    margin-left: 8px;
  }

  .card:nth-child(1) .card-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
  }

  .card:nth-child(2) .card-link {
    background: linear-gradient(135deg, #f093fb, #f5576c);
  }

  .card:nth-child(3) .card-link {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
  }

  .stats {
    margin-top: 50px;
    text-align: center;
    color: white;
    opacity: 0.95;
  }

  .stats p {
    font-size: 0.95rem;
    margin-top: 10px;
  }

  @keyframes fadeInDown {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes float {
    0%, 100% {
      transform: translateY(0px);
    }
    50% {
      transform: translateY(-10px);
    }
  }

  @media (max-width: 768px) {
    .dashboard-header h1 {
      font-size: 1.8rem;
    }

    .dashboard-container {
      padding: 30px 15px;
    }

    .cards-grid {
      gap: 20px;
    }

    .card {
      padding: 30px 20px;
    }
  }
</style>

<div class="dashboard-container">
  <div class="dashboard-header">
    <h1>🎓 Selamat Datang di Platform Belajar</h1>
    <p>Pilih peran Anda untuk melanjutkan</p>
  </div>

  <div class="cards-grid">
    <!-- Card untuk Siswa -->
    <div class="card">
      <div class="card-icon student">📚</div>
      <h2>Untuk Siswa</h2>
      <p class="card-description">Jelajahi ribuan kursus berkualitas, daftar kursus favorit Anda, dan tingkatkan keterampilan dengan pembelajaran yang fleksibel.</p>
      <a class="card-link" href="{{ route('courses.catalog') }}">
        Katalog Kursus
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
        </svg>
      </a>
    </div>

    <!-- Card untuk Guru -->
    <div class="card">
      <div class="card-icon teacher">👨‍🏫</div>
      <h2>Untuk Guru</h2>
      <p class="card-description">Buat dan kelola kursus Anda, pantau progres siswa, serta berikan materi pembelajaran yang interaktif dan menarik.</p>
      <a class="card-link" href="{{ route('teacher.courses') }}">
        Kursus Saya
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
        </svg>
      </a>
    </div>

    <!-- Card untuk Admin -->
    <div class="card">
      <div class="card-icon admin">⚙️</div>
      <h2>Untuk Admin</h2>
      <p class="card-description">Kelola pengguna, verifikasi guru, pantau aktivitas platform, dan jaga kualitas konten pembelajaran.</p>
      <a class="card-link" href="{{ route('admin.users') }}">
        Guru Aktif
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
        </svg>
      </a>
    </div>
  </div>

  <div class="stats">
    <p>✨ Platform pembelajaran online terpercaya untuk semua kalangan</p>
  </div>
</div>

@endsection
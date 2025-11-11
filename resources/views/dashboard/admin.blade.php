{{-- resources/views/dashboard/admin.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .dashboard-wrapper {
    min-height: 100vh;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 40px 20px;
  }

  .dashboard-container {
    max-width: 1400px;
    margin: 0 auto;
  }

  /* Header */
  .dashboard-header {
    margin-bottom: 50px;
    animation: slideDown 0.6s ease-out;
  }

  .dashboard-header h1 {
    font-size: 3rem;
    font-weight: 900;
    color: white;
    margin-bottom: 10px;
    text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.2);
  }

  .dashboard-header p {
    font-size: 1.1rem;
    color: rgba(255, 255, 255, 0.9);
  }

  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 50px;
  }

  .stat-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    animation: slideUp 0.6s ease-out;
    transition: all 0.3s ease;
  }

  .stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 32px rgba(0, 0, 0, 0.16);
  }

  .stat-icon {
    font-size: 2.5rem;
    margin-bottom: 12px;
  }

  .stat-value {
    font-size: 2rem;
    font-weight: 800;
    color: #667eea;
    margin-bottom: 5px;
  }

  .stat-label {
    font-size: 0.95rem;
    color: #718096;
    font-weight: 600;
  }

  /* Menu Grid */
  .menu-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
  }

  .menu-card {
    background: white;
    border-radius: 15px;
    padding: 40px 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    animation: fadeInUp 0.7s ease-out;
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    cursor: pointer;
  }

  .menu-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, #667eea, #764ba2);
  }

  .menu-card::after {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), transparent);
    border-radius: 50%;
    transform: translate(30px, -30px);
  }

  .menu-card:hover {
    transform: translateY(-12px);
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.18);
  }

  .menu-icon {
    font-size: 3rem;
    margin-bottom: 20px;
    z-index: 1;
  }

  .menu-title {
    font-size: 1.4rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 12px;
    z-index: 1;
  }

  .menu-description {
    font-size: 0.95rem;
    color: #718096;
    line-height: 1.6;
    flex-grow: 1;
    margin-bottom: 20px;
    z-index: 1;
  }

  .menu-link {
    display: inline-flex;
    align-items: center;
    padding: 12px 24px;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s ease;
    width: fit-content;
    z-index: 1;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
  }

  .menu-link:hover {
    transform: translateX(5px);
    box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
  }

  .menu-link svg {
    width: 18px;
    height: 18px;
    margin-left: 8px;
  }

  /* Quick Actions */
  .quick-actions {
    background: white;
    border-radius: 15px;
    padding: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    animation: fadeInUp 0.8s ease-out;
  }

  .quick-actions h2 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1a202c;
    margin-bottom: 20px;
  }

  .action-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
  }

  .action-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background: #f7fafc;
    border-radius: 10px;
    border-left: 4px solid #667eea;
    transition: all 0.3s ease;
  }

  .action-item:hover {
    background: #edf2f7;
    transform: translateX(5px);
  }

  .action-item a {
    display: flex;
    align-items: center;
    color: inherit;
    text-decoration: none;
    font-weight: 600;
    color: #4a5568;
    width: 100%;
  }

  .action-item svg {
    width: 20px;
    height: 20px;
    margin-right: 12px;
    color: #667eea;
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
      transform: translateY(30px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Responsive */
  @media (max-width: 768px) {
    .dashboard-wrapper {
      padding: 30px 15px;
    }

    .dashboard-header h1 {
      font-size: 2rem;
    }

    .menu-grid {
      grid-template-columns: 1fr;
      gap: 20px;
    }

    .stats-grid {
      grid-template-columns: repeat(2, 1fr);
    }

    .quick-actions {
      padding: 20px;
    }

    .action-list {
      grid-template-columns: 1fr;
    }
  }

  @media (max-width: 480px) {
    .dashboard-header h1 {
      font-size: 1.5rem;
    }

    .stat-value {
      font-size: 1.5rem;
    }

    .menu-title {
      font-size: 1.2rem;
    }

    .stats-grid {
      grid-template-columns: 1fr;
    }
  }
</style>

<div class="dashboard-wrapper">
  <div class="dashboard-container">
    
    <!-- Header -->
    <div class="dashboard-header">
      <h1>📊 Admin Dashboard</h1>
      <p>Selamat datang, kelola semua aspek platform EduConnect dari sini</p>
    </div>

    <!-- Main Menu -->
    <div class="menu-grid">
      <a href="{{ route('admin.users') }}" class="menu-card">
        <div class="menu-icon">👥</div>
        <h3 class="menu-title">Kelola Pengguna</h3>
        <p class="menu-description">Kelola data guru dan siswa, verifikasi akun, atur permission, dan monitor aktivitas pengguna di platform.</p>
        <div class="menu-link">
          Akses Sekarang
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
          </svg>
        </div>
      </a>

      <a href="{{ route('courses.catalog') }}" class="menu-card">
        <div class="menu-icon">📚</div>
        <h3 class="menu-title">Katalog Kursus</h3>
        <p class="menu-description">Kelola semua kursus yang tersedia, review konten, terima submission kursus baru, dan atur kategori kursus.</p>
        <div class="menu-link">
          Akses Sekarang
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
          </svg>
        </div>
      </a>

@endsection
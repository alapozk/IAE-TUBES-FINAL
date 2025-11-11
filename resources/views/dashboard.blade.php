@extends('layouts.app')

@section('content')
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
      <p class="card-description">
        Jelajahi ribuan kursus berkualitas, daftar kursus favorit Anda, 
        dan tingkatkan keterampilan dengan pembelajaran yang fleksibel.
      </p>
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
      <p class="card-description">
        Buat dan kelola kursus Anda, pantau progres siswa, 
        serta berikan materi pembelajaran yang interaktif dan menarik.
      </p>
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
      <p class="card-description">
        Kelola pengguna, verifikasi guru, pantau aktivitas platform, 
        dan jaga kualitas konten pembelajaran.
      </p>
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

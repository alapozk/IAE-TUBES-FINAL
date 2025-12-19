@extends('layouts.app')

@section('content')
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  .detail-wrapper{min-height:100vh;background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);padding:40px 20px}
  .detail-container{max-width:1000px;margin:0 auto}
  .hero-section{background:white;border-radius:15px;overflow:hidden;box-shadow:0 10px 40px rgba(0,0,0,.12);margin-bottom:30px}
  .hero-image{width:100%;height:250px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;font-size:4rem}
  .hero-content{padding:40px}
  .course-category{display:inline-block;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;padding:8px 16px;border-radius:20px;font-size:.85rem;font-weight:700;margin-bottom:15px}
  .hero-content h1{font-size:2.2rem;font-weight:900;color:#1a202c;margin-bottom:20px}
  .content-grid{display:grid;grid-template-columns:1fr 320px;gap:30px}
  .description-section{background:white;border-radius:15px;padding:35px;box-shadow:0 8px 24px rgba(0,0,0,.08)}
  .section-title{font-size:1.3rem;font-weight:800;color:#1a202c;margin-bottom:20px}
  .description-section p{font-size:1rem;line-height:1.8;color:#4a5568}
  .sidebar{position:sticky;top:20px}
  .enroll-card{background:white;border-radius:15px;padding:30px;box-shadow:0 10px 40px rgba(0,0,0,.12);text-align:center}
  .enroll-btn{width:100%;padding:15px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;border:none;border-radius:10px;font-size:1rem;font-weight:700;cursor:pointer;margin-bottom:15px;box-shadow:0 4px 15px rgba(102,126,234,.4)}
  .enroll-btn:hover{transform:translateY(-3px);box-shadow:0 6px 25px rgba(102,126,234,.6)}
  .enroll-btn:disabled{opacity:0.6;cursor:not-allowed;transform:none}
  .info-box{background:#f7fafc;border-radius:10px;padding:15px;border-left:4px solid #667eea;color:#718096;font-size:.9rem}
  .info-box a{color:#667eea;text-decoration:none;font-weight:600}
  .back-btn{display:inline-flex;align-items:center;gap:8px;color:#667eea;text-decoration:none;font-weight:600;margin-bottom:20px}
  .alert{padding:12px;border-radius:10px;margin-bottom:16px}
  .alert-error{background:#fee2e2;color:#991b1b}
  .alert-success{background:#d1fae5;color:#065f46}
  @media(max-width:768px){.content-grid{grid-template-columns:1fr}.sidebar{position:static}}
</style>

<div class="detail-wrapper" x-data="courseShowPage()" x-init="loadCourse()">
  <div class="detail-container">
    <a href="/courses" class="back-btn">← Kembali</a>

    <!-- Loading -->
    <div x-show="loading" style="text-align:center;padding:40px">Memuat data kursus...</div>

    <!-- Error -->
    <div class="alert alert-error" x-show="error && !loading" x-text="error"></div>

    <!-- Success -->
    <div class="alert alert-success" x-show="success" x-text="success"></div>

    <!-- Content -->
    <div x-show="!loading && course">
      <!-- Hero Section -->
      <div class="hero-section">
        <div class="hero-image">📖</div>
        <div class="hero-content">
          <span class="course-category" x-show="course?.category" x-text="course?.category"></span>
          <h1 x-text="course?.title"></h1>
        </div>
      </div>

      <!-- Content Grid -->
      <div class="content-grid">
        <!-- Left: Description -->
        <div class="description-section">
          <div class="section-title">📝 Deskripsi</div>
          <p x-text="course?.description || 'Tidak ada deskripsi'"></p>
        </div>

        <!-- Right: Sidebar -->
        <div class="sidebar">
          <div class="enroll-card">
            @auth('web')
              @if(auth()->user()->role === 'student')
                <button class="enroll-btn" @click="enrollCourse" :disabled="enrolling" x-show="!isEnrolled">
                  <span x-text="enrolling ? '⏳ Mendaftar...' : '🚀 Daftar Sekarang'"></span>
                </button>
                <div class="info-box" x-show="isEnrolled">
                  <p>✅ Anda sudah terdaftar di kursus ini. <a :href="'/student/courses/' + course?.id">Masuk ke kursus</a></p>
                </div>
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
</div>

<script>
function courseShowPage() {
    const courseId = window.location.pathname.split('/').filter(Boolean).pop();

    return {
        courseId: courseId,
        course: null,
        loading: true,
        enrolling: false,
        isEnrolled: false,
        error: null,
        success: null,

        async loadCourse() {
            try {
                const query = `
                    query PublicCourse($id: ID!) {
                        publicCourse(id: $id) {
                            id
                            title
                            description
                            category
                        }
                    }
                `;
                const result = await GraphQL.query(query, { id: this.courseId });
                if (result && result.publicCourse) {
                    this.course = result.publicCourse;
                } else {
                    this.error = 'Kursus tidak ditemukan';
                }
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        async enrollCourse() {
            this.enrolling = true;
            this.error = null;

            try {
                const mutation = `
                    mutation EnrollCourse($course_id: ID!) {
                        enrollCourse(course_id: $course_id) {
                            id
                        }
                    }
                `;
                await GraphQL.mutate(mutation, { course_id: this.courseId });
                this.success = 'Berhasil mendaftar! Mengarahkan ke kursus...';
                this.isEnrolled = true;
                setTimeout(() => {
                    window.location.href = '/student/courses/' + this.courseId;
                }, 1500);
            } catch (e) {
                if (e.message.includes('sudah terdaftar')) {
                    this.isEnrolled = true;
                } else {
                    this.error = e.message;
                }
            } finally {
                this.enrolling = false;
            }
        }
    }
}
</script>
@endsection
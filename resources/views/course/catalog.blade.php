@extends('layouts.app')

@section('content')
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  .catalog-wrapper{min-height:100vh;background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);padding:40px 20px}
  .catalog-container{max-width:1200px;margin:0 auto}
  .catalog-header{margin-bottom:40px}
  .catalog-header h1{font-size:2.5rem;font-weight:900;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;margin-bottom:8px}
  .catalog-header p{font-size:1rem;color:#718096}
  .search-bar{background:white;border-radius:12px;padding:20px;margin-bottom:30px;box-shadow:0 4px 15px rgba(0,0,0,.08);display:flex;gap:15px;flex-wrap:wrap;align-items:center}
  .search-input{flex:1;min-width:250px;padding:12px 18px;border:2px solid #e2e8f0;border-radius:8px;font-size:.95rem}
  .search-input:focus{outline:none;border-color:#667eea}
  .courses-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:25px;margin-bottom:40px}
  .course-card{background:white;border-radius:15px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.1);transition:all .3s ease;display:flex;flex-direction:column}
  .course-card:hover{transform:translateY(-8px);box-shadow:0 15px 40px rgba(0,0,0,.15)}
  .course-image{width:100%;height:200px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;font-size:3rem}
  .course-content{padding:25px;flex:1;display:flex;flex-direction:column}
  .course-category{display:inline-block;background:#edf2f7;color:#667eea;padding:4px 12px;border-radius:20px;font-size:.75rem;font-weight:700;margin-bottom:12px;width:fit-content}
  .course-title{font-size:1.2rem;font-weight:700;color:#1a202c;margin-bottom:8px}
  .course-description{font-size:.9rem;color:#718096;margin-bottom:15px;flex:1}
  .course-footer{display:flex;gap:10px;align-items:center}
  .course-link{flex:1;padding:12px 16px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:white;text-decoration:none;border-radius:8px;font-weight:600;font-size:.9rem;text-align:center}
  .enroll-btn{flex:1;padding:12px 16px;background:white;color:#667eea;border:2px solid #667eea;border-radius:8px;font-weight:600;font-size:.9rem;cursor:pointer}
  .enroll-btn:hover{background:#667eea;color:white}
  .enroll-btn:disabled{opacity:0.6;cursor:not-allowed}
  .empty-state{text-align:center;padding:60px 20px;background:white;border-radius:15px;box-shadow:0 8px 24px rgba(0,0,0,.1)}
  .empty-state h3{font-size:1.3rem;font-weight:700;color:#2d3748;margin-bottom:10px}
  .empty-state p{color:#718096}
  .loading{text-align:center;padding:40px;color:#718096}
  .alert{padding:12px;border-radius:10px;margin-bottom:16px}
  .alert-success{background:#d1fae5;color:#065f46}
  @media(max-width:768px){.courses-grid{grid-template-columns:repeat(auto-fill,minmax(250px,1fr))}.search-bar{flex-direction:column}.course-footer{flex-direction:column}.course-link,.enroll-btn{width:100%}}
</style>

<div class="catalog-wrapper" x-data="catalogPage()" x-init="loadCourses()">
  <div class="catalog-container">
    
    <!-- Header -->
    <div class="catalog-header">
      <h1>📚 Katalog Kursus</h1>
      <p>Temukan dan ikuti ribuan kursus berkualitas tinggi untuk meningkatkan kemampuan Anda</p>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
      <input type="text" class="search-input" placeholder="🔍 Cari kursus berdasarkan judul..." x-model="searchQuery" @input="filterCourses">
    </div>

    <!-- Loading -->
    <div class="loading" x-show="loading">Memuat kursus...</div>

    <!-- Success Alert -->
    <div class="alert alert-success" x-show="success" x-text="success"></div>

    <!-- Courses Grid -->
    <div class="courses-grid" x-show="!loading">
      <template x-for="course in filteredCourses" :key="course.id">
        <div class="course-card">
          <div class="course-image">📖</div>
          <div class="course-content">
            <span class="course-category" x-text="course.category || 'Umum'"></span>
            <h3 class="course-title" x-text="course.title"></h3>
            <p class="course-description" x-text="truncate(course.description, 80)"></p>
            <div class="course-footer">
              <a :href="'/courses/' + course.id" class="course-link">Lihat Detail</a>
              @auth('web')
                @if(auth()->user()->role === 'student')
                  <button class="enroll-btn" @click="enrollCourse(course.id)" :disabled="enrollingId === course.id">
                    <span x-text="enrollingId === course.id ? '⏳' : 'Daftar'"></span>
                  </button>
                @endif
              @endauth
            </div>
          </div>
        </div>
      </template>
    </div>

    <!-- Empty State -->
    <div class="empty-state" x-show="!loading && filteredCourses.length === 0">
      <h3>Tidak ada kursus tersedia</h3>
      <p>Saat ini belum ada kursus atau tidak ditemukan hasil pencarian.</p>
    </div>

  </div>
</div>

<script>
function catalogPage() {
    return {
        courses: [],
        filteredCourses: [],
        searchQuery: '',
        loading: true,
        enrollingId: null,
        success: null,

        async loadCourses() {
            try {
                const query = `
                    query {
                        publicCourses {
                            id
                            title
                            description
                            status
                        }
                    }
                `;
                const result = await GraphQL.query(query);
                console.log('GraphQL result:', result);
                this.courses = result.publicCourses || [];
                this.filteredCourses = this.courses;
                console.log('Courses loaded:', this.courses.length);
            } catch (e) {
                console.error('Failed to load courses:', e);
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        filterCourses() {
            const query = this.searchQuery.toLowerCase();
            this.filteredCourses = this.courses.filter(c => 
                c.title.toLowerCase().includes(query)
            );
        },

        truncate(text, length) {
            if (!text) return 'Tidak ada deskripsi';
            return text.length > length ? text.substring(0, length) + '...' : text;
        },

        async enrollCourse(courseId) {
            this.enrollingId = courseId;
            this.success = null;

            try {
                const mutation = `
                    mutation EnrollCourse($course_id: ID!) {
                        enrollCourse(course_id: $course_id) {
                            id
                        }
                    }
                `;
                await GraphQL.mutate(mutation, { course_id: courseId });
                this.success = 'Berhasil mendaftar! Mengarahkan ke kursus...';
                setTimeout(() => {
                    window.location.href = '/student/courses/' + courseId;
                }, 1500);
            } catch (e) {
                if (e.message.includes('sudah terdaftar')) {
                    window.location.href = '/student/courses/' + courseId;
                } else {
                    alert(e.message);
                }
            } finally {
                this.enrollingId = null;
            }
        }
    }
}
</script>
@endsection
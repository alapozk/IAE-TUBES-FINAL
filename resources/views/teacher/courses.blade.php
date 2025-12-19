@extends('layouts.app')

@section('content')
<style>
  *{margin:0;padding:0;box-sizing:border-box}
  .courses-wrapper{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:40px 20px}
  .courses-container{max-width:1200px;margin:0 auto}

  .courses-header{display:flex;justify-content:space-between;align-items:center;gap:20px;flex-wrap:wrap;margin-bottom:30px}
  .header-content h1{font-size:2.2rem;font-weight:900;color:#1a202c;display:flex;align-items:center;gap:12px}
  .header-icon{font-size:2.2rem}
  .create-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;text-decoration:none;border-radius:10px;font-weight:700;box-shadow:0 4px 15px rgba(102,126,234,.4)}
  .create-btn:hover{transform:translateY(-2px);box-shadow:0 6px 25px rgba(102,126,234,.6)}

  .courses-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(320px,1fr));
    gap:25px;
    margin-bottom:30px;
    align-items:start;
  }

  .course-card{background:#fff;border-radius:15px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,.1);transition:.3s;display:flex;flex-direction:column}
  .course-card:hover{transform:translateY(-8px);box-shadow:0 15px 40px rgba(0,0,0,.15)}
  .course-image{width:100%;height:180px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;font-size:2.5rem;position:relative;overflow:hidden}
  .course-image::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.2),transparent);animation:shimmer 2s infinite}
  .course-content{padding:25px;flex:1;display:flex;flex-direction:column}
  .course-title{font-size:1.1rem;font-weight:700;color:#1a202c;margin-bottom:12px;line-height:1.4}
  .course-status{display:inline-block;padding:6px 12px;border-radius:20px;font-size:.8rem;font-weight:700;width:fit-content;margin-bottom:15px}
  .status-draft{background:#fee2e2;color:#991b1b}
  .status-published{background:linear-gradient(135deg,#84fab0 0%,#8fd3f4 100%);color:#fff}
  .status-archived{background:#f3f4f6;color:#6b7280}

  .course-footer{display:flex;justify-content:center;gap:12px;margin-top:auto;padding:14px 0 18px;border-top:1px solid #e2e8f0}
  .course-btn{min-width:110px;text-align:center;padding:10px 16px;border:none;border-radius:10px;font-weight:600;font-size:.9rem;cursor:pointer;transition:.3s}
  .btn-view{background:#f7fafc;color:#1a202c;border:1px solid #e2e8f0;display:inline-flex;align-items:center;justify-content:center;gap:6px;box-shadow:0 2px 4px rgba(0,0,0,.04);text-decoration:none}
  .btn-view:hover{background:#edf2f7;transform:translateY(-2px)}
  .btn-delete{background:#fee2e2;color:#991b1b;border:1px solid #fecaca;display:inline-flex;align-items:center;justify-content:center;gap:6px}
  .btn-delete:hover{background:#fecaca;transform:translateY(-2px)}

  .empty-state{text-align:center;padding:80px 20px;background:#fff;border-radius:15px;box-shadow:0 8px 24px rgba(0,0,0,.08)}
  .empty-icon{font-size:4rem;margin-bottom:20px}
  .empty-btn{display:inline-flex;align-items:center;gap:8px;padding:12px 28px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;text-decoration:none;border-radius:8px;font-weight:700}
  .empty-btn:hover{transform:translateY(-2px)}

  .loading-state{text-align:center;padding:60px 20px;color:#718096}

  @keyframes shimmer{0%{left:-100%}100%{left:100%}}

  @media(max-width:768px){
    .courses-wrapper{padding:30px 15px}
    .header-content h1{font-size:1.8rem}
    .courses-grid{grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px}
    .course-footer{flex-direction:column}
    .course-btn{width:100%}
  }
</style>

<div class="courses-wrapper" x-data="coursesPage()" x-init="loadCourses()">
  <div class="courses-container">

    <div class="courses-header">
      <div class="header-content">
        <h1><span class="header-icon">📚</span> {{ __('Kursus Saya') }}</h1>
      </div>
      <a href="{{ route('teacher.courses.create') }}" class="create-btn"><span>+</span><span>Buat Kursus</span></a>
    </div>

    <!-- Loading State -->
    <template x-if="loading">
      <div class="loading-state">
        <p>Memuat kursus...</p>
      </div>
    </template>

    <!-- Courses Grid -->
    <div class="courses-grid" x-show="!loading">
      <template x-for="course in courses" :key="course.id">
        <div class="course-card">
          <div class="course-image">📖</div>

          <div class="course-content">
            <h3 class="course-title" x-text="course.title"></h3>
            <span class="course-status" 
                  :class="'status-' + course.status"
                  x-text="course.status === 'draft' ? '📝 Draft' : (course.status === 'published' ? '✓ Dipublikasikan' : '📦 Diarsipkan')">
            </span>
          </div>

          <div class="course-footer">
            <a :href="'/teacher/courses/' + course.id" class="course-btn btn-view">👁️ View</a>
            <button @click="deleteCourse(course.id)" class="course-btn btn-delete">🗑️ Hapus</button>
          </div>
        </div>
      </template>

      <!-- Empty State -->
      <template x-if="!loading && courses.length === 0">
        <div class="empty-state" style="grid-column:1 / -1">
          <div class="empty-icon">📚</div>
          <h3>Belum Ada Kursus</h3>
          <p>Anda belum membuat kursus apapun. Mulai buat kursus pertama Anda sekarang!</p>
          <a href="{{ route('teacher.courses.create') }}" class="empty-btn"><span>+</span><span>Buat Kursus Baru</span></a>
        </div>
      </template>
    </div>

  </div>
</div>

<script>
function coursesPage() {
    return {
        courses: [],
        loading: true,

        async loadCourses() {
            try {
                const query = `
                    query {
                        teacherCourses {
                            id
                            title
                            code
                            status
                        }
                    }
                `;
                const result = await GraphQL.query(query);
                this.courses = result.teacherCourses || [];
            } catch (e) {
                console.error('Error loading courses:', e);
                alert('Gagal memuat kursus: ' + e.message);
            } finally {
                this.loading = false;
            }
        },

        async deleteCourse(id) {
            if (!confirm('Hapus kursus ini?')) return;

            try {
                const mutation = `
                    mutation DeleteCourse($id: ID!) {
                        deleteCourse(id: $id)
                    }
                `;
                await GraphQL.mutate(mutation, { id: id.toString() });
                this.courses = this.courses.filter(c => c.id !== id);
            } catch (e) {
                alert('Gagal menghapus: ' + e.message);
            }
        }
    }
}
</script>
@endsection

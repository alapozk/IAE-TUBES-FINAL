@extends('layouts.app')

@section('content')
<style>
  .page{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .container{max-width:1100px;margin:0 auto}
  .back{display:inline-flex;gap:8px;align-items:center;color:#667eea;text-decoration:none;font-weight:700;margin-bottom:14px}
  .hero{border-radius:20px;overflow:hidden;box-shadow:0 15px 40px rgba(0,0,0,.12);margin-bottom:18px}
  .hero-top{height:190px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);display:flex;align-items:center;justify-content:center;font-size:3rem}
  .hero-bottom{background:#fff;padding:26px 28px;display:flex;align-items:center;gap:14px}
  .title{font-size:2.1rem;font-weight:900;color:#111827;margin:0}
  .muted{color:#6b7280}
  .actions{display:flex;gap:12px;margin-left:auto}
  .btn{display:inline-flex;gap:8px;align-items:center;padding:10px 16px;border-radius:12px;border:1px solid #e5e7eb;background:#f9fafb;color:#111827;text-decoration:none;font-weight:700;cursor:pointer}
  .btn:hover{background:#f3f4f6}
  .btn-primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none;box-shadow:0 8px 20px rgba(102,126,234,.35)}
  .btn-primary:hover{transform:translateY(-1px)}
  .card{background:#fff;border-radius:18px;box-shadow:0 15px 40px rgba(0,0,0,.10);padding:22px;margin-top:14px}
  .card h3{display:flex;gap:10px;align-items:center;font-weight:900;margin:0 0 10px}
  .materials-grid{display:grid;grid-template-columns:1fr;gap:14px}
  .material-card{background:#fff;border-radius:14px;padding:14px 16px;box-shadow:0 8px 24px rgba(0,0,0,.08);display:flex;gap:12px;align-items:flex-start;transition:.25s}
  .material-card:hover{transform:translateY(-3px);box-shadow:0 12px 28px rgba(0,0,0,.12)}
  .mat-icon{width:44px;height:44px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;color:#fff}
  .mat-body{flex:1;min-width:0}
  .mat-title{font-weight:800;color:#111827;margin:0 0 4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
  .mat-meta{color:#6b7280;font-size:.85rem}
  .mat-actions{display:flex;gap:8px;margin-top:10px;flex-wrap:wrap}
  .btn-xs{padding:7px 10px;border-radius:8px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:700;text-decoration:none;font-size:.85rem;cursor:pointer}
  .btn-xs:hover{background:#f3f4f6}
  .badge{display:inline-flex;align-items:center;gap:6px;font-weight:800;font-size:.75rem;border-radius:999px;padding:5px 9px}
  .badge-video{background:#e0f2fe;color:#075985}
  .badge-pdf{background:#fee2e2;color:#7f1d1d}
  .badge-ppt{background:#fff7ed;color:#7c2d12}
  .two-col{display:grid;grid-template-columns:1fr 1fr;gap:20px}
  .loading{text-align:center;padding:40px;color:#6b7280}
  @media (max-width:900px){.two-col{grid-template-columns:1fr!important}}
</style>

<div class="page" x-data="courseShowPage()" x-init="loadCourse()">
  <div class="container">

    <a href="{{ route('teacher.courses') }}" class="back">← Kembali</a>

    <!-- Loading -->
    <div x-show="loading" class="loading">Memuat data kursus...</div>

    <!-- Error -->
    <div x-show="error && !loading" style="background:#fee2e2;padding:16px;border-radius:10px;color:#991b1b;margin-bottom:16px" x-text="error"></div>

    <!-- Content -->
    <div x-show="!loading && course">
        <!-- Hero -->
        <div class="hero">
          <div class="hero-top">📖</div>
          <div class="hero-bottom">
            <h1 class="title" x-text="course.title"></h1>
            <span class="muted" style="font-weight:800" x-text="'(' + (course.status || 'draft').toUpperCase() + ')'"></span>
            <div class="actions">
              <a :href="'/teacher/courses/' + course.id + '/edit'" class="btn">⚙️ Pengaturan</a>
            </div>
          </div>
        </div>

        <!-- Materials & Assignments Card -->
        <div class="card">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:10px">
            <h3 style="margin:0;font-weight:900;">📚 Materi & 🚀 Tugas</h3>
            <div style="display:flex;gap:10px;flex-wrap:wrap">
              <a :href="'/teacher/courses/' + course.id + '/materials/create'" class="btn btn-primary">＋ Tambah Materi</a>
              <a :href="'/teacher/courses/' + course.id + '/assignments/create'" class="btn btn-primary">📝 Buat Tugas</a>
            </div>
          </div>

          <div class="two-col">
            <!-- Materials -->
            <div>
              <h4 style="font-weight:800;margin-bottom:8px">📄 Materi</h4>
              <div class="materials-grid">
                <template x-for="m in course.materials || []" :key="m.id">
                  <div class="material-card">
                    <div class="mat-icon" style="background:#3b82f6">📄</div>
                    <div class="mat-body">
                      <div class="mat-title" x-text="m.title"></div>
                      <div class="mat-meta">
                        <span class="badge badge-pdf" x-text="(m.extension || 'FILE').toUpperCase()"></span>
                      </div>
                      <div class="mat-actions">
                        <a class="btn-xs" :href="'/teacher/courses/' + course.id + '/materials/' + m.id">👁️ View</a>
                        <a class="btn-xs" :href="'/teacher/courses/' + course.id + '/materials/' + m.id + '/edit'">✏️ Edit</a>
                        <button class="btn-xs" @click="deleteMaterial(m.id)">🗑️ Hapus</button>
                      </div>
                    </div>
                  </div>
                </template>
                <template x-if="!course.materials || course.materials.length === 0">
                  <p class="muted">Belum ada materi.</p>
                </template>
              </div>
            </div>

            <!-- Assignments -->
            <div>
              <h4 style="font-weight:800;margin-bottom:8px">🧾 Tugas</h4>
              <div style="display:flex;flex-direction:column;gap:12px">
                <template x-for="a in course.assignments || []" :key="a.id">
                  <div class="material-card">
                    <div class="mat-icon" style="background:linear-gradient(135deg,#8b5cf6 0%,#6d28d9 100%)">📝</div>
                    <div class="mat-body">
                      <div class="mat-title" x-text="a.title"></div>
                      <div class="mat-meta">
                        <span class="badge" style="background:#e9d5ff;color:#6b21a8">TUGAS</span>
                        <span class="badge" style="background:#dbeafe;color:#1e40af" x-text="(a.submissions_count || 0) + ' Submission'"></span>
                        <span style="margin-left:8px" x-text="'Deadline ' + (a.due_at ? formatDate(a.due_at) : '-')"></span>
                      </div>
                      <div class="mat-actions">
                        <a class="btn-xs" :href="'/teacher/courses/' + course.id + '/assignments/' + a.id + '/submissions'" style="background:#4f46e5;color:#fff">📋 Lihat Submission</a>
                        <a class="btn-xs" :href="'/teacher/courses/' + course.id + '/assignments/' + a.id + '/edit'">✏️ Edit</a>
                        <button class="btn-xs" @click="deleteAssignment(a.id)">🗑️ Hapus</button>
                      </div>
                    </div>
                  </div>
                </template>
                <template x-if="!course.assignments || course.assignments.length === 0">
                  <p class="muted">Belum ada tugas.</p>
                </template>
              </div>
            </div>
          </div>
        </div>

        <!-- Quiz Card -->
        <div class="card" style="margin-top:18px">
          <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
            <h3 style="font-weight:900;margin:0">🧠 Quiz</h3>
            <a :href="'/teacher/courses/' + course.id + '/quizzes/create'" class="btn btn-primary">＋ Buat Quiz</a>
          </div>

          <div style="display:flex;flex-direction:column;gap:12px">
            <template x-for="quiz in course.quizzes || []" :key="quiz.id">
              <div class="material-card">
                <div class="mat-icon" style="background:linear-gradient(135deg,#22c55e 0%,#16a34a 100%)">📝</div>
                <div class="mat-body" style="flex:1">
                  <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
                    <strong x-text="quiz.title"></strong>
                    <span style="font-size:.7rem;padding:4px 8px;border-radius:999px;font-weight:800;color:#fff"
                          :style="'background:' + (quiz.is_published ? '#16a34a' : '#dc2626')"
                          x-text="quiz.is_published ? 'DIBUKA' : 'DISEMBUNYIKAN'"></span>
                    <span class="badge" style="background:#dbeafe;color:#1e40af" x-text="(quiz.attempts_count || 0) + ' Attempt'"></span>
                  </div>
                  <div style="font-size:.85rem;color:#6b7280;display:flex;gap:14px;flex-wrap:wrap;margin-top:6px">
                    <span x-text="'⏱ ' + (quiz.duration || '-') + ' menit'"></span>
                    <span x-text="'🔁 ' + quiz.max_attempt + ' attempt max'"></span>
                  </div>
                </div>
                <div style="display:flex;gap:10px;flex-wrap:wrap">
                  <a class="btn-xs" :href="'/teacher/courses/' + course.id + '/quizzes/' + quiz.id + '/attempts'" style="background:#4f46e5;color:#fff">📋 Lihat Hasil</a>
                  <a class="btn-xs" :href="'/teacher/courses/' + course.id + '/quizzes/' + quiz.id + '/edit'">⚙️ Kelola</a>
                  <button class="btn-xs" @click="toggleQuiz(quiz.id)" x-text="quiz.is_published ? '🔒 Tutup' : '👁️ Buka'"></button>
                  <button class="btn-xs" style="color:#dc2626" @click="deleteQuiz(quiz.id)">🗑️ Hapus</button>
                </div>
              </div>
            </template>
            <template x-if="!course.quizzes || course.quizzes.length === 0">
              <p class="muted">Belum ada quiz pada kursus ini.</p>
            </template>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

<script>
function courseShowPage() {
    return {
        course: null,
        loading: true,
        error: null,
        courseId: window.location.pathname.split('/').filter(Boolean).pop(),

        async loadCourse() {
            console.log('Loading course ID:', this.courseId);
            try {
                const query = `
                    query TeacherCourse($id: ID!) {
                        teacherCourse(id: $id) {
                            id
                            title
                            code
                            status
                            materials { id title extension created_at }
                            assignments { id title due_at submissions_count }
                            quizzes { id title duration max_attempt is_published deadline attempts_count }
                        }
                    }
                `;
                const result = await GraphQL.query(query, { id: this.courseId });
                console.log('GraphQL result:', result);
                if (result && result.teacherCourse) {
                    this.course = result.teacherCourse;
                } else {
                    this.error = 'Kursus tidak ditemukan atau Anda bukan pemiliknya.';
                }
            } catch (e) {
                console.error('GraphQL error:', e);
                this.error = e.message || 'Gagal memuat data kursus';
            } finally {
                this.loading = false;
            }
        },

        async deleteMaterial(id) {
            if (!confirm('Hapus materi ini?')) return;
            try {
                await GraphQL.mutate(`mutation { deleteMaterial(id: "${id}") }`);
                this.course.materials = this.course.materials.filter(m => m.id !== id);
            } catch (e) { alert(e.message); }
        },

        async deleteAssignment(id) {
            if (!confirm('Hapus tugas ini?')) return;
            try {
                await GraphQL.mutate(`mutation { deleteAssignment(id: "${id}") }`);
                this.course.assignments = this.course.assignments.filter(a => a.id !== id);
            } catch (e) { alert(e.message); }
        },

        async deleteQuiz(id) {
            if (!confirm('Hapus quiz ini? Semua soal akan ikut terhapus!')) return;
            try {
                await GraphQL.mutate(`mutation { deleteQuiz(id: "${id}") }`);
                this.course.quizzes = this.course.quizzes.filter(q => q.id !== id);
            } catch (e) { alert(e.message); }
        },

        async toggleQuiz(id) {
            try {
                const result = await GraphQL.mutate(`
                    mutation { toggleQuizPublish(id: "${id}") { id is_published } }
                `);
                const quiz = this.course.quizzes.find(q => q.id === id);
                if (quiz) quiz.is_published = result.toggleQuizPublish.is_published;
            } catch (e) { alert(e.message); }
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            return new Date(dateStr).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        }
    }
}
</script>
@endsection

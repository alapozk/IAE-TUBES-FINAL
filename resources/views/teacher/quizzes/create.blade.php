@extends('layouts.app')

@section('content')
<style>
  .page{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .container{max-width:900px;margin:0 auto}
  .back{display:inline-flex;gap:8px;align-items:center;color:#667eea;text-decoration:none;font-weight:700;margin-bottom:14px}
  .card{background:#fff;border-radius:18px;box-shadow:0 15px 40px rgba(0,0,0,.10);padding:26px}
  .card h3{font-weight:900;margin-bottom:18px;display:flex;align-items:center;gap:10px}
  .form-group{margin-bottom:16px}
  label{font-weight:800;margin-bottom:6px;display:block}
  .form-control{width:100%;padding:12px 14px;border-radius:12px;border:1px solid #e5e7eb;font-weight:600}
  .form-control:focus{outline:none;border-color:#667eea;box-shadow:0 0 0 3px rgba(102,126,234,.25)}
  .actions{display:flex;gap:12px;margin-top:20px;flex-wrap:wrap}
  .btn{display:inline-flex;gap:8px;align-items:center;padding:12px 18px;border-radius:12px;border:1px solid #e5e7eb;background:#f9fafb;color:#111827;text-decoration:none;font-weight:800;cursor:pointer}
  .btn:hover{background:#f3f4f6}
  .btn-primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none;box-shadow:0 8px 20px rgba(102,126,234,.35)}
  .btn-primary:hover{transform:translateY(-1px)}
  .btn-primary:disabled{opacity:0.6;cursor:not-allowed;transform:none}
  .hint{color:#6b7280;font-size:.85rem;margin-top:4px}
  .alert{padding:12px;border-radius:10px;margin-bottom:16px}
  .alert-error{background:#fee2e2;color:#991b1b}
  .alert-success{background:#d1fae5;color:#065f46}
</style>

<div class="page" x-data="quizCreateForm()">
  <div class="container">
    <a :href="'/teacher/courses/' + courseId" class="back">← Kembali ke Kursus</a>

    <div class="card">
      <h3>🧠 Buat Quiz Baru</h3>

      <!-- Alerts -->
      <div class="alert alert-error" x-show="error" x-text="error"></div>
      <div class="alert alert-success" x-show="success" x-text="success"></div>

      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label>Judul Quiz</label>
          <input type="text" x-model="title" class="form-control" placeholder="Contoh: Quiz Pertemuan 1" required>
        </div>

        <div class="form-group">
          <label>Jumlah Attempt</label>
          <select x-model="max_attempt" class="form-control">
            <option value="1">1x Attempt (Sekali saja)</option>
            <option value="2">2x Attempt</option>
            <option value="3">3x Attempt</option>
          </select>
          <div class="hint">Berapa kali siswa boleh mengerjakan quiz</div>
        </div>

        <div class="form-group">
          <label>Durasi Pengerjaan (menit)</label>
          <input type="number" x-model="duration" class="form-control" min="1">
          <div class="hint">Waktu maksimal pengerjaan quiz</div>
        </div>

        <div class="form-group">
          <label>Deadline Quiz</label>
          <input type="datetime-local" x-model="deadline" class="form-control">
          <div class="hint">Quiz tidak bisa dikerjakan setelah waktu ini</div>
        </div>

        <div class="form-group">
          <label>Akses Review Jawaban</label>
          <select x-model="show_review" class="form-control">
            <option value="1">Boleh lihat review</option>
            <option value="0">Tidak boleh lihat review</option>
          </select>
          <div class="hint">Jika tidak diizinkan, siswa hanya melihat nilai</div>
        </div>

        <div class="form-group">
          <label>Status Quiz</label>
          <select x-model="is_published" class="form-control">
            <option value="1">👁️ Dibuka untuk siswa</option>
            <option value="0">🔒 Disembunyikan</option>
          </select>
        </div>

        <div class="actions">
          <button type="submit" class="btn btn-primary" :disabled="loading">
            <span x-text="loading ? '⏳ Menyimpan...' : '💾 Simpan Quiz'"></span>
          </button>
          <a :href="'/teacher/courses/' + courseId" class="btn">✖ Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function quizCreateForm() {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    // URL: /teacher/courses/{courseId}/quizzes/create
    const courseId = pathParts[2];

    return {
        courseId: courseId,
        title: '',
        max_attempt: '1',
        duration: 30,
        deadline: '',
        show_review: '1',
        is_published: '0',
        loading: false,
        error: null,
        success: null,

        async submitForm() {
            this.loading = true;
            this.error = null;

            try {
                const mutation = `
                    mutation CreateQuiz(
                        $course_id: ID!,
                        $title: String!,
                        $max_attempt: Int!,
                        $duration: Int!,
                        $deadline: DateTime,
                        $show_review: Boolean!,
                        $is_published: Boolean!
                    ) {
                        createQuiz(
                            course_id: $course_id,
                            title: $title,
                            max_attempt: $max_attempt,
                            duration: $duration,
                            deadline: $deadline,
                            show_review: $show_review,
                            is_published: $is_published
                        ) {
                            id
                            title
                        }
                    }
                `;

                const result = await GraphQL.mutate(mutation, {
                    course_id: this.courseId,
                    title: this.title,
                    max_attempt: parseInt(this.max_attempt),
                    duration: parseInt(this.duration),
                    deadline: this.deadline || null,
                    show_review: this.show_review === '1',
                    is_published: this.is_published === '1'
                });

                this.success = 'Quiz berhasil dibuat!';
                setTimeout(() => {
                    window.location.href = '/teacher/courses/' + this.courseId;
                }, 1000);
            } catch (e) {
                this.error = e.message || 'Gagal membuat quiz';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection

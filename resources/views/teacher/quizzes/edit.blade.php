@extends('layouts.app')

@section('content')
<style>
  .page{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .container{max-width:900px;margin:0 auto}
  .back{display:inline-flex;gap:8px;align-items:center;color:#667eea;text-decoration:none;font-weight:700;margin-bottom:14px}
  .card{background:#fff;border-radius:18px;box-shadow:0 15px 40px rgba(0,0,0,.10);padding:26px;margin-bottom:20px}
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
  .btn-sm{padding:8px 14px;font-size:.85rem}
  .btn-danger{background:#dc2626;color:#fff;border:none}
  .hint{color:#6b7280;font-size:.85rem;margin-top:4px}
  .alert{padding:12px;border-radius:10px;margin-bottom:16px}
  .alert-error{background:#fee2e2;color:#991b1b}
  .alert-success{background:#d1fae5;color:#065f46}
  .loading{text-align:center;padding:30px}
  .question-item{background:#f8fafc;border:1px solid #e5e7eb;border-radius:12px;padding:16px;margin-bottom:12px}
  .question-item:hover{background:#f1f5f9}
  .question-header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px}
  .question-number{background:#667eea;color:#fff;width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:.85rem;flex-shrink:0}
  .question-text{flex:1;font-weight:600;color:#1f2937}
  .question-actions{display:flex;gap:8px}
  .options-preview{margin-top:10px;font-size:.85rem;color:#6b7280}
  .correct-answer{color:#059669;font-weight:700}
</style>

<div class="page" x-data="quizEditForm()" x-init="loadQuiz()">
  <div class="container">
    <a :href="'/teacher/courses/' + courseId" class="back">← Kembali ke Kursus</a>

    <!-- Quiz Settings Card -->
    <div class="card">
      <h3>⚙️ Pengaturan Quiz</h3>

      <div class="loading" x-show="loading">Memuat data quiz...</div>

      <div class="alert alert-error" x-show="error" x-text="error"></div>
      <div class="alert alert-success" x-show="success" x-text="success"></div>

      <form @submit.prevent="submitForm" x-show="!loading && quiz">
        <div class="form-group">
          <label>Judul Quiz</label>
          <input type="text" x-model="title" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Jumlah Attempt</label>
          <select x-model="max_attempt" class="form-control">
            <option value="1">1x Attempt (Sekali saja)</option>
            <option value="2">2x Attempt</option>
            <option value="3">3x Attempt</option>
          </select>
        </div>

        <div class="form-group">
          <label>Durasi Pengerjaan (menit)</label>
          <input type="number" x-model="duration" class="form-control" min="1">
        </div>

        <div class="form-group">
          <label>Deadline Quiz</label>
          <input type="datetime-local" x-model="deadline" class="form-control">
        </div>

        <div class="form-group">
          <label>Akses Review Jawaban</label>
          <select x-model="show_review" class="form-control">
            <option value="1">Boleh lihat review</option>
            <option value="0">Tidak boleh lihat review</option>
          </select>
        </div>

        <div class="form-group">
          <label>Status Quiz</label>
          <select x-model="is_published" class="form-control">
            <option value="1">👁️ Dibuka untuk siswa</option>
            <option value="0">🔒 Disembunyikan</option>
          </select>
        </div>

        <div class="actions">
          <button type="submit" class="btn btn-primary" :disabled="saving">
            <span x-text="saving ? '⏳ Menyimpan...' : '💾 Simpan Perubahan'"></span>
          </button>
        </div>
      </form>
    </div>

    <!-- Questions Card -->
    <div class="card" x-show="!loading && quiz">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px">
        <h3 style="margin:0">📝 Daftar Soal</h3>
        <a :href="'/teacher/quizzes/' + quizId + '/questions/create'" class="btn btn-primary btn-sm">➕ Tambah Soal</a>
      </div>

      <!-- Questions List -->
      <div x-show="questions.length > 0">
        <template x-for="(q, index) in questions" :key="q.id">
          <div class="question-item">
            <div class="question-header">
              <span class="question-number" x-text="index + 1"></span>
              <div class="question-text" x-text="q.question"></div>
              <div class="question-actions">
                <a :href="'/teacher/quizzes/' + quizId + '/questions/' + q.id + '/edit'" class="btn btn-sm">✏️ Edit</a>
                <button class="btn btn-sm btn-danger" @click="deleteQuestion(q.id)">🗑️</button>
              </div>
            </div>
            <div class="options-preview">
              <span>A: <span x-text="q.option_a"></span></span> |
              <span>B: <span x-text="q.option_b"></span></span> |
              <span>C: <span x-text="q.option_c"></span></span> |
              <span>D: <span x-text="q.option_d"></span></span>
              <span class="correct-answer" style="margin-left:10px">✓ Jawaban: <span x-text="q.correct_answer"></span></span>
            </div>
          </div>
        </template>
      </div>

      <div x-show="questions.length === 0" style="text-align:center;padding:20px;color:#6b7280">
        Belum ada soal. Klik "Tambah Soal" untuk membuat soal baru.
      </div>
    </div>

    <a :href="'/teacher/courses/' + courseId" class="btn">← Kembali ke Kursus</a>
  </div>
</div>

<script>
function quizEditForm() {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    const courseId = pathParts[2];
    const quizId = pathParts[4];

    return {
        courseId: courseId,
        quizId: quizId,
        quiz: null,
        questions: [],
        title: '',
        max_attempt: '1',
        duration: 30,
        deadline: '',
        show_review: '1',
        is_published: '0',
        loading: true,
        saving: false,
        error: null,
        success: null,

        async loadQuiz() {
            try {
                const query = `
                    query TeacherQuiz($id: ID!) {
                        teacherQuiz(id: $id) {
                            id
                            title
                            max_attempt
                            duration
                            deadline
                            show_review
                            is_published
                            questions {
                                id
                                question
                                option_a
                                option_b
                                option_c
                                option_d
                                correct_answer
                            }
                        }
                    }
                `;
                const result = await GraphQL.query(query, { id: this.quizId });
                if (result && result.teacherQuiz) {
                    this.quiz = result.teacherQuiz;
                    this.questions = result.teacherQuiz.questions || [];
                    this.title = result.teacherQuiz.title;
                    this.max_attempt = String(result.teacherQuiz.max_attempt);
                    this.duration = result.teacherQuiz.duration;
                    this.deadline = result.teacherQuiz.deadline ? result.teacherQuiz.deadline.substring(0, 16) : '';
                    this.show_review = result.teacherQuiz.show_review ? '1' : '0';
                    this.is_published = result.teacherQuiz.is_published ? '1' : '0';
                } else {
                    this.error = 'Quiz tidak ditemukan';
                }
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        async submitForm() {
            this.saving = true;
            this.error = null;
            this.success = null;

            try {
                const mutation = `
                    mutation UpdateQuiz(
                        $id: ID!,
                        $title: String!,
                        $max_attempt: Int!,
                        $duration: Int,
                        $deadline: DateTime,
                        $show_review: Boolean!,
                        $is_published: Boolean!
                    ) {
                        updateQuiz(
                            id: $id,
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

                await GraphQL.mutate(mutation, {
                    id: this.quizId,
                    title: this.title,
                    max_attempt: parseInt(this.max_attempt),
                    duration: this.duration ? parseInt(this.duration) : null,
                    deadline: this.deadline ? this.deadline + ':00' : null,
                    show_review: this.show_review === '1',
                    is_published: this.is_published === '1'
                });

                this.success = 'Quiz berhasil diperbarui!';
            } catch (e) {
                this.error = e.message || 'Gagal menyimpan quiz';
            } finally {
                this.saving = false;
            }
        },

        async deleteQuestion(id) {
            if (!confirm('Hapus soal ini?')) return;
            try {
                await GraphQL.mutate(`mutation { deleteQuizQuestion(id: "${id}") }`);
                this.questions = this.questions.filter(q => q.id !== id);
            } catch (e) {
                alert(e.message);
            }
        }
    }
}
</script>
@endsection

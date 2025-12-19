@extends('layouts.app')

@section('content')
<style>
  .page{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .container{max-width:800px;margin:0 auto}
  .back{display:inline-flex;gap:8px;align-items:center;color:#667eea;text-decoration:none;font-weight:700;margin-bottom:14px}
  .card{background:#fff;border-radius:18px;box-shadow:0 15px 40px rgba(0,0,0,.10);padding:26px}
  .card h3{font-weight:900;margin-bottom:18px;display:flex;align-items:center;gap:10px}
  .form-group{margin-bottom:16px}
  label{font-weight:800;margin-bottom:6px;display:block}
  .form-control{width:100%;padding:12px 14px;border-radius:12px;border:1px solid #e5e7eb;font-weight:600}
  .form-control:focus{outline:none;border-color:#667eea;box-shadow:0 0 0 3px rgba(102,126,234,.25)}
  textarea.form-control{min-height:100px;resize:vertical}
  .actions{display:flex;gap:12px;margin-top:20px;flex-wrap:wrap}
  .btn{display:inline-flex;gap:8px;align-items:center;padding:12px 18px;border-radius:12px;border:1px solid #e5e7eb;background:#f9fafb;color:#111827;text-decoration:none;font-weight:800;cursor:pointer}
  .btn:hover{background:#f3f4f6}
  .btn-primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none;box-shadow:0 8px 20px rgba(102,126,234,.35)}
  .btn-primary:hover{transform:translateY(-1px)}
  .btn-primary:disabled{opacity:0.6;cursor:not-allowed;transform:none}
  .alert{padding:12px;border-radius:10px;margin-bottom:16px}
  .alert-error{background:#fee2e2;color:#991b1b}
  .alert-success{background:#d1fae5;color:#065f46}
  .loading{text-align:center;padding:30px}
  .options-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
</style>

<div class="page" x-data="questionEditForm()" x-init="loadQuestion()">
  <div class="container">
    <a :href="backUrl" class="back">← Kembali ke Quiz</a>

    <div class="card">
      <h3>✏️ Edit Soal</h3>

      <div class="loading" x-show="loading">Memuat data soal...</div>

      <div class="alert alert-error" x-show="error" x-text="error"></div>
      <div class="alert alert-success" x-show="success" x-text="success"></div>

      <form @submit.prevent="submitForm" x-show="!loading && question">
        <div class="form-group">
          <label>Pertanyaan</label>
          <textarea x-model="questionText" class="form-control" required placeholder="Tulis pertanyaan di sini..."></textarea>
        </div>

        <div class="options-grid">
          <div class="form-group">
            <label>Opsi A</label>
            <input type="text" x-model="option_a" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Opsi B</label>
            <input type="text" x-model="option_b" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Opsi C</label>
            <input type="text" x-model="option_c" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Opsi D</label>
            <input type="text" x-model="option_d" class="form-control" required>
          </div>
        </div>

        <div class="form-group">
          <label>Jawaban Benar</label>
          <select x-model="correct_answer" class="form-control" required>
            <option value="">-- Pilih Jawaban Benar --</option>
            <option value="A">A</option>
            <option value="B">B</option>
            <option value="C">C</option>
            <option value="D">D</option>
          </select>
        </div>

        <div class="actions">
          <button type="submit" class="btn btn-primary" :disabled="saving">
            <span x-text="saving ? '⏳ Menyimpan...' : '💾 Simpan Perubahan'"></span>
          </button>
          <a :href="backUrl" class="btn">✖ Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function questionEditForm() {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    // URL: /teacher/quizzes/{quizId}/questions/{questionId}/edit
    const quizId = pathParts[2];
    const questionId = pathParts[4];

    return {
        quizId: quizId,
        questionId: questionId,
        question: null,
        questionText: '',
        option_a: '',
        option_b: '',
        option_c: '',
        option_d: '',
        correct_answer: '',
        loading: true,
        saving: false,
        error: null,
        success: null,
        backUrl: '',

        async loadQuestion() {
            try {
                const query = `
                    query TeacherQuizQuestion($id: ID!) {
                        teacherQuizQuestion(id: $id) {
                            id
                            quiz_id
                            question
                            option_a
                            option_b
                            option_c
                            option_d
                            correct_answer
                            quiz {
                                course_id
                            }
                        }
                    }
                `;
                const result = await GraphQL.query(query, { id: this.questionId });
                if (result && result.teacherQuizQuestion) {
                    this.question = result.teacherQuizQuestion;
                    this.questionText = result.teacherQuizQuestion.question;
                    this.option_a = result.teacherQuizQuestion.option_a;
                    this.option_b = result.teacherQuizQuestion.option_b;
                    this.option_c = result.teacherQuizQuestion.option_c;
                    this.option_d = result.teacherQuizQuestion.option_d;
                    this.correct_answer = result.teacherQuizQuestion.correct_answer;
                    
                    // Set back URL to quiz edit page
                    const courseId = result.teacherQuizQuestion.quiz?.course_id;
                    if (courseId) {
                        this.backUrl = `/teacher/courses/${courseId}/quizzes/${this.quizId}/edit`;
                    } else {
                        this.backUrl = `/teacher/courses`;
                    }
                } else {
                    this.error = 'Soal tidak ditemukan';
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
                    mutation UpdateQuizQuestion(
                        $id: ID!,
                        $question: String!,
                        $option_a: String!,
                        $option_b: String!,
                        $option_c: String!,
                        $option_d: String!,
                        $correct_answer: String!
                    ) {
                        updateQuizQuestion(
                            id: $id,
                            question: $question,
                            option_a: $option_a,
                            option_b: $option_b,
                            option_c: $option_c,
                            option_d: $option_d,
                            correct_answer: $correct_answer
                        ) {
                            id
                        }
                    }
                `;

                await GraphQL.mutate(mutation, {
                    id: this.questionId,
                    question: this.questionText,
                    option_a: this.option_a,
                    option_b: this.option_b,
                    option_c: this.option_c,
                    option_d: this.option_d,
                    correct_answer: this.correct_answer
                });

                this.success = 'Soal berhasil diperbarui!';
            } catch (e) {
                this.error = e.message || 'Gagal menyimpan soal';
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
@endsection

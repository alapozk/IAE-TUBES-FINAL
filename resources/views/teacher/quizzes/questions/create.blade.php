@extends('layouts.app')

@section('content')
<style>
  .page{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .container{max-width:900px;margin:0 auto}
  .back{display:inline-flex;align-items:center;gap:8px;color:#667eea;text-decoration:none;font-weight:700;margin-bottom:16px}
  .card{background:#fff;border-radius:18px;box-shadow:0 15px 40px rgba(0,0,0,.10);padding:26px}
  h2{font-weight:900;margin-bottom:18px;display:flex;align-items:center;gap:10px}
  .question-card{border:2px solid #e5e7eb;border-radius:16px;padding:20px;margin-bottom:20px;position:relative}
  .question-number{font-weight:900;margin-bottom:10px;color:#4f46e5}
  .form-group{margin-bottom:16px}
  label{font-weight:700;margin-bottom:6px;display:block}
  input, textarea, select{width:100%;padding:12px 14px;border-radius:12px;border:1px solid #e5e7eb;font-size:.95rem}
  textarea{min-height:90px}
  input:focus, textarea:focus, select:focus{outline:none;border-color:#667eea;box-shadow:0 0 0 3px rgba(102,126,234,.2)}
  .options{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .answer{background:#f8fafc;border-radius:12px;padding:14px;margin-top:8px}
  .btn{display:inline-flex;align-items:center;gap:8px;padding:12px 18px;border-radius:12px;border:none;font-weight:800;cursor:pointer}
  .btn-primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;box-shadow:0 8px 20px rgba(102,126,234,.35)}
  .btn-primary:disabled{opacity:0.6;cursor:not-allowed}
  .btn-secondary{background:#f3f4f6;color:#111827}
  .btn-danger{background:#fee2e2;color:#991b1b;position:absolute;top:14px;right:14px}
  .actions{display:flex;justify-content:space-between;gap:12px;margin-top:20px;flex-wrap:wrap}
  .alert{padding:12px;border-radius:10px;margin-bottom:16px}
  .alert-error{background:#fee2e2;color:#991b1b}
  .alert-success{background:#d1fae5;color:#065f46}
  @media(max-width:640px){.options{grid-template-columns:1fr}}
</style>

<div class="page" x-data="quizQuestionsForm()">
  <div class="container">
    <a :href="'/teacher/courses/' + courseId" class="back">← Kembali ke Kursus</a>

    <div class="card">
      <h2>📝 Tambah Soal Quiz</h2>

      <!-- Alerts -->
      <div class="alert alert-error" x-show="error" x-text="error"></div>
      <div class="alert alert-success" x-show="success" x-text="success"></div>

      <form @submit.prevent="submitForm">
        <div id="questions-container">
          <template x-for="(q, index) in questions" :key="index">
            <div class="question-card">
              <button type="button" class="btn btn-danger" @click="removeQuestion(index)" x-show="questions.length > 1">✖</button>
              <div class="question-number" x-text="'Soal ' + (index + 1)"></div>

              <div class="form-group">
                <label>Pertanyaan</label>
                <textarea x-model="q.question" required></textarea>
              </div>

              <div class="options">
                <input x-model="q.option_a" placeholder="Opsi A" required>
                <input x-model="q.option_b" placeholder="Opsi B" required>
                <input x-model="q.option_c" placeholder="Opsi C" required>
                <input x-model="q.option_d" placeholder="Opsi D" required>
              </div>

              <div class="answer">
                <label>Jawaban Benar</label>
                <select x-model="q.correct_answer" required>
                  <option value="">-- Pilih --</option>
                  <option value="a">A</option>
                  <option value="b">B</option>
                  <option value="c">C</option>
                  <option value="d">D</option>
                </select>
              </div>
            </div>
          </template>
        </div>

        <div class="actions">
          <button type="button" class="btn btn-secondary" @click="addQuestion()">➕ Tambah Soal</button>
          <button type="submit" class="btn btn-primary" :disabled="loading">
            <span x-text="loading ? '⏳ Menyimpan...' : '💾 Simpan Semua Soal'"></span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function quizQuestionsForm() {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    // URL: /teacher/quizzes/{quizId}/questions/create
    const quizId = pathParts[2];

    return {
        quizId: quizId,
        courseId: '', // Will be loaded
        questions: [{ question: '', option_a: '', option_b: '', option_c: '', option_d: '', correct_answer: '' }],
        loading: false,
        error: null,
        success: null,

        async init() {
            // Get course ID from quiz
            try {
                const query = `query { teacherQuiz(id: "${this.quizId}") { course_id } }`;
                const result = await GraphQL.query(query);
                if (result && result.teacherQuiz) {
                    this.courseId = result.teacherQuiz.course_id;
                }
            } catch (e) {
                console.error('Failed to load quiz info:', e);
            }
        },

        addQuestion() {
            this.questions.push({ question: '', option_a: '', option_b: '', option_c: '', option_d: '', correct_answer: '' });
        },

        removeQuestion(index) {
            if (this.questions.length > 1) {
                this.questions.splice(index, 1);
            }
        },

        async submitForm() {
            this.loading = true;
            this.error = null;

            try {
                const mutation = `
                    mutation CreateQuizQuestions($quiz_id: ID!, $questions: [QuizQuestionInput!]!) {
                        createQuizQuestions(quiz_id: $quiz_id, questions: $questions) {
                            id
                        }
                    }
                `;

                await GraphQL.mutate(mutation, {
                    quiz_id: this.quizId,
                    questions: this.questions.map(q => ({
                        question: q.question,
                        option_a: q.option_a,
                        option_b: q.option_b,
                        option_c: q.option_c,
                        option_d: q.option_d,
                        correct_answer: q.correct_answer
                    }))
                });

                this.success = 'Soal berhasil disimpan!';
                setTimeout(() => {
                    if (this.courseId) {
                        window.location.href = '/teacher/courses/' + this.courseId;
                    } else {
                        window.history.back();
                    }
                }, 1000);
            } catch (e) {
                this.error = e.message || 'Gagal menyimpan soal';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection

@extends('layouts.app')

@section('content')
<style>
    /* ===== Page Layout ===== */
    .quiz-edit-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 32px 20px;
    }

    .quiz-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* ===== Back Link ===== */
    .back-link {
        display: inline-flex;
        gap: 8px;
        align-items: center;
        color: #667eea;
        text-decoration: none;
        font-weight: 700;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    }

    .back-link:hover {
        color: #5a67d8;
        transform: translateX(-4px);
    }

    /* ===== Card ===== */
    .settings-card,
    .questions-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.10);
        padding: 28px;
        margin-bottom: 24px;
        animation: slideUp 0.4s ease-out;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }

    .card-title {
        font-weight: 900;
        font-size: 1.4rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #1f2937;
    }

    .question-count {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    /* ===== Form ===== */
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        margin-bottom: 0;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        font-weight: 800;
        margin-bottom: 8px;
        display: block;
        color: #374151;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        font-weight: 600;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        background: #f9fafb;
    }

    .form-control:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.15);
        background: #fff;
    }

    /* ===== Alerts ===== */
    .alert {
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    .alert-success {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }

    /* ===== Buttons ===== */
    .btn-group {
        display: flex;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 2px solid #f3f4f6;
    }

    .btn {
        display: inline-flex;
        gap: 8px;
        align-items: center;
        padding: 14px 24px;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        background: #f9fafb;
        color: #374151;
        text-decoration: none;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn:hover {
        background: #f3f4f6;
        transform: translateY(-1px);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.35);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.45);
    }

    .btn-primary:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }

    .btn-sm {
        padding: 10px 16px;
        font-size: 0.85rem;
    }

    .btn-danger {
        background: #dc2626;
        color: #fff;
        border: none;
    }

    .btn-danger:hover {
        background: #b91c1c;
    }

    /* ===== Loading ===== */
    .loading-state {
        text-align: center;
        padding: 50px 20px;
        color: #6b7280;
    }

    .loading-spinner {
        font-size: 2rem;
        animation: spin 1s linear infinite;
        display: inline-block;
    }

    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* ===== Question Item ===== */
    .question-item {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 16px;
        transition: all 0.2s ease;
    }

    .question-item:hover {
        border-color: #c7d2fe;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
    }

    .question-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
    }

    .question-number {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 0.95rem;
        flex-shrink: 0;
    }

    .question-content {
        flex: 1;
    }

    .question-text {
        font-weight: 700;
        color: #1f2937;
        font-size: 1rem;
        line-height: 1.5;
        margin-bottom: 12px;
    }

    .question-actions {
        display: flex;
        gap: 8px;
        flex-shrink: 0;
    }

    /* ===== Options Preview ===== */
    .options-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        margin-top: 12px;
    }

    .option-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        background: #fff;
        border-radius: 8px;
        font-size: 0.85rem;
        color: #475569;
    }

    .option-letter {
        font-weight: 800;
        color: #667eea;
    }

    .correct-answer-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 12px;
        padding: 8px 14px;
        background: #d1fae5;
        color: #065f46;
        border-radius: 999px;
        font-weight: 700;
        font-size: 0.85rem;
    }

    /* ===== Empty State ===== */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 16px;
    }

    .empty-title {
        font-weight: 800;
        font-size: 1.1rem;
        color: #374151;
        margin-bottom: 8px;
    }

    .empty-desc {
        color: #6b7280;
        margin-bottom: 20px;
    }

    /* ===== Animation ===== */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ===== Responsive ===== */
    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .options-grid {
            grid-template-columns: 1fr;
        }

        .question-header {
            flex-direction: column;
        }

        .question-actions {
            width: 100%;
            justify-content: flex-end;
        }
    }
</style>

<div class="quiz-edit-page" x-data="quizEditForm()" x-init="loadQuiz()">
    <div class="quiz-container">
        
        <!-- Back Link -->
        <a :href="'/teacher/courses/' + courseId" class="back-link">
            ← Kembali ke Kursus
        </a>

        <!-- Quiz Settings Card -->
        <div class="settings-card">
            <div class="card-header">
                <h2 class="card-title">⚙️ Pengaturan Quiz</h2>
            </div>

            <!-- Loading -->
            <div class="loading-state" x-show="loading">
                <span class="loading-spinner">⏳</span>
                <p>Memuat data quiz...</p>
            </div>

            <!-- Alerts -->
            <div class="alert alert-error" x-show="error">
                ❌ <span x-text="error"></span>
            </div>
            <div class="alert alert-success" x-show="success">
                ✅ <span x-text="success"></span>
            </div>

            <!-- Form -->
            <form @submit.prevent="submitForm" x-show="!loading && quiz">
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">Judul Quiz</label>
                        <input type="text" x-model="title" class="form-control" placeholder="Masukkan judul quiz..." required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Jumlah Attempt</label>
                        <select x-model="max_attempt" class="form-control">
                            <option value="1">1x Attempt (Sekali saja)</option>
                            <option value="2">2x Attempt</option>
                            <option value="3">3x Attempt</option>
                            <option value="5">5x Attempt</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Durasi (menit)</label>
                        <input type="number" x-model="duration" class="form-control" min="1" placeholder="30">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Deadline Quiz</label>
                        <input type="datetime-local" x-model="deadline" class="form-control">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Akses Review</label>
                        <select x-model="show_review" class="form-control">
                            <option value="1">✅ Boleh lihat review</option>
                            <option value="0">🚫 Tidak boleh lihat</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label class="form-label">Status Quiz</label>
                        <select x-model="is_published" class="form-control">
                            <option value="1">👁️ Dibuka untuk siswa</option>
                            <option value="0">🔒 Disembunyikan</option>
                        </select>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary" :disabled="saving">
                        <span x-text="saving ? '⏳ Menyimpan...' : '💾 Simpan Perubahan'"></span>
                    </button>
                    <a :href="'/teacher/courses/' + courseId" class="btn">✖ Batal</a>
                </div>
            </form>
        </div>

        <!-- Questions Card -->
        <div class="questions-card" x-show="!loading && quiz">
            <div class="card-header">
                <h2 class="card-title">📝 Daftar Soal</h2>
                <div style="display: flex; gap: 12px; align-items: center;">
                    <span class="question-count" x-text="questions.length + ' Soal'"></span>
                    <a :href="'/teacher/quizzes/' + quizId + '/questions/create'" class="btn btn-primary btn-sm">
                        ➕ Tambah Soal
                    </a>
                </div>
            </div>

            <!-- Questions List -->
            <div x-show="questions.length > 0">
                <template x-for="(q, index) in questions" :key="q.id">
                    <div class="question-item">
                        <div class="question-header">
                            <span class="question-number" x-text="index + 1"></span>
                            <div class="question-content">
                                <div class="question-text" x-text="q.question"></div>
                                <div class="options-grid">
                                    <div class="option-item">
                                        <span class="option-letter">A.</span>
                                        <span x-text="q.option_a"></span>
                                    </div>
                                    <div class="option-item">
                                        <span class="option-letter">B.</span>
                                        <span x-text="q.option_b"></span>
                                    </div>
                                    <div class="option-item">
                                        <span class="option-letter">C.</span>
                                        <span x-text="q.option_c"></span>
                                    </div>
                                    <div class="option-item">
                                        <span class="option-letter">D.</span>
                                        <span x-text="q.option_d"></span>
                                    </div>
                                </div>
                                <div class="correct-answer-badge">
                                    ✓ Jawaban Benar: <span x-text="q.correct_answer"></span>
                                </div>
                            </div>
                            <div class="question-actions">
                                <a :href="'/teacher/quizzes/' + quizId + '/questions/' + q.id + '/edit'" class="btn btn-sm">
                                    ✏️ Edit
                                </a>
                                <button class="btn btn-sm btn-danger" @click="deleteQuestion(q.id)">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Empty State -->
            <div class="empty-state" x-show="questions.length === 0">
                <div class="empty-icon">📋</div>
                <p class="empty-title">Belum Ada Soal</p>
                <p class="empty-desc">Quiz ini belum memiliki soal. Tambahkan soal untuk memulai.</p>
                <a :href="'/teacher/quizzes/' + quizId + '/questions/create'" class="btn btn-primary">
                    ➕ Tambah Soal Pertama
                </a>
            </div>
        </div>

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

@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-6" x-data="quizAttemptPage()" x-init="loadQuizData()">

    <!-- Loading -->
    <template x-if="loading">
        <div class="text-center py-20">
            <p class="text-gray-500">Memuat soal...</p>
        </div>
    </template>

    <!-- Error -->
    <template x-if="error">
        <div class="bg-red-100 text-red-700 p-4 rounded-xl">
            <p x-text="error"></p>
            <a href="/student/my-courses" class="text-indigo-600 underline mt-2 inline-block">Kembali ke Kursus</a>
        </div>
    </template>

    <!-- Quiz Content -->
    <template x-if="!loading && !error && quiz">
        <div>
            {{-- Quiz Header --}}
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white mb-8">
                <h1 class="text-2xl font-bold" x-text="quiz.title"></h1>
                <p class="text-indigo-100 mt-1">
                    Soal <span x-text="currentIndex + 1"></span> dari <span x-text="questions.length"></span>
                </p>
                <div class="mt-3 bg-white/20 rounded-full h-2">
                    <div class="bg-white h-2 rounded-full transition-all"
                         :style="'width: ' + ((currentIndex + 1) / questions.length * 100) + '%'"></div>
                </div>
            </div>

            {{-- Question Card --}}
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-6" x-text="currentQuestion.question"></h2>

                <div class="space-y-3">
                    <template x-for="opt in ['a', 'b', 'c', 'd']" :key="opt">
                        <label x-show="currentQuestion['option_' + opt]"
                               class="flex items-center p-4 rounded-xl border border-gray-200 hover:border-indigo-400 hover:bg-indigo-50 cursor-pointer transition"
                               :class="selectedAnswer === opt ? 'border-indigo-500 bg-indigo-50' : ''">
                            <input type="radio"
                                   :name="'answer_' + currentQuestion.id"
                                   :value="opt"
                                   x-model="selectedAnswer"
                                   class="w-5 h-5 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                            <span class="ml-4 text-gray-700">
                                <span class="font-semibold text-indigo-600" x-text="opt.toUpperCase() + '.'"></span>
                                <span x-text="currentQuestion['option_' + opt]"></span>
                            </span>
                        </label>
                    </template>
                </div>

                {{-- Navigation Buttons --}}
                <div class="flex justify-between mt-8 pt-6 border-t">
                    <button x-show="currentIndex > 0"
                            @click="prevQuestion()"
                            class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-xl transition">
                        ← Sebelumnya
                    </button>
                    <div x-show="currentIndex === 0"></div>

                    <button x-show="currentIndex < questions.length - 1"
                            @click="nextQuestion()"
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition">
                        Selanjutnya →
                    </button>
                    <button x-show="currentIndex === questions.length - 1"
                            @click="finishQuiz()"
                            :disabled="submitting"
                            class="px-8 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition">
                        <span x-show="!submitting">✓ Selesai & Lihat Hasil</span>
                        <span x-show="submitting">Memproses...</span>
                    </button>
                </div>
            </div>

            {{-- Question Navigator --}}
            <div class="bg-white rounded-2xl shadow p-4">
                <p class="text-xs text-gray-500 mb-3">Navigasi Soal:</p>
                <div class="flex flex-wrap gap-2">
                    <template x-for="(q, i) in questions" :key="q.id">
                        <button @click="goToQuestion(i)"
                                class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-medium transition"
                                :class="i === currentIndex ? 'bg-indigo-600 text-white' : (answers[q.id] ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-600')"
                                x-text="i + 1"></button>
                    </template>
                </div>
            </div>
        </div>
    </template>

</div>

<script>
function quizAttemptPage() {
    // Extract attemptId from URL: /student/quiz/{quizId}/attempt/{attemptId}
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    const attemptId = pathParts[pathParts.length - 1];

    return {
        loading: true,
        error: null,
        quiz: null,
        attempt: null,
        questions: [],
        currentIndex: 0,
        selectedAnswer: null,
        answers: {},
        submitting: false,

        get currentQuestion() {
            return this.questions[this.currentIndex] || {};
        },

        async loadQuizData() {
            try {
                const query = `
                    query GetAttempt($id: ID!) {
                        studentQuizAttempt(id: $id) {
                            id
                            score
                            quiz {
                                id
                                title
                                questions {
                                    id
                                    question
                                    option_a
                                    option_b
                                    option_c
                                    option_d
                                }
                            }
                            answers {
                                quiz_question_id
                                selected_answer
                            }
                        }
                    }
                `;
                const result = await GraphQL.query(query, { id: attemptId });
                
                if (!result.studentQuizAttempt) {
                    this.error = 'Attempt tidak ditemukan';
                    return;
                }

                this.attempt = result.studentQuizAttempt;
                this.quiz = result.studentQuizAttempt.quiz;
                this.questions = result.studentQuizAttempt.quiz.questions || [];

                // Load existing answers
                if (result.studentQuizAttempt.answers) {
                    result.studentQuizAttempt.answers.forEach(a => {
                        this.answers[a.quiz_question_id] = a.selected_answer;
                    });
                }

                // Set initial selected answer
                if (this.questions.length > 0) {
                    this.selectedAnswer = this.answers[this.questions[0].id] || null;
                }
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        async saveAnswer() {
            if (!this.selectedAnswer) return;
            
            const questionId = this.currentQuestion.id;
            this.answers[questionId] = this.selectedAnswer;

            try {
                await GraphQL.mutate(`
                    mutation AnswerQuiz($attempt_id: ID!, $question_id: ID!, $selected_answer: String!) {
                        answerQuiz(attempt_id: $attempt_id, question_id: $question_id, selected_answer: $selected_answer) {
                            id
                        }
                    }
                `, {
                    attempt_id: this.attempt.id,
                    question_id: questionId,
                    selected_answer: this.selectedAnswer
                });
            } catch (e) {
                console.error('Failed to save answer:', e);
            }
        },

        async nextQuestion() {
            await this.saveAnswer();
            this.currentIndex++;
            this.selectedAnswer = this.answers[this.questions[this.currentIndex].id] || null;
        },

        async prevQuestion() {
            await this.saveAnswer();
            this.currentIndex--;
            this.selectedAnswer = this.answers[this.questions[this.currentIndex].id] || null;
        },

        goToQuestion(index) {
            this.saveAnswer();
            this.currentIndex = index;
            this.selectedAnswer = this.answers[this.questions[index].id] || null;
        },

        async finishQuiz() {
            await this.saveAnswer();
            this.submitting = true;

            try {
                const result = await GraphQL.mutate(`
                    mutation FinishQuiz($attempt_id: ID!) {
                        finishQuiz(attempt_id: $attempt_id) {
                            id
                            score
                        }
                    }
                `, { attempt_id: this.attempt.id });

                // Redirect to result page
                window.location.href = '/student/quiz/' + this.quiz.id + '/result/' + this.attempt.id;
            } catch (e) {
                alert('Error: ' + e.message);
                this.submitting = false;
            }
        }
    }
}
</script>
@endsection

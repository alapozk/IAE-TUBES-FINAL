@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-6" x-data="quizResultPage()" x-init="loadResult()">

    <!-- Loading State -->
    <template x-if="loading">
        <div class="text-center py-20">
            <p class="text-gray-500">Memuat hasil quiz...</p>
        </div>
    </template>

    <!-- Error State -->
    <template x-if="error">
        <div class="bg-red-100 text-red-700 p-4 rounded-xl">
            <p x-text="error"></p>
            <a href="/student/my-courses" class="text-indigo-600 underline mt-2 inline-block">Kembali ke Kursus</a>
        </div>
    </template>

    <!-- Result Content -->
    <template x-if="!loading && !error && attempt">
        <div>
            {{-- Result Header --}}
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 rounded-2xl p-8 text-white mb-8 text-center">
                <div class="text-6xl mb-4">🎉</div>
                <h1 class="text-2xl font-bold" x-text="'Quiz: ' + attempt.quiz.title"></h1>
                <p class="text-emerald-100 mt-2">Quiz telah selesai!</p>
            </div>

            {{-- Score Card --}}
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 text-center">
                <h2 class="text-lg font-semibold text-gray-600 mb-4">Skor Anda</h2>
                <div class="text-6xl font-bold mb-4"
                     :class="scorePercentage >= 70 ? 'text-emerald-600' : (scorePercentage >= 50 ? 'text-amber-500' : 'text-red-500')">
                    <span x-text="attempt.score"></span>/<span x-text="totalQuestions"></span>
                </div>
                <div class="text-2xl font-medium text-gray-500" x-text="scorePercentage + '%'"></div>
                <div class="mt-4 text-sm text-gray-400">
                    <span x-text="correctAnswers"></span> jawaban benar dari <span x-text="totalQuestions"></span> soal
                </div>
            </div>

            {{-- Answer Review --}}
            <div class="bg-white rounded-2xl shadow p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">📋 Tinjauan Jawaban</h3>
                <div class="space-y-4">
                    <template x-for="(question, index) in attempt.quiz.questions" :key="question.id">
                        <div class="p-4 rounded-xl"
                             :class="getAnswerClass(question.id)">
                            <div class="flex items-start gap-3">
                                <span class="flex-shrink-0 w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold"
                                      :class="isCorrect(question.id) ? 'bg-emerald-500 text-white' : 'bg-red-500 text-white'"
                                      x-text="index + 1"></span>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-800" x-text="question.question"></p>
                                    <p class="text-sm mt-2">
                                        <span class="text-gray-500">Jawaban Anda: </span>
                                        <span :class="isCorrect(question.id) ? 'text-emerald-600 font-semibold' : 'text-red-600'"
                                              x-text="getSelectedAnswer(question.id) || '-'"></span>
                                    </p>
                                    <p class="text-sm" x-show="!isCorrect(question.id)">
                                        <span class="text-gray-500">Jawaban Benar: </span>
                                        <span class="text-emerald-600 font-semibold" x-text="question.correct_answer.toUpperCase()"></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            {{-- Back Button --}}
            <div class="text-center">
                <a href="/student/my-courses"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition">
                    ← Kembali ke Kursus
                </a>
            </div>
        </div>
    </template>

</div>

<script>
function quizResultPage() {
    return {
        loading: true,
        error: null,
        attempt: null,
        attemptId: '{{ $attemptId }}',
        answersMap: {},

        get totalQuestions() {
            return this.attempt?.quiz?.questions?.length || 0;
        },

        get correctAnswers() {
            return this.attempt?.score || 0;
        },

        get scorePercentage() {
            if (this.totalQuestions === 0) return 0;
            return Math.round((this.correctAnswers / this.totalQuestions) * 100);
        },

        async loadResult() {
            try {
                const query = `
                    query GetAttemptResult($id: ID!) {
                        studentQuizAttempt(id: $id) {
                            id
                            score
                            quiz {
                                id
                                title
                                questions {
                                    id
                                    question
                                    correct_answer
                                }
                            }
                            answers {
                                quiz_question_id
                                selected_answer
                            }
                        }
                    }
                `;
                const result = await GraphQL.query(query, { id: this.attemptId });

                if (!result.studentQuizAttempt) {
                    this.error = 'Hasil quiz tidak ditemukan';
                    return;
                }

                this.attempt = result.studentQuizAttempt;

                // Build answers map
                if (this.attempt.answers) {
                    this.attempt.answers.forEach(a => {
                        this.answersMap[a.quiz_question_id] = a.selected_answer;
                    });
                }
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        getSelectedAnswer(questionId) {
            return this.answersMap[questionId]?.toUpperCase();
        },

        isCorrect(questionId) {
            const question = this.attempt?.quiz?.questions?.find(q => q.id === questionId);
            if (!question) return false;
            return this.answersMap[questionId] === question.correct_answer;
        },

        getAnswerClass(questionId) {
            return this.isCorrect(questionId) ? 'bg-emerald-50 border border-emerald-200' : 'bg-red-50 border border-red-200';
        }
    }
}
</script>
@endsection

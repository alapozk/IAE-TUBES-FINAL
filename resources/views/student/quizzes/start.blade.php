@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-6" x-data="quizStartPage()" x-init="startQuiz()">

    <!-- Loading State -->
    <template x-if="loading">
        <div class="text-center py-20">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-white mb-8">
                <h1 class="text-2xl font-bold mb-4">Memulai Quiz...</h1>
                <p class="text-indigo-100">Mohon tunggu sebentar...</p>
            </div>
        </div>
    </template>

    <!-- Error State -->
    <template x-if="error">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="text-center">
                <div class="text-5xl mb-4">❌</div>
                <h2 class="text-xl font-bold text-gray-800 mb-2">Tidak Dapat Memulai Quiz</h2>
                <p class="text-gray-600 mb-6" x-text="error"></p>
                <a href="/student/my-courses" class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-medium">
                    ← Kembali ke Kursus
                </a>
            </div>
        </div>
    </template>

</div>

<script>
function quizStartPage() {
    return {
        loading: true,
        error: null,
        quizId: '{{ $quizId }}',

        async startQuiz() {
            try {
                const mutation = `
                    mutation StartQuiz($quiz_id: ID!) {
                        startQuiz(quiz_id: $quiz_id) {
                            id
                        }
                    }
                `;

                const result = await GraphQL.mutate(mutation, { quiz_id: this.quizId });

                if (result && result.startQuiz && result.startQuiz.id) {
                    // Redirect to attempt page
                    window.location.href = '/student/quiz/' + this.quizId + '/attempt/' + result.startQuiz.id;
                } else {
                    this.error = 'Gagal memulai quiz. Silakan coba lagi.';
                    this.loading = false;
                }
            } catch (e) {
                this.error = e.message || 'Gagal memulai quiz.';
                this.loading = false;
            }
        }
    }
}
</script>
@endsection

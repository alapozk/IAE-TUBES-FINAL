@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-6" x-data="courseDetailPage()" x-init="loadCourse()">

    <!-- Loading State -->
    <div x-show="loading" class="text-center py-20">
        <p class="text-gray-500">Memuat data kursus...</p>
    </div>

    <!-- Error State -->
    <div x-show="error && !loading" class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl mb-6">
        <span x-text="error"></span>
        <a href="/student/my-course" class="block mt-2 text-indigo-600 underline">← Kembali ke Kursus Saya</a>
    </div>

    <!-- Content -->
    <div x-show="!loading && course && !error">
        {{-- Course Header --}}
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-6 text-white mb-8">
            <h1 class="text-3xl font-bold" x-text="course?.title || ''"></h1>
            <p class="text-indigo-100 mt-2" x-text="'Kode: ' + (course?.code || '-')"></p>
            <p class="text-indigo-200 text-sm mt-1" x-text="'Pengajar: ' + (course?.teacher?.name || '-')"></p>
        </div>

        {{-- Progress Card --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Total Tugas</p>
                <p class="text-3xl font-bold text-indigo-600 mt-1" x-text="course?.assignments?.length || 0"></p>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Materi</p>
                <p class="text-3xl font-bold text-emerald-600 mt-1" x-text="course?.materials?.length || 0"></p>
            </div>
            <div class="bg-white rounded-2xl shadow p-6">
                <p class="text-xs text-gray-500 uppercase tracking-wider">Quiz</p>
                <p class="text-3xl font-bold text-purple-600 mt-1" x-text="getPublishedQuizzes().length"></p>
            </div>
        </div>

        {{-- Materials Section --}}
        <div class="bg-white rounded-2xl shadow mb-8">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-white to-indigo-50">
                <h2 class="text-lg font-semibold text-gray-800">📚 Materi</h2>
            </div>
            <div class="p-6">
                <template x-for="material in (course?.materials || [])" :key="material.id">
                    <a :href="'/student/course/' + course.id + '/materials/' + material.id"
                       class="flex items-center gap-4 p-4 hover:bg-gray-50 rounded-xl transition mb-2">
                        <div class="w-10 h-10 rounded-lg bg-indigo-100 flex items-center justify-center">
                            <span class="text-xl">📄</span>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-900" x-text="material.title"></h3>
                            <p class="text-xs text-gray-500" x-text="formatDate(material.created_at)"></p>
                        </div>
                    </a>
                </template>
                <p x-show="!course?.materials?.length" class="text-gray-500 text-center py-4">Belum ada materi</p>
            </div>
        </div>

        {{-- Assignments Section --}}
        <div class="bg-white rounded-2xl shadow mb-8">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-white to-amber-50">
                <h2 class="text-lg font-semibold text-gray-800">📝 Tugas</h2>
            </div>
            <div class="p-6">
                <template x-for="assignment in (course?.assignments || [])" :key="assignment.id">
                    <a :href="'/student/course/' + course.id + '/assignments/' + assignment.id"
                       class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition mb-2">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                                <span class="text-xl">📋</span>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900" x-text="assignment.title"></h3>
                                <p class="text-xs text-gray-500" x-text="'Batas: ' + formatDate(assignment.due_at)"></p>
                            </div>
                        </div>
                        <span class="text-indigo-600 text-sm">Lihat →</span>
                    </a>
                </template>
                <p x-show="!course?.assignments?.length" class="text-gray-500 text-center py-4">Belum ada tugas</p>
            </div>
        </div>

        {{-- Quizzes Section --}}
        <div class="bg-white rounded-2xl shadow">
            <div class="px-6 py-4 border-b bg-gradient-to-r from-white to-emerald-50">
                <h2 class="text-lg font-semibold text-gray-800">🎯 Quiz</h2>
            </div>
            <div class="p-6">
                <template x-for="quiz in getPublishedQuizzes()" :key="quiz.id">
                    <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition mb-2 border border-gray-100">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                                <span class="text-xl">🎯</span>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900" x-text="quiz.title"></h3>
                                <p class="text-xs text-gray-500">
                                    <span x-text="'Durasi: ' + (quiz.duration || '-') + ' menit'"></span> •
                                    <span x-text="'Kesempatan: ' + (quiz.my_attempts || 0) + '/' + quiz.max_attempt"></span>
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <!-- Show Score if attempted -->
                            <template x-if="quiz.my_score !== null && quiz.my_score !== undefined">
                                <span class="px-3 py-1 bg-green-100 text-green-700 text-sm font-bold rounded-lg"
                                      x-text="'Skor: ' + quiz.my_score"></span>
                            </template>
                            <!-- Button: Mulai atau Habis -->
                            <template x-if="canAttemptQuiz(quiz)">
                                <a :href="'/student/quiz/' + quiz.id + '/start'"
                                   class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition">
                                    Mulai Quiz
                                </a>
                            </template>
                            <template x-if="!canAttemptQuiz(quiz)">
                                <span class="px-4 py-2 bg-gray-300 text-gray-600 text-sm font-medium rounded-lg cursor-not-allowed">
                                    Kesempatan Habis
                                </span>
                            </template>
                        </div>
                    </div>
                </template>
                <p x-show="getPublishedQuizzes().length === 0" class="text-gray-500 text-center py-4">Belum ada quiz yang tersedia</p>
            </div>
        </div>

        {{-- Back Button --}}
        <div class="mt-8">
            <a href="/student/my-courses"
               class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium">
                ← Kembali ke Kursus Saya
            </a>
        </div>
    </div>
</div>

<script>
function courseDetailPage() {
    return {
        course: null,
        loading: true,
        error: null,

        getPublishedQuizzes() {
            if (!this.course || !this.course.quizzes) return [];
            return this.course.quizzes.filter(q => q.is_published);
        },

        canAttemptQuiz(quiz) {
            const attempts = quiz.my_attempts || 0;
            return attempts < quiz.max_attempt;
        },

        async loadCourse() {
            // Extract course ID from URL: /student/course/{id}
            const pathParts = window.location.pathname.split('/').filter(Boolean);
            const courseId = pathParts[pathParts.length - 1];
            console.log('Loading course:', courseId);
            
            try {
                const query = `
                    query StudentCourse($id: ID!) {
                        studentCourse(id: $id) {
                            id
                            title
                            code
                            teacher {
                                id
                                name
                            }
                            materials {
                                id
                                title
                                created_at
                            }
                            assignments {
                                id
                                title
                                due_at
                            }
                            quizzes {
                                id
                                title
                                duration
                                max_attempt
                                is_published
                                deadline
                                my_attempts
                                my_score
                            }
                        }
                    }
                `;
                console.log('Sending GraphQL query...');
                const result = await GraphQL.query(query, { id: courseId });
                console.log('GraphQL result:', result);
                
                if (result && result.studentCourse) {
                    this.course = result.studentCourse;
                } else {
                    this.error = 'Kursus tidak ditemukan atau Anda tidak terdaftar.';
                }
            } catch (e) {
                console.error('GraphQL error:', e);
                this.error = e.message || 'Gagal memuat data kursus.';
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            try {
                const date = new Date(dateStr);
                return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            } catch (e) {
                return dateStr;
            }
        }
    }
}
</script>
@endsection

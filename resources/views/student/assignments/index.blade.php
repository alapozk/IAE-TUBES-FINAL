@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-6" x-data="assignmentsPage()" x-init="loadAssignments()">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-md bg-indigo-500 flex items-center justify-center">
            <span class="text-white text-lg">📂</span>
        </div>
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Daftar Tugas</h1>
            <p class="text-sm text-gray-600">
                Berikut adalah tugas-tugas Anda yang sedang aktif di berbagai kursus.
            </p>
        </div>
    </div>

    <!-- Loading -->
    <div x-show="loading" class="text-center py-10">
        <p class="text-gray-500">Memuat tugas...</p>
    </div>

    <!-- Error -->
    <div x-show="error" class="bg-red-100 text-red-700 p-4 rounded-xl mb-6" x-text="error"></div>

    <!-- Content -->
    <div x-show="!loading && !error">
        {{-- Quick Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
            <div class="bg-white rounded-2xl shadow p-4">
                <p class="text-xs text-gray-500">Total Tugas</p>
                <p class="text-2xl font-semibold text-indigo-600" x-text="assignments.length"></p>
            </div>
            <div class="bg-white rounded-2xl shadow p-4">
                <p class="text-xs text-gray-500">Belum Dikerjakan</p>
                <p class="text-2xl font-semibold text-rose-500" x-text="getPendingCount()"></p>
            </div>
            <div class="bg-white rounded-2xl shadow p-4">
                <p class="text-xs text-gray-500">Sudah Dikumpulkan</p>
                <p class="text-2xl font-semibold text-emerald-500" x-text="getSubmittedCount()"></p>
            </div>
        </div>

        {{-- SECTION 1: Kartu Tugas Aktif --}}
        <div class="mb-10" x-show="assignments.length > 0">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-800">Tugas Aktif</h2>
                <span class="text-[11px] px-3 py-1 rounded-full bg-indigo-50 text-indigo-600" x-text="assignments.length + ' tugas terdaftar'"></span>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <template x-for="task in assignments" :key="task.id">
                    <a :href="'/student/courses/' + task.course.id + '/assignments/' + task.id"
                       class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col gap-1 hover:shadow-md transition">
                        <h3 class="font-semibold text-gray-800 text-sm" x-text="task.title"></h3>
                        <p class="text-xs text-gray-500" x-text="'📘 ' + (task.course?.title || '-')"></p>
                        <p class="text-xs text-gray-500" x-text="'⏰ ' + formatDate(task.due_at)"></p>
                        <div class="mt-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-medium"
                                  :class="task.hasSubmission ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'"
                                  x-text="task.hasSubmission ? 'Sudah Dikumpulkan' : 'Belum Dikerjakan'"></span>
                        </div>
                    </a>
                </template>
            </div>
        </div>

        {{-- Empty State --}}
        <div x-show="assignments.length === 0" class="text-center py-10 text-gray-500">
            Belum ada tugas yang terdaftar.
        </div>
    </div>
</div>

<script>
function assignmentsPage() {
    return {
        assignments: [],
        loading: true,
        error: null,

        getPendingCount() {
            return this.assignments.filter(a => !a.hasSubmission).length;
        },

        getSubmittedCount() {
            return this.assignments.filter(a => a.hasSubmission).length;
        },

        async loadAssignments() {
            try {
                const query = `
                    query {
                        studentAssignments {
                            id
                            title
                            due_at
                            course { id title }
                            submissions { id student_id }
                        }
                    }
                `;
                const result = await GraphQL.query(query);
                const currentUserId = document.querySelector('meta[name="user-id"]')?.content;
                this.assignments = (result.studentAssignments || []).map(a => ({
                    ...a,
                    hasSubmission: a.submissions?.some(s => s.student_id == currentUserId) || false
                }));
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateStr) {
            if (!dateStr) return 'Tidak ada batas';
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        }
    }
}
</script>
@endsection

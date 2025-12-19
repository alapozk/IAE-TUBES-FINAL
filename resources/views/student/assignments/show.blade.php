@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-8 px-6" x-data="assignmentShowPage()" x-init="loadAssignment()">

    <!-- Loading -->
    <div x-show="loading" class="text-center py-10">
        <p class="text-gray-500">Memuat tugas...</p>
    </div>

    <!-- Error -->
    <div x-show="error" class="bg-red-100 text-red-700 p-4 rounded-xl" x-text="error"></div>

    <!-- Content -->
    <div x-show="!loading && assignment">
        {{-- Back Button --}}
        <a :href="'/student/courses/' + courseId"
           class="inline-flex items-center gap-2 text-indigo-600 hover:text-indigo-800 font-medium mb-6">
            ← Kembali ke Kursus
        </a>

        {{-- Assignment Card --}}
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 p-6 text-white">
                <h1 class="text-2xl font-bold" x-text="assignment.title"></h1>
                <p class="text-amber-100 mt-1" x-text="assignment.course?.title || '-'"></p>
            </div>

            <div class="p-6">
                {{-- Due Date --}}
                <div class="flex items-center gap-3 mb-6 p-4 bg-gray-50 rounded-xl">
                    <span class="text-2xl">⏰</span>
                    <div>
                        <p class="text-xs text-gray-500 uppercase tracking-wider">Batas Pengumpulan</p>
                        <p class="font-semibold text-gray-800" x-text="formatDate(assignment.due_at)"></p>
                    </div>
                </div>

                {{-- Instructions --}}
                <div class="mb-6" x-show="assignment.instructions">
                    <h2 class="text-lg font-semibold text-gray-800 mb-3">📝 Instruksi</h2>
                    <div class="prose prose-sm max-w-none text-gray-600" x-html="nl2br(assignment.instructions)"></div>
                </div>

                {{-- Max Points --}}
                <div class="mb-6 p-4 bg-indigo-50 rounded-xl" x-show="assignment.max_points">
                    <p class="text-sm text-indigo-600">
                        <span class="font-semibold">Poin Maksimal:</span> <span x-text="assignment.max_points"></span>
                    </p>
                </div>

                {{-- Already Submitted Section --}}
                <div class="mt-8 pt-6 border-t" x-show="mySubmission">
                    <div class="bg-green-50 border border-green-200 rounded-xl p-5">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="text-2xl">✅</span>
                            <h3 class="text-lg font-bold text-green-800">Tugas Sudah Dikumpulkan!</h3>
                        </div>
                        
                        <div class="bg-white rounded-lg p-4 border border-green-100">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <span class="text-xl" x-text="getFileIcon(mySubmission.extension)">📄</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-800" x-text="mySubmission.title || 'File Submission'"></p>
                                    <p class="text-sm text-gray-500">
                                        <span x-text="formatFileSize(mySubmission.size)"></span>
                                        <span class="mx-1">•</span>
                                        <span x-text="mySubmission.extension?.toUpperCase()"></span>
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Dikumpulkan: <span x-text="formatDate(mySubmission.created_at)"></span>
                                    </p>
                                </div>
                                <a :href="'/storage/' + mySubmission.file_path" 
                                   target="_blank"
                                   class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                                    📥 Download
                                </a>
                            </div>
                        </div>

                        {{-- Grade if available --}}
                        <div class="mt-4 p-3 bg-white rounded-lg border border-green-100" x-show="mySubmission.grade">
                            <p class="text-sm text-gray-600">
                                <span class="font-semibold">Nilai:</span> 
                                <span class="text-lg font-bold text-green-600" x-text="mySubmission.grade"></span>
                                <span x-show="assignment.max_points">/ <span x-text="assignment.max_points"></span></span>
                            </p>
                            <p class="text-sm text-gray-500 mt-1" x-show="mySubmission.feedback">
                                <span class="font-semibold">Feedback:</span> <span x-text="mySubmission.feedback"></span>
                            </p>
                        </div>

                        {{-- Option to resubmit --}}
                        <div class="mt-4 text-center">
                            <a :href="'/student/courses/' + courseId + '/assignments/' + assignment.id + '/submit'"
                               class="text-sm text-orange-600 hover:text-orange-800 font-medium underline">
                                🔄 Upload Ulang (Mengganti file sebelumnya)
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Submit Button (only show if not submitted) --}}
                <div class="mt-8 pt-6 border-t" x-show="!mySubmission">
                    <a :href="'/student/courses/' + courseId + '/assignments/' + assignment.id + '/submit'"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl transition">
                        📤 Upload Tugas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function assignmentShowPage() {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    // URL: /student/courses/{courseId}/assignments/{assignmentId} or /student/course/{courseId}/assignments/{assignmentId}
    const courseId = pathParts[2];
    const assignmentId = pathParts[4];

    return {
        assignment: null,
        mySubmission: null,
        loading: true,
        error: null,
        courseId: courseId,
        assignmentId: assignmentId,

        async loadAssignment() {
            try {
                const query = `
                    query StudentAssignment($id: ID!) {
                        studentAssignment(id: $id) {
                            id
                            title
                            instructions
                            due_at
                            max_points
                            course { id title }
                            submissions {
                                id
                                title
                                file_path
                                size
                                extension
                                grade
                                feedback
                                created_at
                                student_id
                            }
                        }
                    }
                `;
                const result = await GraphQL.query(query, { id: this.assignmentId });
                this.assignment = result.studentAssignment;
                
                // Find current user's submission
                const currentUserId = document.querySelector('meta[name="user-id"]')?.content;
                if (this.assignment?.submissions && currentUserId) {
                    this.mySubmission = this.assignment.submissions.find(s => s.student_id == currentUserId);
                }
                // Fallback: if only one submission exists, assume it's the user's
                if (!this.mySubmission && this.assignment?.submissions?.length) {
                    this.mySubmission = this.assignment.submissions[0];
                }
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateStr) {
            if (!dateStr) return 'Tidak ada batas waktu';
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' });
        },

        formatFileSize(bytes) {
            if (!bytes) return '0 KB';
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(1024));
            return Math.round(bytes / Math.pow(1024, i)) + ' ' + sizes[i];
        },

        getFileIcon(ext) {
            const icons = {
                'pdf': '📕',
                'doc': '📘',
                'docx': '📘',
                'ppt': '📙',
                'pptx': '📙',
                'xls': '📗',
                'xlsx': '📗',
                'zip': '🗜️',
                'rar': '🗜️'
            };
            return icons[ext?.toLowerCase()] || '📄';
        },

        nl2br(text) {
            if (!text) return '';
            return text.replace(/\n/g, '<br>');
        }
    }
}
</script>
@endsection

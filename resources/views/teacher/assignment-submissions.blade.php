@extends('layouts.app')

@section('content')
<style>
    /* ===== Page Layout ===== */
    .submissions-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 32px 20px;
    }

    .submissions-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    /* ===== Back Button ===== */
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
    .submissions-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.10);
        padding: 28px;
        animation: slideUp 0.4s ease-out;
    }

    .card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f3f4f6;
    }

    .card-title {
        font-weight: 900;
        font-size: 1.5rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
        color: #1f2937;
    }

    .submission-count {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    /* ===== Loading State ===== */
    .loading-state {
        text-align: center;
        padding: 60px 20px;
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

    /* ===== Table ===== */
    .submissions-table {
        width: 100%;
        border-collapse: collapse;
    }

    .submissions-table th {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        font-weight: 800;
        font-size: 0.8rem;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 14px 16px;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
    }

    .submissions-table td {
        padding: 16px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .submissions-table tbody tr {
        transition: all 0.2s ease;
    }

    .submissions-table tbody tr:hover {
        background: #f8fafc;
    }

    /* ===== Student Info ===== */
    .student-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .student-avatar {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-weight: 800;
        font-size: 0.9rem;
    }

    .student-name {
        font-weight: 700;
        color: #1f2937;
    }

    /* ===== File Link ===== */
    .file-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 14px;
        background: #f1f5f9;
        border-radius: 10px;
        text-decoration: none;
        color: #475569;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.2s ease;
    }

    .file-link:hover {
        background: #e2e8f0;
        color: #1e40af;
    }

    /* ===== Badge ===== */
    .grade-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 800;
        font-size: 0.8rem;
        border-radius: 999px;
        padding: 6px 14px;
    }

    .grade-badge.graded {
        background: #d1fae5;
        color: #065f46;
    }

    .grade-badge.pending {
        background: #fef3c7;
        color: #92400e;
    }

    /* ===== Action Buttons ===== */
    .action-btns {
        display: flex;
        gap: 8px;
    }

    .btn-action {
        padding: 8px 14px;
        border-radius: 10px;
        border: none;
        font-weight: 700;
        text-decoration: none;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-view {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
    }

    .btn-view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
    }

    .btn-download {
        background: #f1f5f9;
        color: #475569;
    }

    .btn-download:hover {
        background: #e2e8f0;
    }

    /* ===== Empty State ===== */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        font-size: 4rem;
        margin-bottom: 16px;
    }

    .empty-title {
        font-weight: 800;
        font-size: 1.2rem;
        color: #374151;
        margin-bottom: 8px;
    }

    .empty-desc {
        color: #6b7280;
    }

    /* ===== Date ===== */
    .date-text {
        color: #6b7280;
        font-size: 0.9rem;
    }

    /* ===== Animation ===== */
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="submissions-page" x-data="submissionsPage()" x-init="loadSubmissions()">
    <div class="submissions-container">
        
        <!-- Back Link -->
        <a href="/teacher/courses/{{ $course }}" class="back-link">
            ← Kembali ke Kursus
        </a>

        <!-- Main Card -->
        <div class="submissions-card">
            
            <!-- Header -->
            <div class="card-header">
                <h2 class="card-title">
                    📋 Pengumpulan Tugas
                </h2>
                <span class="submission-count" x-show="!loading" x-text="submissions.length + ' Submission'"></span>
            </div>

            <!-- Loading State -->
            <div class="loading-state" x-show="loading">
                <span class="loading-spinner">⏳</span>
                <p>Memuat data pengumpulan...</p>
            </div>

            <!-- Table -->
            <table class="submissions-table" x-show="!loading && submissions.length > 0">
                <thead>
                    <tr>
                        <th style="width: 50px">No</th>
                        <th>Siswa</th>
                        <th>File</th>
                        <th>Waktu Submit</th>
                        <th>Nilai</th>
                        <th style="width: 180px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-for="(sub, index) in submissions" :key="sub.id">
                        <tr>
                            <!-- No -->
                            <td x-text="index + 1"></td>
                            
                            <!-- Student Info -->
                            <td>
                                <div class="student-info">
                                    <div class="student-avatar" x-text="getInitial(sub.student_name)"></div>
                                    <span class="student-name" x-text="sub.student_name || 'Siswa #' + sub.student_id"></span>
                                </div>
                            </td>
                            
                            <!-- File -->
                            <td>
                                <a :href="'/storage/' + sub.file_path" target="_blank" class="file-link">
                                    📄 <span x-text="sub.title || 'File Tugas'"></span>
                                </a>
                            </td>
                            
                            <!-- Date -->
                            <td>
                                <span class="date-text" x-text="formatDate(sub.created_at)"></span>
                            </td>
                            
                            <!-- Grade -->
                            <td>
                                <span class="grade-badge" 
                                      :class="sub.grade !== null ? 'graded' : 'pending'"
                                      x-text="sub.grade !== null ? '✓ ' + sub.grade : '⏳ Belum dinilai'">
                                </span>
                            </td>
                            
                            <!-- Actions -->
                            <td>
                                <div class="action-btns">
                                    <a :href="'/storage/' + sub.file_path" target="_blank" class="btn-action btn-view">
                                        👁️ Lihat
                                    </a>
                                    <a :href="'/storage/' + sub.file_path" download class="btn-action btn-download">
                                        📥
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>

            <!-- Empty State -->
            <div class="empty-state" x-show="!loading && submissions.length === 0">
                <div class="empty-icon">📭</div>
                <p class="empty-title">Belum Ada Pengumpulan</p>
                <p class="empty-desc">Siswa belum ada yang mengumpulkan tugas ini.</p>
            </div>

        </div>
    </div>
</div>

<script>
function submissionsPage() {
    const assignmentId = {{ $assignment }};
    
    return {
        submissions: [],
        loading: true,

        async loadSubmissions() {
            try {
                const query = `
                    query TeacherAssignmentSubmissions($assignment_id: ID!) {
                        teacherAssignmentSubmissions(assignment_id: $assignment_id) {
                            id
                            student_id
                            student_name
                            title
                            file_path
                            grade
                            feedback
                            created_at
                        }
                    }
                `;
                const result = await GraphQL.query(query, { assignment_id: assignmentId });
                this.submissions = result.teacherAssignmentSubmissions || [];
            } catch (e) {
                console.error('Error loading submissions:', e);
            } finally {
                this.loading = false;
            }
        },

        getInitial(name) {
            if (!name) return '?';
            return name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            return new Date(dateStr).toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric', 
                hour: '2-digit', 
                minute: '2-digit' 
            });
        }
    }
}
</script>
@endsection

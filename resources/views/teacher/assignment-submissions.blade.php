@extends('layouts.app')

@section('content')
<style>
  .page{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .container{max-width:1000px;margin:0 auto}
  .back{display:inline-flex;gap:8px;align-items:center;color:#667eea;text-decoration:none;font-weight:700;margin-bottom:14px}
  .card{background:#fff;border-radius:18px;box-shadow:0 15px 40px rgba(0,0,0,.10);padding:22px;margin-top:14px}
  .card h2{font-weight:900;margin:0 0 16px;display:flex;align-items:center;gap:10px}
  .loading{text-align:center;padding:40px;color:#6b7280}
  .table{width:100%;border-collapse:collapse}
  .table th,.table td{padding:12px 14px;text-align:left;border-bottom:1px solid #e5e7eb}
  .table th{background:#f8fafc;font-weight:800;font-size:.85rem;color:#4b5563;text-transform:uppercase}
  .table tr:hover{background:#f9fafb}
  .badge{display:inline-flex;align-items:center;gap:6px;font-weight:800;font-size:.75rem;border-radius:999px;padding:5px 10px}
  .badge-success{background:#d1fae5;color:#065f46}
  .badge-warning{background:#fef3c7;color:#92400e}
  .btn-xs{padding:6px 12px;border-radius:8px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:700;text-decoration:none;font-size:.85rem}
  .btn-xs:hover{background:#f3f4f6}
  .btn-primary{background:#4f46e5;color:#fff;border:none}
  .empty{text-align:center;padding:40px;color:#6b7280}
</style>

<div class="page" x-data="submissionsPage()" x-init="loadSubmissions()">
  <div class="container">
    <a href="/teacher/courses/{{ $course }}" class="back">← Kembali ke Kursus</a>

    <div class="card">
      <h2>📋 Pengumpulan Tugas</h2>
      
      <!-- Loading -->
      <div x-show="loading" class="loading">Memuat data...</div>

      <!-- Table -->
      <table class="table" x-show="!loading && submissions.length > 0">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>File</th>
            <th>Tanggal Submit</th>
            <th>Nilai</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <template x-for="(sub, index) in submissions" :key="sub.id">
            <tr>
              <td x-text="index + 1"></td>
              <td x-text="sub.student_name || 'Siswa #' + sub.student_id"></td>
              <td>
                <a :href="'/storage/' + sub.file_path" target="_blank" class="btn-xs" x-text="sub.title || 'File'"></a>
              </td>
              <td x-text="formatDate(sub.created_at)"></td>
              <td>
                <span class="badge" :class="sub.grade !== null ? 'badge-success' : 'badge-warning'"
                      x-text="sub.grade !== null ? sub.grade : 'Belum dinilai'"></span>
              </td>
              <td>
                <a :href="'/storage/' + sub.file_path" target="_blank" class="btn-xs btn-primary">👁️ Lihat</a>
              </td>
            </tr>
          </template>
        </tbody>
      </table>

      <!-- Empty -->
      <div class="empty" x-show="!loading && submissions.length === 0">
        <p>Belum ada siswa yang mengumpulkan tugas ini.</p>
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
                console.error('Error:', e);
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            return new Date(dateStr).toLocaleDateString('id-ID', { 
                day: 'numeric', month: 'short', year: 'numeric', 
                hour: '2-digit', minute: '2-digit' 
            });
        }
    }
}
</script>
@endsection

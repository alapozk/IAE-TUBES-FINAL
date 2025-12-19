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
  .empty{text-align:center;padding:40px;color:#6b7280}
</style>

<div class="page" x-data="quizAttemptsPage()" x-init="loadAttempts()">
  <div class="container">
    <a href="/teacher/courses/{{ $course }}" class="back">← Kembali ke Kursus</a>

    <div class="card">
      <h2>📋 Hasil Quiz</h2>
      
      <!-- Loading -->
      <div x-show="loading" class="loading">Memuat data...</div>

      <!-- Table -->
      <table class="table" x-show="!loading && attempts.length > 0">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Skor</th>
            <th>Status</th>
            <th>Waktu Mulai</th>
            <th>Waktu Selesai</th>
          </tr>
        </thead>
        <tbody>
          <template x-for="(attempt, index) in attempts" :key="attempt.id">
            <tr>
              <td x-text="index + 1"></td>
              <td x-text="attempt.student_name || 'Siswa #' + attempt.student_id"></td>
              <td>
                <span class="badge" :class="attempt.score !== null ? 'badge-success' : 'badge-warning'"
                      x-text="attempt.score !== null ? attempt.score : '-'"></span>
              </td>
              <td>
                <span class="badge" :class="attempt.status === 'completed' ? 'badge-success' : 'badge-warning'"
                      x-text="attempt.status === 'completed' ? 'Selesai' : 'Dalam Proses'"></span>
              </td>
              <td x-text="formatDate(attempt.started_at)"></td>
              <td x-text="attempt.finished_at ? formatDate(attempt.finished_at) : '-'"></td>
            </tr>
          </template>
        </tbody>
      </table>

      <!-- Empty -->
      <div class="empty" x-show="!loading && attempts.length === 0">
        <p>Belum ada siswa yang mengerjakan quiz ini.</p>
      </div>
    </div>
  </div>
</div>

<script>
function quizAttemptsPage() {
    const quizId = {{ $quiz }};
    
    return {
        attempts: [],
        loading: true,

        async loadAttempts() {
            try {
                const query = `
                    query TeacherQuizAttempts($quiz_id: ID!) {
                        teacherQuizAttempts(quiz_id: $quiz_id) {
                            id
                            student_id
                            student_name
                            score
                            status
                            started_at
                            finished_at
                        }
                    }
                `;
                const result = await GraphQL.query(query, { quiz_id: quizId });
                this.attempts = result.teacherQuizAttempts || [];
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

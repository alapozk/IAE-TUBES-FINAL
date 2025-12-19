@extends('layouts.app')

@section('content')
<style>
  .wrap{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .container{max-width:860px;margin:0 auto}
  .card{background:#fff;border-radius:16px;box-shadow:0 12px 30px rgba(0,0,0,.10);padding:20px}
  .row{display:grid;grid-template-columns:1fr 1fr;gap:14px}
  .row-1{display:grid;grid-template-columns:1fr;gap:14px}
  label{font-weight:800;margin-bottom:6px;display:block}
  input[type="text"], input[type="datetime-local"], textarea, select {
    width:100%; padding:12px; border:1px solid #e5e7eb; border-radius:10px; background:#f9fafb
  }
  textarea{min-height:160px; resize:vertical}
  .actions{display:flex;gap:10px;flex-wrap:wrap;margin-top:14px}
  .btn{padding:10px 14px;border-radius:10px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:800;text-decoration:none;cursor:pointer}
  .primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none}
  .primary:disabled{opacity:0.6;cursor:not-allowed}
  .alert{padding:12px;border-radius:10px;margin-bottom:12px}
  .alert-error{background:#fee2e2;color:#991b1b}
  .alert-success{background:#d1fae5;color:#065f46}
  .loading{text-align:center;padding:20px}
</style>

<div class="wrap" x-data="assignmentEditForm()" x-init="loadAssignment()">
  <div class="container">
    <a :href="'/teacher/courses/' + courseId" class="btn">← Kembali</a>

    <div class="card" style="margin-top:12px">
      <h2 style="font-weight:900;margin:0 0 10px">✏️ Edit Tugas</h2>

      <!-- Loading -->
      <div class="loading" x-show="loading">Memuat data tugas...</div>

      <!-- Alerts -->
      <div class="alert alert-error" x-show="error" x-text="error"></div>
      <div class="alert alert-success" x-show="success" x-text="success"></div>

      <form @submit.prevent="submitForm" x-show="!loading && assignment">
        <div class="row-1">
          <div>
            <label>Judul</label>
            <input type="text" x-model="title" required>
          </div>

          <div>
            <label>Instruksi / Detail Tugas</label>
            <textarea x-model="instructions"></textarea>
          </div>
        </div>

        <div class="row" style="margin-top:6px">
          <div>
            <label>Deadline (opsional)</label>
            <input type="datetime-local" x-model="due_at">
          </div>

          <div>
            <label>Mode Pengumpulan</label>
            <select x-model="submission_mode" required>
              <option value="text">Teks saja</option>
              <option value="file">File saja (PDF/DOC/ZIP)</option>
              <option value="both">Teks + File</option>
            </select>
          </div>
        </div>

        <div class="row" style="margin-top:6px">
          <div>
            <label>Skor Maks (opsional)</label>
            <input type="text" x-model="max_points" inputmode="numeric">
          </div>
          <div>
            <label>Status</label>
            <select x-model="status">
              <option value="active">Aktif</option>
              <option value="archived">Arsip</option>
            </select>
          </div>
        </div>

        <div class="actions">
          <button class="btn primary" type="submit" :disabled="saving">
            <span x-text="saving ? '⏳ Menyimpan...' : '💾 Simpan Perubahan'"></span>
          </button>
          <a class="btn" :href="'/teacher/courses/' + courseId">Batal</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function assignmentEditForm() {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    // URL: /teacher/courses/{courseId}/assignments/{assignmentId}/edit
    const courseId = pathParts[2];
    const assignmentId = pathParts[4];

    return {
        courseId: courseId,
        assignmentId: assignmentId,
        assignment: null,
        title: '',
        instructions: '',
        due_at: '',
        submission_mode: 'file',
        max_points: '',
        status: 'active',
        loading: true,
        saving: false,
        error: null,
        success: null,

        async loadAssignment() {
            try {
                const query = `
                    query TeacherAssignment($id: ID!) {
                        teacherAssignment(id: $id) {
                            id
                            title
                            instructions
                            due_at
                            submission_mode
                            max_points
                            course { id }
                        }
                    }
                `;
                const result = await GraphQL.query(query, { id: this.assignmentId });
                if (result && result.teacherAssignment) {
                    this.assignment = result.teacherAssignment;
                    this.title = result.teacherAssignment.title || '';
                    this.instructions = result.teacherAssignment.instructions || '';
                    this.due_at = result.teacherAssignment.due_at ? result.teacherAssignment.due_at.substring(0, 16) : '';
                    this.submission_mode = result.teacherAssignment.submission_mode || 'file';
                    this.max_points = result.teacherAssignment.max_points || '';
                } else {
                    this.error = 'Tugas tidak ditemukan';
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
                    mutation UpdateAssignment(
                        $id: ID!,
                        $title: String!,
                        $instructions: String,
                        $due_at: DateTime,
                        $submission_mode: String!,
                        $max_points: Int
                    ) {
                        updateAssignment(
                            id: $id,
                            title: $title,
                            instructions: $instructions,
                            due_at: $due_at,
                            submission_mode: $submission_mode,
                            max_points: $max_points
                        ) {
                            id
                            title
                        }
                    }
                `;

                await GraphQL.mutate(mutation, {
                    id: this.assignmentId,
                    title: this.title,
                    instructions: this.instructions || null,
                    due_at: this.due_at ? this.due_at + ':00' : null,
                    submission_mode: this.submission_mode,
                    max_points: this.max_points ? parseInt(this.max_points) : null
                });

                this.success = 'Tugas berhasil diperbarui!';
            } catch (e) {
                this.error = e.message || 'Gagal menyimpan tugas';
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
@endsection

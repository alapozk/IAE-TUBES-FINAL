@extends('layouts.app')

@section('content')
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  .edit-wrapper { background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); min-height: 100vh; padding: 40px 20px; }
  .edit-container { max-width: 700px; margin: 0 auto; }
  .edit-header { margin-bottom: 30px; }
  .edit-header h1 { font-size: 2rem; font-weight: 900; color: #1a202c; display: flex; align-items: center; gap: 12px; }
  .header-icon { font-size: 2rem; }
  .form-card { background: white; border-radius: 15px; padding: 35px; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08); }
  .alert { padding: 16px; border-radius: 10px; margin-bottom: 25px; display: flex; gap: 12px; }
  .alert-success { background: #c6f6d5; color: #22543d; border: 1px solid #9ae6b4; }
  .alert-error { background: #fed7d7; color: #742a2a; border: 1px solid #fc8181; }
  .form-group { margin-bottom: 25px; }
  .form-label { display: block; font-weight: 700; color: #2d3748; margin-bottom: 10px; font-size: 0.95rem; }
  .form-input, .form-select { width: 100%; padding: 12px 16px; border: 2px solid #e2e8f0; border-radius: 10px; font-size: 0.95rem; transition: all 0.3s ease; font-family: inherit; }
  .form-input:focus, .form-select:focus { outline: none; border-color: #667eea; box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1); }
  .form-actions { display: flex; gap: 12px; margin-top: 30px; padding-top: 30px; border-top: 2px solid #e2e8f0; }
  .btn { padding: 12px 28px; border: none; border-radius: 10px; font-weight: 700; font-size: 0.95rem; cursor: pointer; transition: all 0.3s ease; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
  .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4); flex: 1; justify-content: center; }
  .btn-primary:hover { transform: translateY(-2px); }
  .btn-primary:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
  .btn-secondary { background: #f7fafc; color: #4a5568; border: 2px solid #cbd5e0; flex: 1; justify-content: center; }
  .btn-secondary:hover { background: #edf2f7; }
  .loading { text-align: center; padding: 40px; }
  @media (max-width: 768px) { .form-actions { flex-direction: column; } .btn { width: 100%; } }
</style>

<div class="edit-wrapper" x-data="editCourseForm()" x-init="loadCourse()">
  <div class="edit-container">
    <div class="edit-header">
      <h1><span class="header-icon">✏️</span> Edit Kursus</h1>
    </div>

    <!-- Loading -->
    <div class="form-card" x-show="loading">
      <div class="loading">Memuat data...</div>
    </div>

    <!-- Form -->
    <div class="form-card" x-show="!loading && course">
      <!-- Success Alert -->
      <div class="alert alert-success" x-show="success" x-text="success"></div>
      
      <!-- Error Alert -->
      <div class="alert alert-error" x-show="error" x-text="error"></div>

      <form @submit.prevent="submitForm">
        <div class="form-group">
          <label for="title" class="form-label">Judul Kursus</label>
          <input type="text" id="title" x-model="title" class="form-input" placeholder="Masukkan judul kursus" required />
        </div>

        <div class="form-group">
          <label for="code" class="form-label">Kode Kursus</label>
          <input type="text" id="code" x-model="code" class="form-input" placeholder="Contoh: PROG101" required />
        </div>

        <div class="form-group">
          <label for="status" class="form-label">Status</label>
          <select id="status" x-model="status" class="form-select" required>
            <option value="draft">📝 Draft</option>
            <option value="published">✓ Dipublikasikan</option>
          </select>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary" :disabled="saving">
            <span x-show="!saving">💾</span>
            <span x-text="saving ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
          </button>
          <a :href="'/teacher/courses/' + courseId" class="btn btn-secondary">← Kembali ke Kursus</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function editCourseForm() {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    const courseId = pathParts[pathParts.length - 2] || pathParts[pathParts.length - 1];
    
    return {
        course: null,
        title: '',
        code: '',
        status: 'draft',
        loading: true,
        saving: false,
        error: null,
        success: null,
        courseId: courseId,

        async loadCourse() {
            try {
                const query = `
                    query TeacherCourse($id: ID!) {
                        teacherCourse(id: $id) { id title code status }
                    }
                `;
                const result = await GraphQL.query(query, { id: this.courseId });
                if (result.teacherCourse) {
                    this.course = result.teacherCourse;
                    this.title = result.teacherCourse.title;
                    this.code = result.teacherCourse.code;
                    this.status = result.teacherCourse.status;
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
                    mutation UpdateCourse($id: ID!, $title: String!, $code: String!, $status: String) {
                        updateCourse(id: $id, title: $title, code: $code, status: $status) {
                            id title code status
                        }
                    }
                `;
                await GraphQL.mutate(mutation, {
                    id: this.courseId,
                    title: this.title,
                    code: this.code,
                    status: this.status
                });
                this.success = 'Kursus berhasil diperbarui!';
            } catch (e) {
                this.error = e.message;
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
@endsection
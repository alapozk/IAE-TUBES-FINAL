@extends('layouts.app')
@section('content')
<style>
  .wrap{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .box{max-width:700px;margin:0 auto;background:#fff;border-radius:16px;padding:24px;box-shadow:0 12px 30px rgba(0,0,0,.1)}
  .label{font-weight:800;margin-bottom:6px;display:block}
  .input,.file{width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:10px}
  .row{display:flex;gap:12px;margin-top:16px}
  .btn{padding:12px 18px;border-radius:10px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:800;text-decoration:none;cursor:pointer}
  .primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none}
  .primary:disabled{opacity:0.6;cursor:not-allowed}
  .alert{padding:12px;border-radius:10px;margin-bottom:12px}
  .alert-error{background:#fee2e2;border:1px solid #fecaca;color:#991b1b}
  .alert-success{background:#d1fae5;border:1px solid #a7f3d0;color:#065f46}
</style>

<div class="wrap" x-data="submissionForm()">
  <div class="box">
    <h2 style="font-weight:900;margin-bottom:12px">📤 Upload Tugas</h2>

    <!-- Error Alert -->
    <div class="alert alert-error" x-show="error" x-text="error"></div>
    
    <!-- Success Alert -->
    <div class="alert alert-success" x-show="success" x-text="success"></div>

    <form @submit.prevent="submitForm">
      <label class="label" style="margin-top:12px">File (PDF / PPT / Video)</label>
      <input class="file" type="file" @change="handleFile" accept=".pdf,.ppt,.pptx,.doc,.docx,video/*" required>

      <div class="row">
        <button class="btn primary" type="submit" :disabled="loading">
          <span x-text="loading ? '⏳ Mengupload...' : '💾 Kirim Tugas'"></span>
        </button>
        <a class="btn" :href="'/student/courses/' + courseId">← Batal</a>
      </div>
    </form>
  </div>
</div>

<script>
function submissionForm() {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    // URL: /student/courses/{courseId}/assignments/{assignmentId}/submit
    const courseId = pathParts[2];
    const assignmentId = pathParts[4];
    
    return {
        file: null,
        loading: false,
        error: null,
        success: null,
        courseId: courseId,
        assignmentId: assignmentId,

        handleFile(e) {
            this.file = e.target.files[0];
        },

        async submitForm() {
            if (!this.file) {
                this.error = 'Pilih file terlebih dahulu';
                return;
            }

            this.loading = true;
            this.error = null;

            try {
                const mutation = `
                    mutation SubmitAssignment($course_id: ID!, $assignment_id: ID!, $file: Upload!) {
                        submitAssignment(course_id: $course_id, assignment_id: $assignment_id, file: $file) {
                            id
                        }
                    }
                `;

                await GraphQL.upload(mutation, {
                    course_id: this.courseId,
                    assignment_id: this.assignmentId
                }, this.file, 'file');

                this.success = 'Tugas berhasil dikumpulkan!';
                setTimeout(() => {
                    window.location.href = '/student/courses/' + this.courseId;
                }, 1500);
            } catch (e) {
                this.error = e.message || 'Gagal mengirim tugas';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection

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
  .alert-error{background:#fee2e2;border:1px solid #fecaca}
  .alert-success{background:#d1fae5;border:1px solid #a7f3d0}
</style>

<div class="wrap" x-data="materialCreateForm()">
  <div class="box">
    <h2 style="font-weight:900;margin-bottom:12px">➕ Tambah Materi</h2>

    <!-- Error Alert -->
    <div class="alert alert-error" x-show="error" x-text="error"></div>
    
    <!-- Success Alert -->
    <div class="alert alert-success" x-show="success" x-text="success"></div>

    <form @submit.prevent="submitForm">
      <label class="label">Judul</label>
      <input class="input" x-model="title" placeholder="Contoh: Pertemuan 1 - Pendahuluan" required>

      <label class="label" style="margin-top:12px">Deskripsi / Penjelasan (opsional)</label>
      <textarea class="input" x-model="description" rows="4" placeholder="Tulis penjelasan materi di sini..."></textarea>

      <label class="label" style="margin-top:12px">File (PDF / PPT / Video)</label>
      <input class="file" type="file" @change="handleFile" accept=".pdf,.ppt,.pptx,video/*" required>

      <div class="row">
        <button class="btn primary" type="submit" :disabled="loading">
          <span x-text="loading ? '⏳ Mengupload...' : '💾 Simpan'"></span>
        </button>
        <a class="btn" :href="'/teacher/courses/' + courseId">← Batal</a>
      </div>
    </form>
  </div>
</div>

<script>
function materialCreateForm() {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    // URL: /teacher/courses/{courseId}/materials/create
    const courseId = pathParts[2];
    
    return {
        title: '',
        description: '',
        file: null,
        loading: false,
        error: null,
        success: null,
        courseId: courseId,

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
                    mutation CreateMaterial($course_id: ID!, $title: String!, $description: String, $file: Upload!) {
                        createMaterial(course_id: $course_id, title: $title, description: $description, file: $file) {
                            id
                            title
                        }
                    }
                `;

                await GraphQL.upload(mutation, {
                    course_id: this.courseId,
                    title: this.title,
                    description: this.description
                }, this.file, 'file');

                this.success = 'Materi berhasil ditambahkan!';
                setTimeout(() => {
                    window.location.href = '/teacher/courses/' + this.courseId;
                }, 1000);
            } catch (e) {
                this.error = e.message || 'Gagal menyimpan materi';
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection

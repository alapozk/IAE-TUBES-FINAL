@extends('layouts.app')

@section('content')
<style>
  .wrap{background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);min-height:100vh;padding:32px 20px}
  .box{max-width:700px;margin:0 auto;background:#fff;border-radius:16px;padding:24px;box-shadow:0 12px 30px rgba(0,0,0,.1)}
  .label{font-weight:800;margin-bottom:6px;display:block}
  .input,.file{width:100%;padding:12px;border:2px solid #e5e7eb;border-radius:10px}
  .row{display:flex;gap:12px;margin-top:16px;flex-wrap:wrap}
  .btn{padding:12px 18px;border-radius:10px;border:1px solid #e5e7eb;background:#f9fafb;font-weight:800;text-decoration:none;cursor:pointer}
  .primary{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border:none}
  .primary:disabled{opacity:0.6;cursor:not-allowed}
  .meta{color:#6b7280;font-size:.9rem;margin-top:8px}
  .alert{padding:12px;border-radius:10px;margin-bottom:12px}
  .alert-error{background:#fee2e2;color:#991b1b}
  .alert-success{background:#d1fae5;color:#065f46}
</style>

<div class="wrap" x-data="materialEditForm()" x-init="loadMaterial()">
  <div class="box">
    <h2 style="font-weight:900;margin-bottom:12px">✏️ Edit Materi</h2>

    <!-- Loading -->
    <div x-show="loading" style="text-align:center;padding:20px">Memuat data...</div>

    <!-- Alerts -->
    <div class="alert alert-error" x-show="error" x-text="error"></div>
    <div class="alert alert-success" x-show="success" x-text="success"></div>

    <!-- Form -->
    <form @submit.prevent="submitForm" x-show="!loading && material">
      <label class="label">Judul</label>
      <input class="input" x-model="title" placeholder="Judul materi" required>

      <label class="label" style="margin-top:12px">Deskripsi / Penjelasan (opsional)</label>
      <textarea class="input" x-model="description" rows="4" placeholder="Tulis penjelasan materi di sini..."></textarea>

      <label class="label" style="margin-top:12px">Ganti File (opsional)</label>
      <input class="file" type="file" @change="handleFile" accept=".pdf,.ppt,.pptx,video/*">
      <div class="meta" x-show="material">
        Saat ini: <span x-text="(material?.extension || '').toUpperCase()"></span>,
        <span x-text="formatSize(material?.size || 0)"></span>
      </div>

      <div class="row">
        <button class="btn primary" type="submit" :disabled="saving">
          <span x-text="saving ? '⏳ Menyimpan...' : '💾 Simpan Perubahan'"></span>
        </button>
        <a class="btn" :href="'/teacher/courses/' + courseId">← Batal</a>
      </div>
    </form>
  </div>
</div>

<script>
function materialEditForm() {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    // URL: /teacher/courses/{courseId}/materials/{materialId}/edit
    const courseId = pathParts[2];
    const materialId = pathParts[4];

    return {
        courseId: courseId,
        materialId: materialId,
        material: null,
        title: '',
        description: '',
        file: null,
        loading: true,
        saving: false,
        error: null,
        success: null,

        async loadMaterial() {
            try {
                const query = `
                    query TeacherMaterial($id: ID!) {
                        teacherMaterial(id: $id) {
                            id
                            title
                            description
                            extension
                            size
                        }
                    }
                `;
                const result = await GraphQL.query(query, { id: this.materialId });
                if (result && result.teacherMaterial) {
                    this.material = result.teacherMaterial;
                    this.title = result.teacherMaterial.title;
                    this.description = result.teacherMaterial.description || '';
                } else {
                    this.error = 'Materi tidak ditemukan';
                }
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        handleFile(e) {
            this.file = e.target.files[0];
        },

        formatSize(bytes) {
            if (!bytes) return '0 KB';
            return Math.round(bytes / 1024) + ' KB';
        },

        async submitForm() {
            this.saving = true;
            this.error = null;

            try {
                if (this.file) {
                    // With file upload
                    const mutation = `
                        mutation UpdateMaterial($id: ID!, $title: String!, $description: String, $file: Upload) {
                            updateMaterial(id: $id, title: $title, description: $description, file: $file) {
                                id title
                            }
                        }
                    `;
                    await GraphQL.upload(mutation, {
                        id: this.materialId,
                        title: this.title,
                        description: this.description || null
                    }, this.file, 'file');
                } else {
                    // Without file
                    const mutation = `
                        mutation UpdateMaterial($id: ID!, $title: String!, $description: String) {
                            updateMaterial(id: $id, title: $title, description: $description) {
                                id title
                            }
                        }
                    `;
                    await GraphQL.mutate(mutation, {
                        id: this.materialId,
                        title: this.title,
                        description: this.description || null
                    });
                }

                this.success = 'Materi berhasil diperbarui!';
                setTimeout(() => {
                    window.location.href = '/teacher/courses/' + this.courseId;
                }, 1000);
            } catch (e) {
                this.error = e.message || 'Gagal menyimpan materi';
            } finally {
                this.saving = false;
            }
        }
    }
}
</script>
@endsection

@extends('layouts.app')

@section('content')
<style>
    /* ===== Page Layout ===== */
    .material-page {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        min-height: 100vh;
        padding: 32px 20px;
    }

    .material-container {
        max-width: 900px;
        margin: 0 auto;
    }

    /* ===== Back Link ===== */
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
    .material-card {
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

    /* ===== Loading ===== */
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

    /* ===== Content ===== */
    .material-info {
        margin-bottom: 24px;
    }

    .info-label {
        font-weight: 700;
        color: #6b7280;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 6px;
    }

    .info-value {
        font-size: 1rem;
        color: #1f2937;
        line-height: 1.6;
    }

    /* ===== File Preview ===== */
    .file-preview {
        background: #f8fafc;
        border-radius: 16px;
        padding: 20px;
        margin-top: 20px;
        border: 2px solid #e2e8f0;
    }

    .file-embed {
        width: 100%;
        height: 500px;
        border: none;
        border-radius: 12px;
    }

    /* ===== Buttons ===== */
    .btn-group {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }

    .btn {
        display: inline-flex;
        gap: 8px;
        align-items: center;
        padding: 14px 24px;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        background: #f9fafb;
        color: #374151;
        text-decoration: none;
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn:hover {
        background: #f3f4f6;
        transform: translateY(-1px);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }

    .btn-danger {
        background: #dc2626;
        color: #fff;
        border: none;
    }

    .alert {
        padding: 14px 18px;
        border-radius: 12px;
        margin-bottom: 16px;
        font-weight: 600;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="material-page" x-data="materialShowPage()" x-init="loadMaterial()">
    <div class="material-container">
        
        <!-- Back Link -->
        <a :href="'/teacher/courses/' + courseId" class="back-link">
            ← Kembali ke Kursus
        </a>

        <!-- Main Card -->
        <div class="material-card">
            
            <!-- Header -->
            <div class="card-header">
                <h2 class="card-title">📄 Detail Materi</h2>
            </div>

            <!-- Loading -->
            <div class="loading-state" x-show="loading">
                <span class="loading-spinner">⏳</span>
                <p>Memuat data materi...</p>
            </div>

            <!-- Error -->
            <div class="alert alert-error" x-show="error" x-text="error"></div>

            <!-- Content -->
            <div x-show="!loading && material">
                <div class="material-info">
                    <div class="info-label">Judul</div>
                    <div class="info-value" x-text="material.title"></div>
                </div>

                <div class="material-info" x-show="material.description">
                    <div class="info-label">Deskripsi</div>
                    <div class="info-value" x-text="material.description"></div>
                </div>

                <!-- File Preview -->
                <div class="file-preview" x-show="material.file_path">
                    <div class="info-label">File Materi</div>
                    <iframe class="file-embed" :src="'/storage/' + material.file_path" x-show="isPdf()"></iframe>
                    <video class="file-embed" controls x-show="isVideo()">
                        <source :src="'/storage/' + material.file_path" type="video/mp4">
                    </video>
                </div>

                <!-- Buttons -->
                <div class="btn-group">
                    <a :href="'/storage/' + material.file_path" target="_blank" class="btn btn-primary">
                        📥 Download File
                    </a>
                    <a :href="'/teacher/courses/' + courseId + '/materials/' + materialId + '/edit'" class="btn">
                        ✏️ Edit Materi
                    </a>
                    <button class="btn btn-danger" @click="deleteMaterial()">
                        🗑️ Hapus
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
function materialShowPage() {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    const courseId = pathParts[2];
    const materialId = pathParts[4];

    return {
        courseId: courseId,
        materialId: materialId,
        material: null,
        loading: true,
        error: null,

        async loadMaterial() {
            try {
                const query = `
                    query TeacherMaterial($id: ID!) {
                        teacherMaterial(id: $id) {
                            id
                            title
                            description
                            file_path
                        }
                    }
                `;
                const result = await GraphQL.query(query, { id: this.materialId });
                if (result && result.teacherMaterial) {
                    this.material = result.teacherMaterial;
                } else {
                    this.error = 'Materi tidak ditemukan';
                }
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        isPdf() {
            return this.material?.file_path?.toLowerCase().endsWith('.pdf');
        },

        isVideo() {
            const path = this.material?.file_path?.toLowerCase() || '';
            return path.endsWith('.mp4') || path.endsWith('.webm') || path.endsWith('.mov');
        },

        async deleteMaterial() {
            if (!confirm('Hapus materi ini?')) return;
            try {
                await GraphQL.mutate(`mutation { deleteMaterial(id: "${this.materialId}") }`);
                window.location.href = '/teacher/courses/' + this.courseId;
            } catch (e) {
                alert(e.message);
            }
        }
    }
}
</script>
@endsection

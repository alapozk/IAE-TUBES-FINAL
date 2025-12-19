@extends('layouts.app')

@section('content')
<style>
    .material-wrapper{min-height:100vh;background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);padding:40px 20px}
    .material-container{max-width:900px;margin:0 auto}
    .material-card{background:#fff;border-radius:16px;box-shadow:0 10px 40px rgba(0,0,0,.12);overflow:hidden}
    .material-header{background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);padding:30px;color:#fff}
    .material-header h1{font-size:1.8rem;font-weight:900;margin-bottom:8px}
    .material-header p{opacity:0.9}
    .material-body{padding:30px}
    .material-meta{display:flex;flex-wrap:wrap;gap:20px;margin-bottom:24px}
    .meta-item{display:flex;align-items:center;gap:10px;padding:12px 16px;background:#f7fafc;border-radius:10px}
    .meta-icon{font-size:1.5rem}
    .meta-label{font-size:.75rem;color:#718096;text-transform:uppercase}
    .meta-value{font-weight:700;color:#2d3748}
    .material-description{background:#f7fafc;border-radius:12px;padding:20px;margin-bottom:24px}
    .material-description h3{font-weight:800;margin-bottom:10px;color:#2d3748}
    .material-description p{color:#4a5568;line-height:1.8}
    .file-preview{border:2px dashed #e2e8f0;border-radius:12px;padding:30px;text-align:center}
    .file-icon{font-size:4rem;margin-bottom:16px}
    .file-name{font-weight:700;color:#2d3748;margin-bottom:8px}
    .file-info{color:#718096;font-size:.9rem}
    .download-btn{display:inline-flex;align-items:center;gap:8px;padding:14px 28px;background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);color:#fff;border-radius:10px;text-decoration:none;font-weight:700;margin-top:20px;box-shadow:0 4px 15px rgba(102,126,234,.4)}
    .download-btn:hover{transform:translateY(-2px);box-shadow:0 6px 25px rgba(102,126,234,.6)}
    .back-link{display:inline-flex;align-items:center;gap:8px;color:#667eea;text-decoration:none;font-weight:600;margin-bottom:20px}
    .back-link:hover{transform:translateX(-5px)}
    .pdf-viewer{width:100%;height:600px;border:none;border-radius:12px;margin-top:20px}
    .video-player{width:100%;border-radius:12px;margin-top:20px}
    .loading{text-align:center;padding:50px}
    .error{background:#fee2e2;color:#991b1b;padding:16px;border-radius:12px}
</style>

<div class="material-wrapper" x-data="materialShowPage()" x-init="loadMaterial()">
    <div class="material-container">
        <!-- Back Link -->
        <a :href="'/student/courses/' + courseId" class="back-link">← Kembali ke Kursus</a>

        <!-- Loading -->
        <div class="loading" x-show="loading">
            <p>Memuat materi...</p>
        </div>

        <!-- Error -->
        <div class="error" x-show="error && !loading" x-text="error"></div>

        <!-- Content -->
        <div class="material-card" x-show="!loading && material">
            <div class="material-header">
                <h1 x-text="material?.title"></h1>
                <p x-text="material?.course?.title"></p>
            </div>

            <div class="material-body">
                <!-- Meta Info -->
                <div class="material-meta">
                    <div class="meta-item">
                        <span class="meta-icon" x-text="getFileIcon(material?.extension)">📄</span>
                        <div>
                            <p class="meta-label">Tipe File</p>
                            <p class="meta-value" x-text="(material?.extension || 'File').toUpperCase()"></p>
                        </div>
                    </div>
                    <div class="meta-item">
                        <span class="meta-icon">📦</span>
                        <div>
                            <p class="meta-label">Ukuran</p>
                            <p class="meta-value" x-text="formatFileSize(material?.size)"></p>
                        </div>
                    </div>
                    <div class="meta-item">
                        <span class="meta-icon">📅</span>
                        <div>
                            <p class="meta-label">Ditambahkan</p>
                            <p class="meta-value" x-text="formatDate(material?.created_at)"></p>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="material-description" x-show="material?.description">
                    <h3>📝 Deskripsi</h3>
                    <p x-text="material?.description"></p>
                </div>

                <!-- File Preview -->
                <div class="file-preview">
                    <a :href="'/storage/' + material?.file_path" target="_blank" class="download-btn">
                        📥 Download Materi
                    </a>
                </div>

                <!-- PDF Viewer (for PDF files) -->
                <template x-if="isPdf()">
                    <iframe :src="'/storage/' + material?.file_path" class="pdf-viewer"></iframe>
                </template>

                <!-- Video Player (for video files) -->
                <template x-if="isVideo()">
                    <video controls class="video-player">
                        <source :src="'/storage/' + material?.file_path" :type="material?.mime">
                        Browser Anda tidak mendukung pemutaran video.
                    </video>
                </template>
            </div>
        </div>
    </div>
</div>

<script>
function materialShowPage() {
    const pathParts = window.location.pathname.split('/').filter(Boolean);
    // URL: /student/course/{courseId}/materials/{materialId} or /student/courses/{courseId}/materials/{materialId}
    const courseId = pathParts[2];
    const materialId = pathParts[4];

    return {
        material: null,
        loading: true,
        error: null,
        courseId: courseId,
        materialId: materialId,

        async loadMaterial() {
            try {
                const query = `
                    query StudentMaterial($id: ID!) {
                        studentMaterial(id: $id) {
                            id
                            title
                            description
                            file_path
                            mime
                            size
                            extension
                            created_at
                            course { id title }
                        }
                    }
                `;
                const result = await GraphQL.query(query, { id: this.materialId });
                this.material = result.studentMaterial;
            } catch (e) {
                this.error = e.message;
            } finally {
                this.loading = false;
            }
        },

        formatDate(dateStr) {
            if (!dateStr) return '-';
            const date = new Date(dateStr);
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
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
                'mp4': '🎬',
                'webm': '🎬',
                'zip': '🗜️',
                'rar': '🗜️'
            };
            return icons[ext?.toLowerCase()] || '📄';
        },

        isPdf() {
            return this.material?.extension?.toLowerCase() === 'pdf';
        },

        isVideo() {
            const videoExts = ['mp4', 'webm', 'ogg', 'mov'];
            return videoExts.includes(this.material?.extension?.toLowerCase());
        }
    }
}
</script>
@endsection

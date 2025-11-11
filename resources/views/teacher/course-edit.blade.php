@extends('layouts.app')

@section('content')
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  .edit-wrapper {
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: 100vh;
    padding: 40px 20px;
  }

  .edit-container {
    max-width: 700px;
    margin: 0 auto;
  }

  /* Header */
  .edit-header {
    margin-bottom: 30px;
    animation: slideDown 0.6s ease-out;
  }

  .edit-header h1 {
    font-size: 2rem;
    font-weight: 900;
    color: #1a202c;
    display: flex;
    align-items: center;
    gap: 12px;
  }

  .header-icon {
    font-size: 2rem;
  }

  /* Form Card */
  .form-card {
    background: white;
    border-radius: 15px;
    padding: 35px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    animation: slideUp 0.6s ease-out;
  }

  /* Alerts */
  .alert {
    padding: 16px;
    border-radius: 10px;
    margin-bottom: 25px;
    display: flex;
    gap: 12px;
    align-items: flex-start;
    animation: slideDown 0.4s ease-out;
  }

  .alert-success {
    background: #c6f6d5;
    color: #22543d;
    border: 1px solid #9ae6b4;
  }

  .alert-error {
    background: #fed7d7;
    color: #742a2a;
    border: 1px solid #fc8181;
  }

  .alert-icon {
    font-size: 1.2rem;
    flex-shrink: 0;
  }

  .alert-content h3 {
    font-weight: 700;
    margin-bottom: 8px;
  }

  .alert-list {
    list-style: none;
    padding-left: 0;
  }

  .alert-list li {
    padding: 4px 0;
    display: flex;
    gap: 8px;
  }

  .alert-list li::before {
    content: '•';
    font-weight: bold;
  }

  /* Form Group */
  .form-group {
    margin-bottom: 25px;
  }

  .form-label {
    display: block;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 10px;
    font-size: 0.95rem;
  }

  .form-input,
  .form-select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    font-family: inherit;
  }

  .form-input:focus,
  .form-select:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
  }

  .form-input::placeholder {
    color: #cbd5e0;
  }

  .form-error {
    color: #e53e3e;
    font-size: 0.85rem;
    margin-top: 6px;
    display: flex;
    align-items: center;
    gap: 6px;
  }

  /* Form Actions */
  .form-actions {
    display: flex;
    gap: 12px;
    margin-top: 30px;
    padding-top: 30px;
    border-top: 2px solid #e2e8f0;
  }

  .btn {
    padding: 12px 28px;
    border: none;
    border-radius: 10px;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
  }

  .btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    flex: 1;
    justify-content: center;
  }

  .btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 25px rgba(102, 126, 234, 0.6);
  }

  .btn-primary:active {
    transform: translateY(0);
  }

  .btn-secondary {
    background: #f7fafc;
    color: #4a5568;
    border: 2px solid #cbd5e0;
    flex: 1;
    justify-content: center;
  }

  .btn-secondary:hover {
    background: #edf2f7;
    border-color: #a0aec0;
    transform: translateY(-2px);
  }

  /* Animations */
  @keyframes slideDown {
    from {
      opacity: 0;
      transform: translateY(-20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Responsive */
  @media (max-width: 768px) {
    .edit-wrapper {
      padding: 30px 15px;
    }

    .edit-header h1 {
      font-size: 1.6rem;
    }

    .form-card {
      padding: 25px;
    }

    .form-actions {
      flex-direction: column;
    }

    .btn {
      width: 100%;
    }
  }

  @media (max-width: 480px) {
    .edit-wrapper {
      padding: 20px 10px;
    }

    .edit-header h1 {
      font-size: 1.3rem;
    }

    .header-icon {
      font-size: 1.6rem;
    }

    .form-card {
      padding: 20px;
    }

    .form-label {
      font-size: 0.9rem;
    }

    .form-input,
    .form-select {
      padding: 10px 12px;
      font-size: 0.9rem;
    }

    .alert {
      font-size: 0.9rem;
    }
  }
</style>

<div class="edit-wrapper">
  <div class="edit-container">

    <!-- Header -->
    <div class="edit-header">
      <h1>
        <span class="header-icon">✏️</span>
        Edit Kursus
      </h1>
    </div>

    <div class="form-card">

      <!-- Success Alert -->
      @if (session('ok'))
        <div class="alert alert-success">
          <span class="alert-icon">✓</span>
          <div class="alert-content">
            <p>{{ session('ok') }}</p>
          </div>
        </div>
      @endif

      <!-- Error Alert -->
      @if ($errors->any())
        <div class="alert alert-error">
          <span class="alert-icon">✕</span>
          <div class="alert-content">
            <h3>Periksa form Anda</h3>
            <ul class="alert-list">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        </div>
      @endif

      <!-- Form -->
      <form method="POST" action="{{ route('teacher.courses.update', $course->id) }}">
        @csrf
        @method('PUT')

        <!-- Title Field -->
        <div class="form-group">
          <label for="title" class="form-label">Judul Kursus</label>
          <input 
            type="text" 
            id="title"
            name="title" 
            class="form-input"
            value="{{ old('title', $course->title) }}"
            placeholder="Masukkan judul kursus"
            required
          />
          @error('title')
            <div class="form-error">
              <span>✕</span>
              <span>{{ $message }}</span>
            </div>
          @enderror
        </div>

        <!-- Code Field -->
        <div class="form-group">
          <label for="code" class="form-label">Kode Kursus</label>
          <input 
            type="text" 
            id="code"
            name="code" 
            class="form-input"
            value="{{ old('code', $course->code) }}"
            placeholder="Contoh: PROG101"
            required
          />
          @error('code')
            <div class="form-error">
              <span>✕</span>
              <span>{{ $message }}</span>
            </div>
          @enderror
        </div>

        <!-- Status Field -->
        <div class="form-group">
          <label for="status" class="form-label">Status</label>
          <select 
            id="status"
            name="status" 
            class="form-select"
            required
          >
            <option value="draft" {{ old('status', $course->status) === 'draft' ? 'selected' : '' }}>
              📝 Draft
            </option>
            <option value="published" {{ old('status', $course->status) === 'published' ? 'selected' : '' }}>
              ✓ Dipublikasikan
            </option>
          </select>
          @error('status')
            <div class="form-error">
              <span>✕</span>
              <span>{{ $message }}</span>
            </div>
          @enderror
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
          <button type="submit" class="btn btn-primary">
            <span>💾</span>
            <span>Simpan Perubahan</span>
          </button>
          <a href="{{ route('teacher.courses') }}" class="btn btn-secondary">
            <span>←</span>
            <span>Batal</span>
          </a>
        </div>
      </form>

    </div>

  </div>
</div>

@endsection
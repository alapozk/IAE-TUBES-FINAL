@extends('layouts.app')

@section('content')
<style>
  .page{
    background:linear-gradient(135deg,#f5f7fa 0%,#c3cfe2 100%);
    min-height:100vh;
    padding:32px 20px
  }
  .container{max-width:900px;margin:0 auto}

  .back{
    display:inline-flex;
    align-items:center;
    gap:8px;
    color:#667eea;
    text-decoration:none;
    font-weight:700;
    margin-bottom:16px
  }

  .card{
    background:#fff;
    border-radius:18px;
    box-shadow:0 15px 40px rgba(0,0,0,.10);
    padding:26px
  }

  h2{
    font-weight:900;
    margin-bottom:18px;
    display:flex;
    align-items:center;
    gap:10px
  }

  .question-card{
    border:2px solid #e5e7eb;
    border-radius:16px;
    padding:20px;
    margin-bottom:20px;
    position:relative
  }

  .question-number{
    font-weight:900;
    margin-bottom:10px;
    color:#4f46e5
  }

  .form-group{margin-bottom:16px}
  label{
    font-weight:700;
    margin-bottom:6px;
    display:block
  }

  input, textarea, select{
    width:100%;
    padding:12px 14px;
    border-radius:12px;
    border:1px solid #e5e7eb;
    font-size:.95rem
  }

  textarea{min-height:90px}

  input:focus, textarea:focus, select:focus{
    outline:none;
    border-color:#667eea;
    box-shadow:0 0 0 3px rgba(102,126,234,.2)
  }

  .options{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:14px
  }

  .answer{
    background:#f8fafc;
    border-radius:12px;
    padding:14px;
    margin-top:8px
  }

  .btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:12px 18px;
    border-radius:12px;
    border:none;
    font-weight:800;
    cursor:pointer
  }

  .btn-primary{
    background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);
    color:#fff;
    box-shadow:0 8px 20px rgba(102,126,234,.35)
  }

  .btn-secondary{
    background:#f3f4f6;
    color:#111827
  }

  .btn-danger{
    background:#fee2e2;
    color:#991b1b;
    position:absolute;
    top:14px;
    right:14px
  }

  .actions{
    display:flex;
    justify-content:space-between;
    gap:12px;
    margin-top:20px;
    flex-wrap:wrap
  }

  @media(max-width:640px){
    .options{grid-template-columns:1fr}
  }
</style>

<div class="page">
  <div class="container">

    {{-- BACK --}}
    <a href="{{ route('teacher.courses.show', $quiz->course_id) }}" class="back">
      ← Kembali ke Kursus
    </a>

    <div class="card">
      <h2>📝 Tambah Soal Quiz</h2>

      <form method="POST" action="{{ route('teacher.quiz.questions.store', $quiz->id) }}">
        @csrf

        <div id="questions-container">

          {{-- SOAL 1 --}}
          <div class="question-card">
            <div class="question-number">Soal 1</div>

            <div class="form-group">
              <label>Pertanyaan</label>
              <textarea name="questions[0][question]" required></textarea>
            </div>

            <div class="options">
              <input name="questions[0][option_a]" placeholder="Opsi A" required>
              <input name="questions[0][option_b]" placeholder="Opsi B" required>
              <input name="questions[0][option_c]" placeholder="Opsi C" required>
              <input name="questions[0][option_d]" placeholder="Opsi D" required>
            </div>

            <div class="answer">
              <label>Jawaban Benar</label>
              <select name="questions[0][correct_answer]" required>
                <option value="">-- Pilih --</option>
                <option value="a">A</option>
                <option value="b">B</option>
                <option value="c">C</option>
                <option value="d">D</option>
              </select>
            </div>
          </div>

        </div>

        <div class="actions">
          <button type="button" class="btn btn-secondary" onclick="addQuestion()">
            ➕ Tambah Soal
          </button>

          <button type="submit" class="btn btn-primary">
            💾 Simpan Semua Soal
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

<script>
let index = 1;

function addQuestion() {
  const container = document.getElementById('questions-container');

  const html = `
    <div class="question-card">
      <button type="button" class="btn btn-danger" onclick="removeQuestion(this)">✖</button>
      <div class="question-number">Soal ${index + 1}</div>

      <div class="form-group">
        <label>Pertanyaan</label>
        <textarea name="questions[${index}][question]" required></textarea>
      </div>

      <div class="options">
        <input name="questions[${index}][option_a]" placeholder="Opsi A" required>
        <input name="questions[${index}][option_b]" placeholder="Opsi B" required>
        <input name="questions[${index}][option_c]" placeholder="Opsi C" required>
        <input name="questions[${index}][option_d]" placeholder="Opsi D" required>
      </div>

      <div class="answer">
        <label>Jawaban Benar</label>
        <select name="questions[${index}][correct_answer]" required>
          <option value="">-- Pilih --</option>
          <option value="a">A</option>
          <option value="b">B</option>
          <option value="c">C</option>
          <option value="d">D</option>
        </select>
      </div>
    </div>
  `;

  container.insertAdjacentHTML('beforeend', html);
  index++;
  updateNumbers();
}

function removeQuestion(btn) {
  btn.closest('.question-card').remove();
  updateNumbers();
}

function updateNumbers() {
  document.querySelectorAll('.question-number')
    .forEach((el, i) => el.innerText = `Soal ${i + 1}`);
}
</script>
@endsection

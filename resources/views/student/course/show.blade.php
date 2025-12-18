@if(session('quiz_result'))
  <div style="margin:20px 0;padding:16px;border-radius:12px;background:#ecfdf5">
    <strong>Hasil Quiz</strong><br>
    Skor: {{ session('quiz_result.score') }}/{{ session('quiz_result.total') }} <br>
    Nilai: {{ session('quiz_result.percentage') }}%
  </div>
@endif

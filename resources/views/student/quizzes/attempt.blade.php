<form method="POST" action="{{ route('student.quiz.answer', $quiz->id) }}">
  @csrf

  <input type="hidden" name="question_id" value="{{ $question->id }}">

  <label>
    <input type="radio" name="answer" value="a" required>
    {{ $question->option_a }}
  </label><br>

  <label>
    <input type="radio" name="answer" value="b">
    {{ $question->option_b }}
  </label><br>

  <label>
    <input type="radio" name="answer" value="c">
    {{ $question->option_c }}
  </label><br>

  <label>
    <input type="radio" name="answer" value="d">
    {{ $question->option_d }}
  </label><br><br>

  <button type="submit" class="btn btn-primary">
    ➡️ Jawab & Lanjut
  </button>
</form>

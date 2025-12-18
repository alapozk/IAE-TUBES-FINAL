@extends('layouts.app')

@section('content')
<div class="container" style="max-width:800px">

  <h2>{{ $quiz->title }}</h2>
  <p>Jawab semua soal di bawah ini</p>

  <form method="POST" action="{{ route('student.quiz.answer', $quiz->id) }}">
    @csrf

    @foreach($questions as $index => $q)
      <div style="margin-bottom:24px;padding:20px;border:1px solid #e5e7eb;border-radius:12px">
        <strong>Soal {{ $index + 1 }}</strong>
        <p>{{ $q->question }}</p>

        @foreach(['a','b','c','d'] as $opt)
          <label style="display:block;margin-bottom:6px">
            <input
              type="radio"
              name="answers[{{ $q->id }}]"
              value="{{ $opt }}"
              required
            >
            {{ strtoupper($opt) }}. {{ $q->{'option_'.$opt} }}
          </label>
        @endforeach
      </div>
    @endforeach

    <button type="submit" class="btn btn-primary">
      Kirim Jawaban
    </button>
  </form>

</div>
@endsection

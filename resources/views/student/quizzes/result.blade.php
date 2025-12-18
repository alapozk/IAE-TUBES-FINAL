@extends('layouts.app')

@section('content')
<div class="container text-center">
    <h3>Quiz Selesai</h3>

    <p class="fs-4">
        Nilai kamu: <strong>{{ $attempt->score }}</strong>
    </p>

    @if($attempt->quiz->show_review)
        <p class="text-muted">
            Review jawaban diaktifkan oleh guru
        </p>
    @else
        <p class="text-muted">
            Review jawaban tidak tersedia
        </p>
    @endif

    <a href="{{ route('student.mycourses') }}"
       class="btn btn-primary mt-3">
       Kembali ke Kursus
    </a>
</div>
@endsection

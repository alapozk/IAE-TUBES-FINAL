@extends('layouts.app')

@section('content')
@php
$val = fn($k, $fmt = null) =>
    old($k) ?? ($fmt ? optional($assignment->$k)?->format($fmt) : $assignment->$k);
@endphp

@include('teacher.partials.assignment-form', [
    'title' => 'Edit Tugas — ' . $course->title,
    'action' => route('teacher.assignments.update', [$course, $assignment]),
    'method' => 'PUT',
    'defaults' => [
        'title' => $val('title'),
        'instructions' => $val('instructions'),
        'due_at' => $val('due_at', 'Y-m-d\TH:i'),
        'submission_mode' => $val('submission_mode'),
        'max_points' => $val('max_points'),
        'status' => $val('status'),
    ],
])
@endsection

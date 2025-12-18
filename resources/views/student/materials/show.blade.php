@extends('layouts.app')

@section('content')
<div class="container">
    <a href="{{ url()->previous() }}">← Kembali</a>

    <h1>{{ $material->title }}</h1>

    <div class="content">
        {!! $material->content !!}
    </div>
</div>
@endsection

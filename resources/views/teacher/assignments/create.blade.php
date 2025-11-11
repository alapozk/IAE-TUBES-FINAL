{{-- resources/views/teacher/assignments/create.blade.php --}}
@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Buat Tugas</h1>
<form method="POST" action="{{ route('assignments.store') }}" class="space-y-3">@csrf
  <div><label>Kursus</label>
    <select name="course_id" class="border p-2 w-full">
      @foreach($courses as $c)<option value="{{ $c->id }}">{{ $c->title }}</option>@endforeach
    </select>
  </div>
  <div><label>Judul</label><input name="title" class="border p-2 w-full" required></div>
  <div><label>Instruksi</label><textarea name="instructions" class="border p-2 w-full"></textarea></div>
  <div><label>Jatuh Tempo</label><input type="datetime-local" name="due_at" class="border p-2 w-full"></div>
  <div><label>Maks Skor</label><input type="number" name="max_score" value="100" class="border p-2 w-full"></div>
  <button class="px-4 py-2 bg-black text-white rounded">Simpan</button>
</form>
@endsection

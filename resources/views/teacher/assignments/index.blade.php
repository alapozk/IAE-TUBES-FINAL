{{-- resources/views/teacher/assignments/index.blade.php --}}
@extends('layouts.app')
@section('content')
<h1 class="text-xl font-semibold mb-4">Tugas</h1>
<a class="underline text-sm" href="{{ route('assignments.create') }}">+ Buat Tugas</a>
<ul class="list-disc ml-6 mt-3">
@forelse($items as $a)
  <li>{{ $a->title }} — {{ optional($a->due_at)->format('d/m/Y H:i') }}
    <a class="text-xs underline" href="{{ route('assignments.edit',$a) }}">edit</a>
    <form method="POST" action="{{ route('assignments.destroy',$a) }}" class="inline">@csrf @method('DELETE')
      <button class="text-xs underline" onclick="return confirm('Hapus?')">hapus</button>
    </form>
  </li>
@empty <li>Belum ada.</li> @endforelse
</ul>
@endsection

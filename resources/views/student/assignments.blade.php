@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-6">

    {{-- Header --}}
    <div class="flex items-center gap-3 mb-6">
        <div class="w-8 h-8 rounded-md bg-indigo-500 flex items-center justify-center">
            <span class="text-white text-lg">📂</span>
        </div>
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Daftar Tugas</h1>
            <p class="text-sm text-gray-600">
                Berikut adalah tugas-tugas Anda yang sedang aktif di berbagai kursus.
            </p>
        </div>
    </div>

    {{-- Quick Stats --}}
    @php
        $total      = count($assignments);
        $pending    = collect($assignments)->where('status', 'Belum Dikerjakan')->count();
        $inProgress = collect($assignments)->where('status', 'Dikerjakan')->count();
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10">
        <div class="bg-white rounded-2xl shadow p-4">
            <p class="text-xs text-gray-500">Total Tugas</p>
            <p class="text-2xl font-semibold text-indigo-600">{{ $total }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-4">
            <p class="text-xs text-gray-500">Belum Dikerjakan</p>
            <p class="text-2xl font-semibold text-rose-500">{{ $pending }}</p>
        </div>
        <div class="bg-white rounded-2xl shadow p-4">
            <p class="text-xs text-gray-500">Sedang Dikerjakan</p>
            <p class="text-2xl font-semibold text-amber-500">{{ $inProgress }}</p>
        </div>
    </div>

    {{-- SECTION 1: Kartu "Tugas Aktif" (ringkasan per tugas) --}}
    @if($total > 0)
    <div class="mb-10">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Tugas Aktif</h2>
            <span class="text-[11px] px-3 py-1 rounded-full bg-indigo-50 text-indigo-600">
                {{ $total }} tugas terdaftar
            </span>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($assignments as $task)
                @php
                    $status = $task->status ?? 'Belum Dikerjakan';
                    $statusColor = match($status) {
                        'Belum Dikerjakan' => 'bg-rose-50 text-rose-600',
                        'Dikerjakan'       => 'bg-amber-50 text-amber-600',
                        default            => 'bg-emerald-50 text-emerald-600',
                    };
                @endphp

                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col gap-1 hover:shadow-md transition">
                    <h3 class="font-semibold text-gray-800 text-sm">
                        {{ $task->title }}
                    </h3>
                    <p class="text-xs text-gray-500">
                        📘 {{ $task->course->title ?? '-' }}
                    </p>
                    <p class="text-xs text-gray-500">
                        ⏰ {{ optional($task->due_at)->format('d M Y H:i') }}
                    </p>
                    <div class="mt-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-medium {{ $statusColor }}">
                            {{ $status }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- SECTION 2: Tabel Detail Tugas --}}
<div class="mt-8 bg-white rounded-2xl shadow-md w-full overflow-hidden">
    <div class="px-6 py-4 border-b flex items-center justify-between bg-gradient-to-r from-white to-indigo-50">
        <div>
            <h2 class="text-lg font-semibold text-gray-800">Detail Tugas</h2>
            <p class="text-xs text-gray-500">
                Lihat detail lengkap tugas, kursus, jatuh tempo, dan status pengerjaan Anda.
            </p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse">
            <thead class="bg-gray-50 text-[11px] uppercase tracking-wide text-gray-500 border-b">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold w-2/5">Judul Tugas</th>
                    <th class="px-6 py-3 text-left font-semibold w-1/4">Kursus</th>
                    <th class="px-6 py-3 text-left font-semibold w-1/4">Tanggal Jatuh Tempo</th>
                </tr>
            </thead>
            <tbody class="text-sm text-gray-700">
                @forelse ($assignments as $task)
                    @php
                        $status = $task->status ?? 'Belum Dikerjakan';
                        $badge = match($status) {
                            'Belum Dikerjakan' => 'bg-rose-50 text-rose-600',
                            'Dikerjakan'       => 'bg-amber-50 text-amber-600',
                            default            => 'bg-emerald-50 text-emerald-600',
                        };
                    @endphp
                    <tr class="border-b last:border-0 hover:bg-gray-50 transition">
                        {{-- Judul --}}
                        <td class="px-6 py-3 align-middle">
                            <div class="flex flex-col">
                                <span class="font-medium text-gray-900 hover:text-indigo-600 cursor-pointer">
                                    {{ $task->title }}
                                </span>
                            </div>
                        </td>

                        {{-- Kursus --}}
                        <td class="px-6 py-3 align-middle text-gray-600">
                            {{ $task->course->title ?? '-' }}
                        </td>

                        {{-- Due date --}}
                        <td class="px-6 py-3 align-middle text-gray-600">
                            {{ optional($task->due_at)->format('d M Y H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-6 text-center text-gray-500">
                            Belum ada tugas yang terdaftar.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

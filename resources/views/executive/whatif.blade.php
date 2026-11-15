@php
    $fmt = fn ($s) => $s === null ? '-' : number_format((float) $s, 1, ',', '.') . '%';
    $scoreColor = fn ($s) => $s === null ? '#94a3b8' : ($s >= 90 ? '#059669' : ($s >= 75 ? '#d97706' : '#dc2626'));
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-800">What-if Analysis</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Simulasikan: bagaimana skor kepatuhan berubah jika sebagian item &ldquo;tidak sesuai&rdquo; diperbaiki?
                </p>
            </div>
            <a href="{{ route('executive.index') }}"
               class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                &larr; Analisis Eksekutif
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- ============ FORM SKENARIO ============ --}}
        <form method="GET" action="{{ route('executive.whatif') }}"
              class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm"
              x-data="{ fix: {{ $fixPercent }} }">
            <div class="grid gap-5 md:grid-cols-2">
                <div>
                    <label for="fix_percent" class="block text-sm font-semibold text-slate-700">
                        Persentase item tidak sesuai yang diperbaiki:
                        <span class="text-blue-700" x-text="fix + '%'">{{ $fixPercent }}%</span>
                    </label>
                    <input id="fix_percent" name="fix_percent" type="range" min="0" max="100" step="5"
                           x-model="fix" value="{{ $fixPercent }}" class="mt-3 w-full">
                    @error('fix_percent') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="target" class="block text-sm font-semibold text-slate-700">Target skor kepatuhan (%)</label>
                    <input id="target" name="target" type="number" min="1" max="100" step="0.5"
                           value="{{ $target }}"
                           class="mt-2 w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('target') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <button type="submit"
                    class="mt-5 inline-flex items-center rounded-xl bg-[#17365D] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#122B4D]">
                Hitung Proyeksi
            </button>
        </form>

        {{-- ============ HASIL PER TERMINAL ============ --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-bold text-slate-800">Hasil Proyeksi per Terminal</h3>
            <p class="mb-4 text-xs text-slate-500">
                Rumus: (sesuai + tidak sesuai &times; {{ $fixPercent }}%) &divide; (sesuai + tidak sesuai) &times; 100.
            </p>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-2 pr-3">Terminal</th>
                            <th class="py-2 pr-3 text-right">Sesuai</th>
                            <th class="py-2 pr-3 text-right">Tidak Sesuai</th>
                            <th class="py-2 pr-3 text-right">Skor Sekarang</th>
                            <th class="py-2 pr-3 text-right">Skor Proyeksi</th>
                            <th class="py-2">Menuju Target {{ rtrim(rtrim(number_format($target, 1, ',', '.'), '0'), ',') }}%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($results as $r)
                            <tr>
                                <td class="py-2 pr-3 font-semibold text-slate-800">{{ $r['label'] }}</td>
                                <td class="py-2 pr-3 text-right">{{ $r['ok'] }}</td>
                                <td class="py-2 pr-3 text-right">{{ $r['not_ok'] }}</td>
                                <td class="py-2 pr-3 text-right font-bold" style="color: {{ $scoreColor($r['current']) }}">{{ $fmt($r['current']) }}</td>
                                <td class="py-2 pr-3 text-right font-bold" style="color: {{ $scoreColor($r['projected']) }}">{{ $fmt($r['projected']) }}</td>
                                <td class="py-2 text-slate-600">
                                    @if ($r['assessed'] === 0)
                                        Belum ada item yang dinilai.
                                    @elseif ($r['needed'] === 0)
                                        Target sudah tercapai.
                                    @elseif ($r['reachable'])
                                        Perlu memperbaiki <strong>{{ $r['needed'] }}</strong> dari {{ $r['not_ok'] }} item.
                                    @else
                                        Target tidak tercapai walau semua item diperbaiki.
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="py-6 text-center text-slate-500">Belum ada data. Buat inspeksi lalu jalankan ETL.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ============ PERBANDINGAN SKENARIO ============ --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-bold text-slate-800">Perbandingan Skenario</h3>
            <p class="mb-4 text-xs text-slate-500">Skor proyeksi bila 0%, 25%, 50%, 75%, atau 100% item tidak sesuai diperbaiki.</p>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="py-2 pr-3">Terminal</th>
                            @foreach ($scenarios as $p)
                                <th class="py-2 pr-3 text-right">Perbaiki {{ $p }}%</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($results as $r)
                            <tr>
                                <td class="py-2 pr-3 font-semibold text-slate-800">{{ $r['label'] }}</td>
                                @foreach ($scenarios as $p)
                                    <td class="py-2 pr-3 text-right font-semibold" style="color: {{ $scoreColor($r['scenarios'][$p]) }}">
                                        {{ $fmt($r['scenarios'][$p]) }}
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>

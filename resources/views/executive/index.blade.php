@php
    // Warna skor kepatuhan: hijau >= 90, kuning >= 75, merah di bawahnya.
    $scoreColor = fn ($s) => $s === null ? '#94a3b8' : ($s >= 90 ? '#059669' : ($s >= 75 ? '#d97706' : '#dc2626'));
    $fmt = fn ($s) => $s === null ? '-' : number_format((float) $s, 1, ',', '.') . '%';
    $query = array_filter(['terminal' => $terminal, 'brand' => $brand]);
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-800">Analisis Eksekutif</h2>
                <p class="mt-1 text-sm text-slate-500">
                    Ringkasan kepatuhan gerai untuk pengambilan keputusan (data hasil ETL).
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('executive.whatif') }}"
                   class="inline-flex items-center rounded-xl bg-[#17365D] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#122B4D]">
                    What-if Analysis
                </a>
                <a href="{{ route('executive.export.csv', $query) }}"
                   class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Unduh CSV
                </a>
                <a href="{{ route('executive.export.pdf', $query) }}"
                   class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Unduh PDF
                </a>

                @if (Auth::user()->isAdmin())
                    <form method="POST" action="{{ route('executive.etl') }}">
                        @csrf
                        <button type="submit"
                                class="inline-flex items-center rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-800 transition hover:bg-emerald-100">
                            Jalankan ETL
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        {{-- ================= BREADCRUMB DRILL-DOWN ================= --}}
        <nav class="flex flex-wrap items-center gap-2 text-sm" aria-label="Drill-down">
            <a href="{{ route('executive.index') }}"
               class="font-semibold {{ $terminal ? 'text-blue-700 hover:underline' : 'text-slate-800' }}">
                Semua Terminal
            </a>

            @if ($terminal)
                <span class="text-slate-400">/</span>
                <a href="{{ route('executive.index', ['terminal' => $terminal]) }}"
                   class="font-semibold {{ $brand ? 'text-blue-700 hover:underline' : 'text-slate-800' }}">
                    {{ $terminal }}
                </a>
            @endif

            @if ($brand)
                <span class="text-slate-400">/</span>
                <span class="font-semibold text-slate-800">{{ $brand }}</span>
            @endif
        </nav>

        {{-- ================= KPI ================= --}}
        <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Inspeksi</p>
                <p class="mt-2 text-3xl font-bold text-slate-800">{{ number_format($kpi['inspections'], 0, ',', '.') }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Rata-rata Skor Kepatuhan</p>
                <p class="mt-2 text-3xl font-bold" style="color: {{ $scoreColor($kpi['avg_score']) }}">
                    {{ $fmt($kpi['avg_score']) }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Item Tidak Sesuai</p>
                <p class="mt-2 text-3xl font-bold text-slate-800">{{ number_format($kpi['not_ok'], 0, ',', '.') }}</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Total Temuan</p>
                <p class="mt-2 text-3xl font-bold text-slate-800">{{ number_format($kpi['findings'], 0, ',', '.') }}</p>
            </div>
        </section>

        <p class="text-xs text-slate-500">
            Data diperbarui oleh ETL terakhir:
            {{ $kpi['extracted'] ? \Illuminate\Support\Carbon::parse($kpi['extracted'])->format('d/m/Y H:i') : 'belum pernah dijalankan' }}.
            Skor kepatuhan = item sesuai &divide; (sesuai + tidak sesuai) &times; 100.
        </p>

        <div class="grid gap-6 lg:grid-cols-2">

            {{-- ================= TABEL DRILL-DOWN ================= --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-base font-bold text-slate-800">
                    @if ($level === 'terminal') Skor per Terminal
                    @elseif ($level === 'brand') Skor per Brand &mdash; {{ $terminal }}
                    @else Daftar Inspeksi &mdash; {{ $brand }}
                    @endif
                </h3>
                <p class="mb-4 text-xs text-slate-500">
                    @if ($level !== 'inspection') Klik satu baris untuk melihat rincian di bawahnya (drill-down).
                    @else Klik nomor inspeksi untuk membuka detail.
                    @endif
                </p>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="border-b border-slate-200 text-xs uppercase tracking-wider text-slate-500">
                            @if ($level === 'inspection')
                                <tr>
                                    <th class="py-2 pr-3">No. Inspeksi</th>
                                    <th class="py-2 pr-3">Tanggal</th>
                                    <th class="py-2 pr-3 text-right">Tidak Sesuai</th>
                                    <th class="py-2 text-right">Skor</th>
                                </tr>
                            @else
                                <tr>
                                    <th class="py-2 pr-3">{{ $level === 'terminal' ? 'Terminal' : 'Brand' }}</th>
                                    <th class="py-2 pr-3 text-right">Inspeksi</th>
                                    <th class="py-2 pr-3 text-right">Tidak Sesuai</th>
                                    <th class="py-2 text-right">Skor</th>
                                </tr>
                            @endif
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @if ($level === 'inspection')
                                @forelse ($inspections as $fact)
                                    <tr>
                                        <td class="py-2 pr-3">
                                            <a class="font-semibold text-blue-700 hover:underline"
                                               href="{{ route('inspections.show', $fact->inspection_id) }}">
                                                {{ $fact->inspection_number ?: 'Inspeksi #' . $fact->inspection_id }}
                                            </a>
                                        </td>
                                        <td class="py-2 pr-3 text-slate-600">{{ $fact->inspection_date?->format('d/m/Y') }}</td>
                                        <td class="py-2 pr-3 text-right">{{ $fact->tidak_sesuai_count }}</td>
                                        <td class="py-2 text-right font-bold" style="color: {{ $scoreColor($fact->compliance_score) }}">
                                            {{ $fmt($fact->compliance_score) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-6 text-center text-slate-500">Belum ada data.</td></tr>
                                @endforelse
                            @else
                                @forelse ($rows as $row)
                                    @php
                                        $target = $level === 'terminal'
                                            ? ['terminal' => $row->label]
                                            : ['terminal' => $terminal, 'brand' => $row->label];
                                    @endphp
                                    <tr class="transition hover:bg-slate-50">
                                        <td class="py-2 pr-3">
                                            <a class="font-semibold text-blue-700 hover:underline"
                                               href="{{ route('executive.index', $target) }}">
                                                {{ $row->label }} &rsaquo;
                                            </a>
                                        </td>
                                        <td class="py-2 pr-3 text-right">{{ $row->total }}</td>
                                        <td class="py-2 pr-3 text-right">{{ (int) $row->not_ok }}</td>
                                        <td class="py-2 text-right font-bold" style="color: {{ $scoreColor($row->avg_score) }}">
                                            {{ $fmt($row->avg_score) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-6 text-center text-slate-500">
                                        Belum ada data. Buat inspeksi lalu jalankan ETL.
                                    </td></tr>
                                @endforelse
                            @endif
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- ================= GRAFIK TREN (SVG) ================= --}}
            <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <h3 class="text-base font-bold text-slate-800">Tren Skor Kepatuhan per Bulan</h3>
                <p class="mb-4 text-xs text-slate-500">Rata-rata skor tiap bulan (maksimal 12 bulan terakhir).</p>

                @if ($trend->isEmpty())
                    <p class="py-10 text-center text-sm text-slate-500">Belum ada data untuk grafik.</p>
                @else
                    @php
                        $count = $trend->count();
                        $chartW = 560; $chartH = 220; $padL = 34; $padB = 30; $padT = 14;
                        $plotH = $chartH - $padB - $padT;
                        $slot = ($chartW - $padL) / max($count, 1);
                        $barW = min(40, $slot * 0.6);
                    @endphp
                    <svg viewBox="0 0 {{ $chartW }} {{ $chartH }}" class="w-full" role="img"
                         aria-label="Grafik batang tren skor kepatuhan per bulan">
                        @foreach ([0, 50, 100] as $tick)
                            @php $y = $padT + $plotH - ($tick / 100 * $plotH); @endphp
                            <line x1="{{ $padL }}" x2="{{ $chartW }}" y1="{{ $y }}" y2="{{ $y }}" stroke="#e2e8f0" stroke-width="1"/>
                            <text x="{{ $padL - 6 }}" y="{{ $y + 4 }}" text-anchor="end" font-size="10" fill="#64748b">{{ $tick }}</text>
                        @endforeach

                        @foreach ($trend as $i => $point)
                            @php
                                $score = $point->avg_score === null ? 0 : (float) $point->avg_score;
                                $h = $score / 100 * $plotH;
                                $x = $padL + $slot * $i + ($slot - $barW) / 2;
                                $y = $padT + $plotH - $h;
                            @endphp
                            <rect x="{{ $x }}" y="{{ $y }}" width="{{ $barW }}" height="{{ max($h, 1) }}" rx="4"
                                  fill="{{ $scoreColor($point->avg_score === null ? null : $score) }}">
                                <title>{{ $point->period }}: {{ $fmt($point->avg_score) }} ({{ $point->total }} inspeksi)</title>
                            </rect>
                            <text x="{{ $x + $barW / 2 }}" y="{{ $y - 4 }}" text-anchor="middle" font-size="10" fill="#334155">
                                {{ $point->avg_score === null ? '' : number_format($score, 0) }}
                            </text>
                            <text x="{{ $x + $barW / 2 }}" y="{{ $chartH - 10 }}" text-anchor="middle" font-size="10" fill="#64748b">
                                {{ substr($point->period, 5) }}/{{ substr($point->period, 2, 2) }}
                            </text>
                        @endforeach
                    </svg>
                @endif
            </section>
        </div>

        {{-- ================= EXCEPTION REPORT ================= --}}
        <section class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h3 class="text-base font-bold text-slate-800">10 Kriteria Paling Sering Tidak Sesuai</h3>
            <p class="mb-4 text-xs text-slate-500">Laporan pengecualian (exception report) untuk fokus perbaikan.</p>

            @php $maxFail = max(1, (int) ($topFailed->max('total') ?? 1)); @endphp

            <div class="space-y-3">
                @forelse ($topFailed as $item)
                    <div>
                        <div class="flex items-start justify-between gap-3 text-sm">
                            <p class="text-slate-700"><span class="font-bold">{{ $item->code }}</span> &mdash; {{ \Illuminate\Support\Str::limit($item->criteria, 140) }}</p>
                            <span class="shrink-0 font-bold text-slate-800">{{ $item->total }}x</span>
                        </div>
                        <div class="mt-1 h-2 rounded-full bg-slate-100">
                            <div class="h-2 rounded-full" style="width: {{ $item->total / $maxFail * 100 }}%; background: #dc2626"></div>
                        </div>
                    </div>
                @empty
                    <p class="py-4 text-center text-sm text-slate-500">Belum ada item yang dinilai tidak sesuai.</p>
                @endforelse
            </div>
        </section>
    </div>
</x-app-layout>

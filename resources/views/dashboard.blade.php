<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-2xl font-bold tracking-tight text-slate-800">
                Dashboard
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Ringkasan kegiatan inspeksi gerai.
            </p>
        </div>
    </x-slot>

    @php
        // Nilai turunan dari data yang sudah dikirim controller (tanpa query tambahan)
        $draftPercent = $totalInspections > 0
            ? (int) round(($draftInspections / $totalInspections) * 100)
            : 0;

        $findingsAverage = $totalInspections > 0
            ? number_format($totalFindings / $totalInspections, 1, ',', '.')
            : '0';
    @endphp

    <div class="space-y-6 lg:space-y-8">

        {{-- ================================
             HERO / SAMBUTAN
        ================================= --}}
        <section class="relative isolate overflow-hidden rounded-3xl bg-gradient-to-br from-[#122B4D] via-[#17365D] to-[#2B5C94] p-6 text-white shadow-xl shadow-blue-900/10 sm:p-8 lg:p-10">

            {{-- Dekorasi: pola titik + cahaya lembut --}}
            <div class="pointer-events-none absolute inset-0 -z-10 opacity-[0.12]"
                 style="background-image: radial-gradient(circle at 1px 1px, #fff 1px, transparent 0); background-size: 22px 22px;"></div>
            <div class="pointer-events-none absolute -right-20 -top-24 -z-10 h-80 w-80 rounded-full bg-sky-400/20 blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-32 left-1/3 -z-10 h-64 w-64 rounded-full bg-indigo-400/20 blur-3xl"></div>

            <div class="grid items-center gap-8 lg:grid-cols-[1fr_auto]">

                <div class="max-w-2xl">

                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-[11px] font-semibold uppercase tracking-wider text-blue-100 backdrop-blur">
                            <span class="relative flex h-2 w-2">
                                <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-300 opacity-60"></span>
                                <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-300"></span>
                            </span>
                            Sistem Checklist Inspeksi Gerai
                        </span>

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-white/5 px-3 py-1.5 text-[11px] font-medium text-blue-100/90">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z"/>
                            </svg>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </span>
                    </div>

                    <h1 class="mt-5 text-2xl font-bold leading-tight tracking-tight sm:text-3xl lg:text-[2.1rem]">
                        Selamat datang, {{ Auth::user()->name }}!
                    </h1>

                    <p class="mt-3 max-w-xl text-sm leading-7 text-blue-100/90 sm:text-base">
                        Pantau kegiatan inspeksi, kelola checklist pemeriksaan, serta tinjau
                        temuan dan riwayat aktivitas dalam satu dashboard terintegrasi.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">
                        <a href="{{ route('inspections.create') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-[#17365D] shadow-lg shadow-black/10 transition duration-200 hover:-translate-y-0.5 hover:bg-blue-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-white focus-visible:ring-offset-2 focus-visible:ring-offset-[#17365D]">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                            </svg>
                            Buat Inspeksi
                        </a>

                        <a href="{{ route('inspections.index') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/25 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition duration-200 hover:bg-white/15 focus:outline-none focus-visible:ring-2 focus-visible:ring-white">
                            Lihat Semua Inspeksi
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Panel fokus hari ini --}}
                <div class="w-full rounded-2xl border border-white/15 bg-white/10 p-5 backdrop-blur-sm lg:w-72">
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-blue-100/80">
                        Perlu perhatian
                    </p>

                    <div class="mt-3 flex items-end gap-3">
                        <p class="text-4xl font-bold leading-none tracking-tight">
                            {{ number_format($draftInspections) }}
                        </p>
                        <p class="pb-1 text-sm text-blue-100/90">
                            inspeksi draft
                        </p>
                    </div>

                    <div class="mt-4 h-1.5 overflow-hidden rounded-full bg-white/15">
                        <div class="h-full rounded-full bg-amber-300" style="width: {{ $draftPercent }}%"></div>
                    </div>

                    <p class="mt-3 text-xs leading-5 text-blue-100/80">
                        @if ($draftInspections > 0)
                            {{ $draftPercent }}% dari seluruh inspeksi masih berstatus draft dan belum diselesaikan.
                        @else
                            Tidak ada inspeksi draft yang tertunda.
                        @endif
                    </p>
                </div>

            </div>
        </section>


        {{-- ================================
             KARTU STATISTIK
        ================================= --}}
        <section aria-label="Ringkasan inspeksi" class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 xl:gap-5">

            {{-- TOTAL INSPEKSI --}}
            <a href="{{ route('inspections.index') }}"
               class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-900/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">

                <span class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-500 to-sky-400"></span>

                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Inspeksi</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalInspections) }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 ring-1 ring-blue-100 transition group-hover:bg-blue-100">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-4 inline-flex items-center gap-1.5 border-t border-slate-100 pt-3 text-sm font-semibold text-blue-700">
                    Lihat inspeksi
                    <span aria-hidden="true" class="transition group-hover:translate-x-1">&rarr;</span>
                </p>
            </a>


            {{-- INSPEKSI DRAFT --}}
            <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-amber-200 hover:shadow-lg hover:shadow-amber-900/5">

                <span class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-amber-400 to-orange-300"></span>

                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Inspeksi Draft</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($draftInspections) }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-amber-100 transition group-hover:bg-amber-100">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.4-9.4a2 2 0 112.8 2.8L11.8 15H9v-2.8l8.6-8.6z"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-4 border-t border-slate-100 pt-3">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>Porsi dari total inspeksi</span>
                        <span class="font-semibold text-amber-700">{{ $draftPercent }}%</span>
                    </div>
                    <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-amber-400" style="width: {{ $draftPercent }}%"></div>
                    </div>
                </div>
            </div>


            {{-- TOTAL TEMUAN --}}
            <div class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-rose-200 hover:shadow-lg hover:shadow-rose-900/5">

                <span class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-rose-500 to-pink-400"></span>

                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Temuan</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalFindings) }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-rose-50 text-rose-600 ring-1 ring-rose-100 transition group-hover:bg-rose-100">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.3 3.86L1.82 18a2 2 0 001.7 3h16.96a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-4 border-t border-slate-100 pt-3 text-sm text-slate-500">
                    Rata-rata
                    <span class="font-semibold text-rose-700">{{ $findingsAverage }}</span>
                    temuan per inspeksi
                </p>
            </div>


            {{-- TOTAL AKTIVITAS --}}
            <a href="{{ route('activity-logs.index') }}"
               class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-900/5 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500">

                <span class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-400"></span>

                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Aktivitas Tercatat</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">
                            {{ number_format($totalActivities) }}
                        </p>
                    </div>

                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100 transition group-hover:bg-emerald-100">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h4l3-8 4 16 3-8h4"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-4 inline-flex items-center gap-1.5 border-t border-slate-100 pt-3 text-sm font-semibold text-emerald-700">
                    Lihat aktivitas
                    <span aria-hidden="true" class="transition group-hover:translate-x-1">&rarr;</span>
                </p>
            </a>

        </section>


        {{-- ================================
             INSPEKSI TERBARU + AKTIVITAS TERBARU
        ================================= --}}
        <div class="grid items-start gap-6 xl:grid-cols-3">

            {{-- INSPEKSI TERBARU --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">

                <div class="flex flex-col justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:px-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[#28558A] ring-1 ring-blue-100">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-800">Inspeksi Terbaru</h2>
                            <p class="text-sm text-slate-500">Lima inspeksi yang terakhir dibuat.</p>
                        </div>
                    </div>

                    <a href="{{ route('inspections.index') }}"
                       class="inline-flex items-center justify-center gap-1.5 self-start rounded-lg border border-slate-200 px-3.5 py-2 text-sm font-semibold text-[#17365D] transition hover:border-blue-200 hover:bg-blue-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 sm:self-auto">
                        Lihat semua
                        <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">

                        <thead class="bg-slate-50/80">
                            <tr>
                                <th scope="col" class="whitespace-nowrap px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500 sm:px-6">Inspeksi</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Mitra / Gerai</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Lokasi</th>
                                <th scope="col" class="whitespace-nowrap px-5 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500 sm:pr-6">Status</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">

                            @forelse ($latestInspections as $inspection)

                                @php
                                    $status = $inspection->status ?? 'draft';

                                    $statusStyle = match ($status) {
                                        'draft'     => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                        'completed' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                        default     => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                                    };

                                    $statusLabel = match ($status) {
                                        'draft'     => 'Draft',
                                        'completed' => 'Selesai',
                                        default     => ucfirst(str_replace('_', ' ', $status)),
                                    };
                                @endphp

                                <tr class="transition duration-150 hover:bg-blue-50/40">

                                    <td class="whitespace-nowrap px-5 py-4 sm:px-6">
                                        <a href="{{ route('inspections.show', $inspection) }}"
                                           class="text-sm font-bold text-blue-700 transition hover:text-blue-900 hover:underline">
                                            {{ $inspection->inspection_number ?? 'INS-' . $inspection->id }}
                                        </a>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $inspection->inspection_date?->format('d/m/Y') ?? '-' }}
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4">
                                        <p class="text-sm font-semibold text-slate-800">
                                            {{ $inspection->partner_name }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $inspection->brand ?: '-' }}
                                        </p>
                                    </td>

                                    <td class="px-5 py-4 text-sm text-slate-600">
                                        <span class="line-clamp-2 min-w-[8rem] max-w-[14rem]">
                                            {{ $inspection->location ?: '-' }}
                                        </span>
                                    </td>

                                    <td class="whitespace-nowrap px-5 py-4 sm:pr-6">
                                        <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $statusStyle }}">
                                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                                            {{ $statusLabel }}
                                        </span>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="4" class="px-6 py-14 text-center">
                                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                            </svg>
                                        </div>

                                        <h3 class="mt-4 text-sm font-bold text-slate-700">Belum Ada Data Inspeksi</h3>

                                        <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">
                                            Data inspeksi yang dibuat akan muncul di bagian ini.
                                            Mulai dengan membuat inspeksi pertama.
                                        </p>

                                        <a href="{{ route('inspections.create') }}"
                                           class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#17365D] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#28558A]">
                                            <span aria-hidden="true">+</span>
                                            Buat Inspeksi
                                        </a>
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>
                    </table>
                </div>
            </section>


            {{-- AKTIVITAS TERBARU --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="flex items-center justify-between gap-3 border-b border-slate-100 px-5 py-4 sm:px-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 ring-1 ring-indigo-100">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="font-bold text-slate-800">Aktivitas Terbaru</h2>
                            <p class="text-sm text-slate-500">Riwayat terakhir.</p>
                        </div>
                    </div>

                    <a href="{{ route('activity-logs.index') }}"
                       class="shrink-0 text-sm font-semibold text-blue-700 transition hover:text-blue-900 focus:outline-none focus-visible:underline">
                        Semua <span aria-hidden="true">&rarr;</span>
                    </a>
                </div>

                @if ($latestActivities->isNotEmpty())

                    <ol class="px-5 py-5 sm:px-6">

                        @foreach ($latestActivities as $activity)

                            <li class="relative flex gap-4 pb-6 last:pb-0">

                                @unless ($loop->last)
                                    <span aria-hidden="true" class="absolute left-[15px] top-9 h-[calc(100%-2.25rem)] w-px bg-slate-200"></span>
                                @endunless

                                <span class="relative z-10 flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-50 text-[#28558A] ring-4 ring-white">
                                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12h4l3-8 4 16 3-8h4"/>
                                    </svg>
                                </span>

                                <div class="min-w-0 flex-1 pt-0.5">
                                    <p class="break-words text-sm font-semibold leading-5 text-slate-800">
                                        {{ $activity->description ?: ucfirst(str_replace('_', ' ', $activity->activity)) }}
                                    </p>

                                    <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-0.5 text-xs text-slate-500">
                                        <span>{{ $activity->user?->name ?? 'Pengguna tidak tersedia' }}</span>

                                        @if ($activity->inspection)
                                            <span aria-hidden="true" class="text-slate-300">&bull;</span>
                                            <span class="rounded bg-slate-100 px-1.5 py-0.5 font-medium text-slate-600">
                                                {{ $activity->inspection->inspection_number ?? 'INS-' . $activity->inspection->id }}
                                            </span>
                                        @endif
                                    </div>

                                    <p class="mt-1.5 text-xs text-slate-400">
                                        {{ $activity->created_at?->diffForHumans() ?? '-' }}
                                    </p>
                                </div>

                            </li>

                        @endforeach

                    </ol>

                @else

                    <div class="px-6 py-12 text-center">
                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">
                            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <p class="mt-3 text-sm font-semibold text-slate-700">Belum Ada Aktivitas</p>
                        <p class="mt-1 text-sm text-slate-500">Aktivitas pengguna akan tercatat di bagian ini.</p>
                    </div>

                @endif

            </section>

        </div>


        {{-- ================================
             INFORMASI SISTEM
        ================================= --}}
        <section class="flex flex-col gap-4 rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50 via-white to-white p-5 sm:flex-row sm:items-center sm:p-6">

            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-white text-[#28558A] shadow-sm">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 110-20 10 10 0 010 20z"/>
                </svg>
            </div>

            <div>
                <h3 class="font-bold text-slate-800">Informasi Sistem</h3>
                <p class="mt-1 text-sm leading-6 text-slate-600">
                    Pastikan data gerai, checklist, temuan, dokumentasi foto, dan tanda tangan
                    diisi sesuai kondisi aktual saat inspeksi. Riwayat aktivitas membantu
                    menelusuri perubahan yang tercatat dalam sistem.
                </p>
            </div>

        </section>

    </div>
</x-app-layout>
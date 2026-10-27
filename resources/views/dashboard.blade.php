<x-app-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    <div class="space-y-8">

        {{-- ================================
             SAMBUTAN DASHBOARD
        ================================= --}}
        <section class="relative isolate overflow-hidden rounded-2xl bg-gradient-to-r from-[#17365D] via-[#204675] to-[#28558A] p-6 text-white shadow-lg sm:p-8 lg:p-10">

            {{-- Dekorasi background --}}
            <div class="pointer-events-none absolute -right-12 -top-20 -z-10 h-64 w-64 rounded-full bg-white/5"></div>
            <div class="pointer-events-none absolute -bottom-28 right-32 -z-10 h-52 w-52 rounded-full bg-white/5"></div>

            <div class="relative flex flex-col justify-between gap-8 md:flex-row md:items-center">

                <div class="max-w-2xl">

                    <div class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-3 py-1.5">
                        <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                        <span class="text-xs font-semibold tracking-wider text-blue-100">
                            SISTEM CHECKLIST INSPEKSI GERAI
                        </span>
                    </div>

                    <h1 class="mt-5 text-2xl font-bold leading-tight tracking-tight sm:text-3xl lg:text-4xl">
                        Selamat datang, {{ Auth::user()->name }}!
                    </h1>

                    <p class="mt-4 max-w-xl text-sm leading-7 text-blue-100 sm:text-base">
                        Pantau kegiatan inspeksi, kelola checklist pemeriksaan,
                        serta tinjau temuan dan riwayat aktivitas melalui satu
                        dashboard yang terintegrasi.
                    </p>

                    <div class="mt-7 flex flex-wrap gap-3">

                        <a href="{{ route('inspections.create') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-[#17365D] shadow-sm transition duration-200 hover:-translate-y-0.5 hover:bg-blue-50 hover:shadow-md">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 5v14m-7-7h14"/>
                            </svg>

                            Buat Inspeksi
                        </a>

                        <a href="{{ route('inspections.index') }}"
                           class="inline-flex items-center justify-center gap-2 rounded-xl border border-white/30 bg-white/5 px-5 py-3 text-sm font-semibold text-white transition duration-200 hover:bg-white/15">

                            Lihat Semua Inspeksi

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-4 w-4"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>

                    </div>
                </div>

                {{-- Ikon utama --}}
                <div class="hidden shrink-0 md:block">
                    <div class="flex h-32 w-32 items-center justify-center rounded-3xl border border-white/15 bg-white/10 shadow-inner lg:h-36 lg:w-36">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-20 w-20 text-white"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.2">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12l2 2 4-4"/>
                        </svg>

                    </div>
                </div>

            </div>
        </section>


        {{-- ================================
             JUDUL RINGKASAN
        ================================= --}}
        <section class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">

            <div>
                <div class="flex items-center gap-2">
                    <span class="h-6 w-1 rounded-full bg-[#28558A]"></span>

                    <h2 class="text-lg font-bold text-slate-800 sm:text-xl">
                        Ringkasan Inspeksi
                    </h2>
                </div>

                <p class="mt-2 text-sm text-slate-500">
                    Informasi terkini berdasarkan data sistem.
                </p>
            </div>

            <div class="inline-flex items-center gap-2 self-start rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm text-slate-600 shadow-sm sm:self-auto">

                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5 text-[#28558A]"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.7">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          d="M8 7V3m8 4V3M4 11h16M5 5h14a1 1 0 011 1v14H4V6a1 1 0 011-1z"/>
                </svg>

                <span class="font-medium">
                    {{ now()->translatedFormat('l, d F Y') }}
                </span>

            </div>

        </section>


        {{-- ================================
             KARTU STATISTIK
        ================================= --}}
        <section class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-4">

            {{-- TOTAL INSPEKSI --}}
            <div class="group flex min-h-56 flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg">

                <div class="flex items-center justify-between gap-3">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700 transition group-hover:bg-blue-100">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>

                    </div>

                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                        Semua Data
                    </span>

                </div>

                <div class="mt-6">
                    <p class="text-sm font-medium text-slate-500">
                        Total Inspeksi
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-800">
                        {{ number_format($totalInspections) }}
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a href="{{ route('inspections.index') }}"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-blue-700 transition hover:gap-3 hover:text-blue-900">
                        Lihat inspeksi
                        <span>&rarr;</span>
                    </a>
                </div>

            </div>


            {{-- INSPEKSI DRAFT --}}
            <div class="group flex min-h-56 flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-amber-200 hover:shadow-lg">

                <div class="flex items-center justify-between">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600 transition group-hover:bg-amber-100">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>

                    </div>

                    <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                        Dalam Proses
                    </span>

                </div>

                <div class="mt-6">
                    <p class="text-sm font-medium text-slate-500">
                        Inspeksi Draft
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-800">
                        {{ number_format($draftInspections) }}
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <p class="text-sm text-slate-400">
                        Inspeksi berstatus draft
                    </p>
                </div>

            </div>


            {{-- TOTAL TEMUAN --}}
            <div class="group flex min-h-56 flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-rose-200 hover:shadow-lg">

                <div class="flex items-center justify-between">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-rose-50 text-rose-600 transition group-hover:bg-rose-100">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 9v4m0 4h.01M10.3 3.86L1.82 18a2 2 0 001.7 3h16.96a2 2 0 001.7-3L13.7 3.86a2 2 0 00-3.4 0z"/>
                        </svg>

                    </div>

                    <span class="rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-700">
                        Temuan
                    </span>

                </div>

                <div class="mt-6">
                    <p class="text-sm font-medium text-slate-500">
                        Total Temuan
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-800">
                        {{ number_format($totalFindings) }}
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <p class="text-sm text-slate-400">
                        Seluruh temuan yang tercatat
                    </p>
                </div>

            </div>


            {{-- TOTAL AKTIVITAS --}}
            <div class="group flex min-h-56 flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-emerald-200 hover:shadow-lg">

                <div class="flex items-center justify-between">

                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700 transition group-hover:bg-emerald-100">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-6 w-6"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>

                    </div>

                    <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700">
                        Riwayat
                    </span>

                </div>

                <div class="mt-6">
                    <p class="text-sm font-medium text-slate-500">
                        Aktivitas Tercatat
                    </p>

                    <p class="mt-2 text-3xl font-bold tracking-tight text-slate-800">
                        {{ number_format($totalActivities) }}
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a href="{{ route('activity-logs.index') }}"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 transition hover:gap-3 hover:text-emerald-900">
                        Lihat aktivitas
                        <span>&rarr;</span>
                    </a>
                </div>

            </div>

        </section>


        {{-- ================================
             INSPEKSI TERBARU
        ================================= --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Header tabel --}}
            <div class="flex flex-col justify-between gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:px-6">

                <div>
                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-[#28558A]">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.7">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>

                        <div>
                            <h2 class="font-bold text-slate-800">
                                Inspeksi Terbaru
                            </h2>

                            <p class="mt-1 text-sm text-slate-500">
                                Lima inspeksi yang terakhir dibuat.
                            </p>
                        </div>

                    </div>
                </div>

                <a href="{{ route('inspections.index') }}"
                   class="inline-flex items-center justify-center gap-2 self-start rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-[#17365D] transition hover:border-blue-200 hover:bg-blue-50 sm:self-auto">
                    Lihat semua
                    <span>&rarr;</span>
                </a>

            </div>


            {{-- Isi tabel --}}
            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-slate-100">

                    <thead class="bg-slate-50/80">
                        <tr>
                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Nomor Inspeksi
                            </th>

                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Mitra / Gerai
                            </th>

                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Lokasi
                            </th>

                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Tanggal
                            </th>

                            <th class="whitespace-nowrap px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y divide-slate-100 bg-white">

                        @forelse ($latestInspections as $inspection)

                            <tr class="transition duration-150 hover:bg-blue-50/40">

                                {{-- Nomor inspeksi --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <a href="{{ route('inspections.show', $inspection) }}"
                                       class="text-sm font-bold text-blue-700 transition hover:text-blue-900 hover:underline">
                                        {{ $inspection->inspection_number ?? 'INS-' . $inspection->id }}
                                    </a>

                                </td>


                                {{-- Mitra dan gerai --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    <p class="text-sm font-semibold text-slate-800">
                                        {{ $inspection->partner_name }}
                                    </p>

                                    <p class="mt-1 text-xs text-slate-500">
                                        {{ $inspection->brand ?: '-' }}
                                    </p>

                                </td>


                                {{-- Lokasi --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $inspection->location ?: '-' }}
                                </td>


                                {{-- Tanggal --}}
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    {{ $inspection->inspection_date?->format('d/m/Y') ?? '-' }}
                                </td>


                                {{-- Status --}}
                                <td class="whitespace-nowrap px-6 py-4">

                                    @php
                                        $status = $inspection->status ?? 'draft';

                                        $statusStyle = match ($status) {
                                            'draft' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                            'selesai' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                            'berjalan' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                            default => 'bg-slate-100 text-slate-600 ring-slate-500/20',
                                        };
                                    @endphp

                                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset {{ $statusStyle }}">

                                        <span class="h-1.5 w-1.5 rounded-full bg-current"></span>

                                        {{ ucfirst(str_replace('_', ' ', $status)) }}

                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5" class="px-6 py-14 text-center">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">

                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             class="h-7 w-7"
                                             fill="none"
                                             viewBox="0 0 24 24"
                                             stroke="currentColor"
                                             stroke-width="1.7">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>

                                    </div>

                                    <h3 class="mt-4 text-sm font-bold text-slate-700">
                                        Belum Ada Data Inspeksi
                                    </h3>

                                    <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-slate-500">
                                        Data inspeksi yang dibuat akan muncul di bagian ini.
                                        Mulai dengan membuat inspeksi pertama.
                                    </p>

                                    <a href="{{ route('inspections.create') }}"
                                       class="mt-5 inline-flex items-center gap-2 rounded-xl bg-[#17365D] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#28558A]">

                                        <span>+</span>
                                        Buat Inspeksi

                                    </a>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>


        {{-- ================================
             AKTIVITAS TERBARU
        ================================= --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Header aktivitas --}}
            <div class="flex flex-col justify-between gap-4 border-b border-slate-100 px-5 py-5 sm:flex-row sm:items-center sm:px-6">

                <div class="flex items-center gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700">

                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="1.7">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>

                    </div>

                    <div>
                        <h2 class="font-bold text-slate-800">
                            Aktivitas Terbaru
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Riwayat aktivitas yang tercatat dalam sistem.
                        </p>
                    </div>

                </div>

                <a href="{{ route('activity-logs.index') }}"
                   class="inline-flex items-center gap-2 self-start text-sm font-semibold text-blue-700 transition hover:text-blue-900 sm:self-auto">
                    Lihat semua
                    <span>&rarr;</span>
                </a>

            </div>


            {{-- Daftar aktivitas --}}
            <div class="divide-y divide-slate-100">

                @forelse ($latestActivities as $activity)

                    <div class="flex gap-4 px-5 py-5 transition hover:bg-slate-50/70 sm:px-6">

                        {{-- Ikon aktivitas --}}
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[#28558A]">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.7">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>

                        </div>


                        {{-- Informasi aktivitas --}}
                        <div class="min-w-0 flex-1">

                            <p class="break-words text-sm font-semibold leading-6 text-slate-800">
                                {{ $activity->description ?: ucfirst(str_replace('_', ' ', $activity->activity)) }}
                            </p>

                            <div class="mt-1 flex flex-wrap items-center gap-x-2 gap-y-1 text-sm text-slate-500">

                                <span>
                                    {{ $activity->user?->name ?? 'Pengguna tidak tersedia' }}
                                </span>

                                @if ($activity->inspection)

                                    <span class="text-slate-300">&bull;</span>

                                    <span>
                                        {{ $activity->inspection->inspection_number ?? 'INS-' . $activity->inspection->id }}
                                    </span>

                                @endif

                            </div>

                            <p class="mt-2 text-xs text-slate-400">
                                {{ $activity->created_at?->diffForHumans() ?? '-' }}
                            </p>

                        </div>

                    </div>

                @empty

                    <div class="px-6 py-12 text-center">

                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-slate-100 text-slate-400">

                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="1.7">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>

                        </div>

                        <p class="mt-3 text-sm font-semibold text-slate-700">
                            Belum Ada Aktivitas
                        </p>

                        <p class="mt-1 text-sm text-slate-500">
                            Aktivitas pengguna akan tercatat di bagian ini.
                        </p>

                    </div>

                @endforelse

            </div>

        </section>


        {{-- ================================
             INFORMASI SISTEM
        ================================= --}}
        <section class="overflow-hidden rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50 to-white">

            <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-start sm:p-6">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-white text-[#28558A] shadow-sm">

                    <svg xmlns="http://www.w3.org/2000/svg"
                         class="h-6 w-6"
                         fill="none"
                         viewBox="0 0 24 24"
                         stroke="currentColor"
                         stroke-width="1.7">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 110-20 10 10 0 010 20z"/>
                    </svg>

                </div>

                <div>

                    <h3 class="font-bold text-slate-800">
                        Informasi Sistem
                    </h3>

                    <p class="mt-2 text-sm leading-7 text-slate-600">
                        Pastikan data gerai, checklist, temuan, dokumentasi foto,
                        dan tanda tangan diisi sesuai kondisi aktual saat inspeksi.
                        Riwayat aktivitas membantu menelusuri perubahan yang tercatat
                        dalam sistem.
                    </p>

                </div>

            </div>

        </section>

    </div>
</x-app-layout>
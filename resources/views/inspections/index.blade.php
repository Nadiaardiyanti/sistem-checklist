<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-800">
                    Monitoring Operasional Gerai
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Kelola dan pantau seluruh kegiatan inspeksi gerai.
                </p>
            </div>

            <a href="{{ route('inspections.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#17365D] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#28558A] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#28558A] focus-visible:ring-offset-2">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                </svg>
                Buat Inspeksi Baru
            </a>
        </div>
    </x-slot>

    @php
        // Satu query untuk semua angka ringkasan
        $counts = \App\Models\Inspection::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $totalCount     = (int) $counts->sum();
        $draftCount     = (int) ($counts['draft'] ?? 0);
        $completedCount = (int) ($counts['completed'] ?? 0);
        $completedPct   = $totalCount > 0 ? (int) round($completedCount / $totalCount * 100) : 0;
    @endphp

    <div class="space-y-6 lg:space-y-8">

        {{-- Notifikasi berhasil --}}
        @if (session('success'))
            <div role="status" class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="mt-0.5 h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Notifikasi kesalahan --}}
        @if ($errors->any())
            <div role="alert" class="rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-800">
                <p class="font-semibold">Ada data yang perlu diperiksa.</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        {{-- ================================
             RINGKASAN
        ================================= --}}
        <section aria-label="Ringkasan inspeksi" class="grid grid-cols-1 gap-4 sm:grid-cols-3 xl:gap-5">

            {{-- Total --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <span class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-blue-500 to-sky-400"></span>

                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Total Inspeksi</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($totalCount) }}</p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 ring-1 ring-blue-100">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-4 border-t border-slate-100 pt-3 text-sm text-slate-500">Seluruh data inspeksi gerai</p>
            </div>

            {{-- Draft --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <span class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-amber-400 to-orange-300"></span>

                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Sedang Dikerjakan</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($draftCount) }}</p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600 ring-1 ring-amber-100">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.4-9.4a2 2 0 112.8 2.8L11.8 15H9v-2.8l8.6-8.6z"/>
                        </svg>
                    </div>
                </div>

                <p class="mt-4 border-t border-slate-100 pt-3 text-sm text-slate-500">Inspeksi berstatus draft</p>
            </div>

            {{-- Selesai --}}
            <div class="relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <span class="absolute inset-x-0 top-0 h-1 bg-gradient-to-r from-emerald-500 to-teal-400"></span>

                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-medium text-slate-500">Inspeksi Selesai</p>
                        <p class="mt-2 text-3xl font-bold tracking-tight text-slate-900">{{ number_format($completedCount) }}</p>
                    </div>
                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 ring-1 ring-emerald-100">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="mt-4 border-t border-slate-100 pt-3">
                    <div class="flex items-center justify-between text-xs text-slate-500">
                        <span>Tingkat penyelesaian</span>
                        <span class="font-semibold text-emerald-700">{{ $completedPct }}%</span>
                    </div>
                    <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-emerald-500" style="width: {{ $completedPct }}%"></div>
                    </div>
                </div>
            </div>

        </section>


        {{-- ================================
             RIWAYAT INSPEKSI
        ================================= --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-[#28558A] ring-1 ring-blue-100">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Riwayat Inspeksi</h3>
                        <p class="text-sm text-slate-500">Data inspeksi gerai yang tercatat dalam sistem.</p>
                    </div>
                </div>

                <span class="inline-flex w-fit items-center rounded-full bg-blue-50 px-3 py-1.5 text-xs font-semibold text-[#17365D] ring-1 ring-inset ring-blue-100">
                    {{ number_format($inspections->total()) }} data
                </span>
            </div>

            @if ($inspections->count())

                {{-- Tabel (tablet ke atas) --}}
                <div class="hidden overflow-x-auto md:block">
                    <table class="min-w-full divide-y divide-slate-100">

                        <thead class="bg-slate-50/80">
                            <tr>
                                <th scope="col" class="whitespace-nowrap px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Inspeksi</th>
                                <th scope="col" class="whitespace-nowrap px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Mitra / Brand</th>
                                <th scope="col" class="whitespace-nowrap px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Lokasi Gerai</th>
                                <th scope="col" class="whitespace-nowrap px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Tanggal</th>
                                <th scope="col" class="whitespace-nowrap px-6 py-3 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">Status</th>
                                <th scope="col" class="whitespace-nowrap px-6 py-3 text-right text-[11px] font-semibold uppercase tracking-wider text-slate-500">Aksi</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($inspections as $inspection)
                                <tr class="transition duration-150 hover:bg-blue-50/40">

                                    <td class="whitespace-nowrap px-6 py-4">
                                        <a href="{{ route('inspections.show', $inspection) }}"
                                           class="text-sm font-bold text-[#17365D] transition hover:text-blue-700 hover:underline">
                                            {{ $inspection->inspection_number ?? 'INS-' . $inspection->id }}
                                        </a>
                                        <p class="mt-1 text-xs text-slate-400">ID #{{ $inspection->id }}</p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="text-sm font-semibold text-slate-800">{{ $inspection->partner_name }}</p>
                                        <p class="mt-1 text-xs text-slate-500">{{ $inspection->brand ?: 'Brand belum diisi' }}</p>
                                    </td>

                                    <td class="px-6 py-4">
                                        <p class="line-clamp-2 max-w-[16rem] text-sm text-slate-700">{{ $inspection->location }}</p>
                                        <p class="mt-1.5">
                                            <span class="inline-flex items-center rounded bg-slate-100 px-1.5 py-0.5 text-[11px] font-medium text-slate-600">
                                                Ruang: {{ $inspection->room_code ?: '-' }}
                                            </span>
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                        {{ $inspection->inspection_date?->format('d M Y') ?? '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4">
                                        @if ($inspection->status === 'completed')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                Draft
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-2">
                                            <a href="{{ route('inspections.show', $inspection) }}"
                                               class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-[#17365D] focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                                Detail
                                            </a>

                                            <a href="{{ route('inspections.edit', $inspection) }}"
                                               class="inline-flex items-center justify-center rounded-lg bg-[#17365D] px-3 py-2 text-xs font-semibold text-white transition hover:bg-[#28558A] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#28558A] focus-visible:ring-offset-1">
                                                Edit
                                            </a>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                {{-- Daftar kartu (mobile) --}}
                <ul class="divide-y divide-slate-100 md:hidden">
                    @foreach ($inspections as $inspection)
                        <li class="space-y-3 px-5 py-4">

                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <a href="{{ route('inspections.show', $inspection) }}"
                                       class="text-sm font-bold text-[#17365D] hover:underline">
                                        {{ $inspection->inspection_number ?? 'INS-' . $inspection->id }}
                                    </a>
                                    <p class="mt-0.5 text-xs text-slate-400">
                                        {{ $inspection->inspection_date?->format('d M Y') ?? '-' }}
                                    </p>
                                </div>

                                @if ($inspection->status === 'completed')
                                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai
                                    </span>
                                @else
                                    <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                        Draft
                                    </span>
                                @endif
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $inspection->partner_name }}</p>
                                <p class="text-xs text-slate-500">{{ $inspection->brand ?: 'Brand belum diisi' }}</p>
                                <p class="mt-1.5 text-sm text-slate-600">{{ $inspection->location }}</p>
                                <p class="mt-1">
                                    <span class="inline-flex items-center rounded bg-slate-100 px-1.5 py-0.5 text-[11px] font-medium text-slate-600">
                                        Ruang: {{ $inspection->room_code ?: '-' }}
                                    </span>
                                </p>
                            </div>

                            <div class="grid grid-cols-2 gap-2">
                                <a href="{{ route('inspections.show', $inspection) }}"
                                   class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2.5 text-xs font-semibold text-slate-700 transition hover:bg-blue-50">
                                    Detail
                                </a>
                                <a href="{{ route('inspections.edit', $inspection) }}"
                                   class="inline-flex items-center justify-center rounded-lg bg-[#17365D] px-3 py-2.5 text-xs font-semibold text-white transition hover:bg-[#28558A]">
                                    Edit
                                </a>
                            </div>

                        </li>
                    @endforeach
                </ul>

                {{-- Paginasi --}}
                <div class="flex flex-col gap-3 border-t border-slate-100 bg-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <p class="text-sm text-slate-500">
                        Menampilkan
                        <span class="font-semibold text-slate-700">{{ $inspections->firstItem() }}&ndash;{{ $inspections->lastItem() }}</span>
                        dari
                        <span class="font-semibold text-slate-700">{{ $inspections->total() }}</span>
                        inspeksi
                    </p>

                    @if ($inspections->hasPages())
                        <div>{{ $inspections->links() }}</div>
                    @endif
                </div>

            @else

                <div class="px-6 py-16 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-[#17365D]">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 12l2 2 4-4"/>
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-slate-800">Belum ada inspeksi</h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Data inspeksi yang dibuat akan muncul di halaman ini.
                        Mulai dengan menambahkan inspeksi gerai baru.
                    </p>

                    <a href="{{ route('inspections.create') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#17365D] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#28558A]">
                        <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14m-7-7h14"/>
                        </svg>
                        Buat Inspeksi Baru
                    </a>
                </div>

            @endif
        </section>


        {{-- Informasi tambahan --}}
        <section class="flex flex-col gap-4 rounded-2xl border border-blue-100 bg-gradient-to-r from-blue-50 via-white to-white p-5 sm:flex-row sm:items-center sm:p-6">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-blue-100 bg-white text-[#28558A] shadow-sm">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 110-20 10 10 0 010 20z"/>
                </svg>
            </div>

            <div>
                <h4 class="font-bold text-slate-800">Informasi Sistem</h4>
                <p class="mt-1 text-sm leading-6 text-slate-600">
                    Gunakan tombol Detail untuk melihat data dan hasil pemeriksaan.
                    Gunakan tombol Edit untuk memperbarui informasi inspeksi.
                </p>
            </div>
        </section>

    </div>
</x-app-layout>
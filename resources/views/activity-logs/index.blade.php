@php
    $users = $users ?? collect();

    // Nilai `activity` mengikuti yang disimpan InspectionController: label, ikon, warna
    $activityMeta = [
        'created' => [
            'label' => 'Membuat inspeksi',
            'icon'  => 'M12 4v16m8-8H4',
            'tone'  => 'bg-emerald-50 text-emerald-600 ring-emerald-100',
        ],
        'updated' => [
            'label' => 'Memperbarui inspeksi',
            'icon'  => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
            'tone'  => 'bg-blue-50 text-blue-600 ring-blue-100',
        ],
        'checklist_updated' => [
            'label' => 'Memperbarui checklist',
            'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2M9 12l2 2 4-4',
            'tone'  => 'bg-amber-50 text-amber-600 ring-amber-100',
        ],
        'checklist_item_added' => [
            'label' => 'Menambah kriteria checklist',
            'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01',
            'tone'  => 'bg-teal-50 text-teal-600 ring-teal-100',
        ],
        'finding_added' => [
            'label' => 'Menambahkan temuan',
            'icon'  => 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            'tone'  => 'bg-rose-50 text-rose-600 ring-rose-100',
        ],
        'photo_uploaded' => [
            'label' => 'Mengunggah foto',
            'icon'  => 'M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9zM15 13a3 3 0 11-6 0 3 3 0 016 0z',
            'tone'  => 'bg-violet-50 text-violet-600 ring-violet-100',
        ],
        'signature_saved' => [
            'label' => 'Menyimpan tanda tangan',
            'icon'  => 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
            'tone'  => 'bg-sky-50 text-sky-600 ring-sky-100',
        ],
    ];

    $fallbackMeta = [
        'icon' => 'M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z',
        'tone' => 'bg-slate-100 text-slate-500 ring-slate-200',
    ];

    // Tombol "Buka di Spreadsheet" hanya tampil jika Google Sheets sudah dikonfigurasi
    $sheetUrl = \App\Models\InspectionActivityLog::sheetUrl();

    $filterKeys = ['q', 'activity', 'user', 'dari', 'sampai'];
    $hasFilter = collect(request()->only($filterKeys))->filter(fn ($v) => filled($v))->isNotEmpty();

    $inputClass = 'block w-full rounded-xl border-slate-200 bg-white py-2.5 text-sm text-slate-800 shadow-sm transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100';
    $labelClass = 'mb-1.5 block text-xs font-semibold text-slate-500';

    // Kelompokkan per hari
    $groups = $activityLogs->getCollection()
        ->groupBy(fn ($log) => $log->created_at?->toDateString() ?? 'unknown');
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-800">
                    Log Aktivitas
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Riwayat aktivitas pengguna pada inspeksi gerai.
                </p>
            </div>

            @if ($sheetUrl)
                <a href="{{ $sheetUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-sm font-semibold text-emerald-800 transition hover:bg-emerald-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M3 10h18M3 14h18M9 4v16M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z"/>
                    </svg>
                    Buka di Spreadsheet
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">

        {{-- ==============================
             FILTER
        =============================== --}}
        <form method="GET" action="{{ route('activity-logs.index') }}"
              class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">

                <div class="sm:col-span-2">
                    <label for="q" class="{{ $labelClass }}">Pencarian</label>
                    <div class="relative">
                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-[18px] w-[18px]" fill="none"
                                 viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input id="q" name="q" type="search" value="{{ request('q') }}"
                               placeholder="Nama pengguna, no. inspeksi, mitra, atau detail"
                               class="{{ $inputClass }} pl-10 pr-4">
                    </div>
                </div>

                <div>
                    <label for="activity" class="{{ $labelClass }}">Jenis aktivitas</label>
                    <select id="activity" name="activity" class="{{ $inputClass }} px-3.5">
                        <option value="">Semua aktivitas</option>
                        @foreach ($activityMeta as $key => $meta)
                            <option value="{{ $key }}" @selected(request('activity') === $key)>{{ $meta['label'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="user" class="{{ $labelClass }}">Pengguna</label>
                    <select id="user" name="user" class="{{ $inputClass }} px-3.5">
                        <option value="">Semua pengguna</option>
                        @foreach ($users as $u)
                            <option value="{{ $u->id }}" @selected((string) request('user') === (string) $u->id)>{{ $u->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="dari" class="{{ $labelClass }}">Dari tanggal</label>
                    <input id="dari" name="dari" type="date" value="{{ request('dari') }}"
                           class="{{ $inputClass }} px-3.5">
                </div>

                <div>
                    <label for="sampai" class="{{ $labelClass }}">Sampai tanggal</label>
                    <input id="sampai" name="sampai" type="date" value="{{ request('sampai') }}"
                           class="{{ $inputClass }} px-3.5">
                </div>

                <div class="flex items-end gap-3 sm:col-span-2">
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#17365D] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#204675] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Terapkan
                    </button>

                    @if ($hasFilter)
                        <a href="{{ route('activity-logs.index') }}"
                           class="rounded-xl px-4 py-2.5 text-sm font-semibold text-slate-500 transition hover:bg-slate-100 hover:text-slate-800">
                            Reset filter
                        </a>
                    @endif
                </div>
            </div>

            @if ($errors->any())
                <p class="mt-4 text-sm text-red-600">{{ $errors->first() }}</p>
            @endif
        </form>

        {{-- ==============================
             DAFTAR AKTIVITAS
        =============================== --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 px-5 py-4 sm:px-6">
                <h3 class="text-base font-bold text-slate-800">Daftar Aktivitas</h3>

                @if ($activityLogs->total() > 0)
                    <p class="text-sm text-slate-500">
                        Menampilkan
                        <span class="font-semibold text-slate-700">{{ $activityLogs->firstItem() }}–{{ $activityLogs->lastItem() }}</span>
                        dari
                        <span class="font-semibold text-slate-700">{{ number_format($activityLogs->total(), 0, ',', '.') }}</span>
                        aktivitas
                    </p>
                @endif
            </div>

            @forelse ($groups as $date => $logs)
                @php
                    $day = $date === 'unknown' ? null : \Illuminate\Support\Carbon::parse($date)->locale('id');

                    $dayLabel = match (true) {
                        $day === null          => 'Tanggal tidak diketahui',
                        $day->isToday()        => 'Hari ini',
                        $day->isYesterday()    => 'Kemarin',
                        default                => $day->translatedFormat('l, d F Y'),
                    };
                @endphp

                {{-- Penanda hari --}}
                <div class="flex items-center justify-between gap-3 border-b border-t border-slate-100 bg-slate-50 px-5 py-2.5 first:border-t-0 sm:px-6">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">
                        {{ $dayLabel }}
                        @if ($day && ($day->isToday() || $day->isYesterday()))
                            <span class="ml-1 font-medium normal-case tracking-normal text-slate-400">
                                &middot; {{ $day->translatedFormat('d F Y') }}
                            </span>
                        @endif
                    </p>
                    <span class="text-xs font-semibold text-slate-400">{{ $logs->count() }} aktivitas</span>
                </div>

                <ul class="divide-y divide-slate-100">
                    @foreach ($logs as $log)
                        @php
                            $meta = $activityMeta[$log->activity] ?? null;
                            $label = $meta['label'] ?? \Illuminate\Support\Str::headline(str_replace('_', ' ', (string) $log->activity));
                            $icon = $meta['icon'] ?? $fallbackMeta['icon'];
                            $tone = $meta['tone'] ?? $fallbackMeta['tone'];
                        @endphp

                        <li class="flex items-start gap-4 px-5 py-4 transition hover:bg-slate-50/70 sm:px-6">

                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl ring-1 {{ $tone }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                     stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/>
                                </svg>
                            </span>

                            <div class="min-w-0 flex-1">
                                <p class="text-sm leading-6 text-slate-500">
                                    <span class="font-semibold text-slate-800">{{ $log->user?->name ?? 'Pengguna tidak ditemukan' }}</span>
                                    {{ lcfirst($label) }}
                                </p>

                                @if ($log->description)
                                    <p class="mt-0.5 text-sm leading-6 text-slate-600">{{ $log->description }}</p>
                                @endif

                                @if ($log->inspection)
                                    <a href="{{ route('inspections.show', $log->inspection) }}"
                                       class="mt-2 inline-flex max-w-full items-center gap-1.5 rounded-lg bg-blue-50 px-2.5 py-1 text-xs font-semibold text-blue-700 transition hover:bg-blue-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                        </svg>
                                        <span class="truncate">
                                            {{ $log->inspection->inspection_number ?: 'Inspeksi #' . $log->inspection->id }}
                                            @if ($log->inspection->partner_name)
                                                &middot; {{ $log->inspection->partner_name }}
                                            @endif
                                        </span>
                                    </a>
                                @endif
                            </div>

                            <time class="shrink-0 pt-0.5 text-right text-xs font-medium text-slate-400"
                                  datetime="{{ $log->created_at?->toIso8601String() }}"
                                  title="{{ $log->created_at?->format('d/m/Y H:i') }} WIB">
                                {{ $log->created_at?->format('H:i') ?? '-' }}
                                <span class="hidden sm:inline">WIB</span>
                            </time>
                        </li>
                    @endforeach
                </ul>
            @empty
                {{-- Empty state --}}
                <div class="px-6 py-14 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-slate-400">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="1.6" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    @if ($hasFilter)
                        <h3 class="mt-4 text-base font-bold text-slate-800">Tidak ada aktivitas yang cocok</h3>
                        <p class="mt-1 text-sm text-slate-500">Coba ubah kata kunci atau rentang tanggal filter Anda.</p>
                        <a href="{{ route('activity-logs.index') }}"
                           class="mt-5 inline-flex rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Reset filter
                        </a>
                    @else
                        <h3 class="mt-4 text-base font-bold text-slate-800">Belum ada aktivitas</h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Aktivitas akan muncul di sini setelah ada inspeksi yang dibuat atau diperbarui.
                        </p>
                    @endif
                </div>
            @endforelse

            @if ($activityLogs->hasPages())
                <div class="border-t border-slate-100 px-5 py-4 sm:px-6">
                    {{ $activityLogs->links() }}
                </div>
            @endif
        </section>
    </div>
</x-app-layout>
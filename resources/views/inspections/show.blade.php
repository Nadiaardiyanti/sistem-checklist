<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-slate-800">
                    Detail Inspeksi Gerai
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Nomor inspeksi:
                    <span class="font-semibold text-[#17365D]">
                        {{ $inspection->inspection_number ?? 'INS-' . $inspection->id }}
                    </span>
                </p>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('inspections.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Kembali
                </a>

                <a href="{{ route('inspections.pdf', $inspection) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M5 17v3h14v-3"/>
                    </svg>
                    Download PDF
                </a>

                <a href="{{ route('inspections.edit', $inspection) }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#17365D] px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#28558A] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#28558A] focus-visible:ring-offset-2">
                    <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.4-9.4a2 2 0 112.8 2.8L11.8 15H9v-2.8l8.6-8.6z"/>
                    </svg>
                    Edit Inspeksi
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6 lg:space-y-8">

            {{-- Navigasi cepat antar bagian --}}
            <nav aria-label="Navigasi bagian halaman"
                 class="sticky top-24 z-20 -mx-1 flex gap-1.5 overflow-x-auto rounded-2xl border border-slate-200 bg-white/90 p-1.5 shadow-sm backdrop-blur [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                @foreach ([
                    '#informasi'    => 'Informasi',
                    '#ringkasan'    => 'Ringkasan',
                    '#checklist'    => 'Checklist',
                    '#temuan'       => 'Temuan',
                    '#tanda-tangan' => 'Tanda Tangan',
                    '#foto'         => 'Foto',
                ] as $anchor => $anchorLabel)
                    <a href="{{ $anchor }}"
                       class="whitespace-nowrap rounded-xl px-3.5 py-2 text-sm font-medium text-slate-600 transition hover:bg-blue-50 hover:text-[#17365D] focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                        {{ $anchorLabel }}
                        @if ($anchor === '#temuan' && $findings->count() > 0)
                            <span class="ml-1 rounded-full bg-rose-100 px-1.5 py-0.5 text-[11px] font-semibold text-rose-700">{{ $findings->count() }}</span>
                        @elseif ($anchor === '#foto' && $photos->count() > 0)
                            <span class="ml-1 rounded-full bg-slate-100 px-1.5 py-0.5 text-[11px] font-semibold text-slate-600">{{ $photos->count() }}</span>
                        @endif
                    </a>
                @endforeach
            </nav>

            {{-- Notifikasi --}}
            @if (session('success'))
                <div role="alert"
                     class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div role="alert"
                     class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div role="alert"
                     class="rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-800">
                    <p class="mb-2 font-semibold">Terdapat kesalahan:</p>
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Informasi Inspeksi --}}
            <section id="informasi" class="scroll-mt-40 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-base font-bold text-slate-800">
                                Informasi Inspeksi
                            </h3>
                            <p class="mt-1 text-sm text-slate-500">
                                Informasi umum mengenai pelaksanaan inspeksi gerai.
                            </p>
                        </div>

                        @php
                            $status = $inspection->status ?? 'draft';

                            $statusClass = match ($status) {
                                'completed'   => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                'in_progress' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                'cancelled'   => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                default       => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                            };

                            $statusLabel = match ($status) {
                                'completed'   => 'Selesai',
                                'in_progress' => 'Berjalan',
                                'cancelled'   => 'Dibatalkan',
                                'draft'       => 'Draft',
                                default       => ucfirst(str_replace('_', ' ', $status)),
                            };
                        @endphp

                        <span class="inline-flex w-fit items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset {{ $statusClass }}">
                            <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-x-8 gap-y-6 p-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Nomor Inspeksi</p>
                        <p class="mt-1 break-words font-semibold text-slate-900">
                            {{ $inspection->inspection_number ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Nama Mitra</p>
                        <p class="mt-1 break-words font-medium text-slate-900">
                            {{ $inspection->partner_name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Brand</p>
                        <p class="mt-1 break-words font-medium text-slate-900">
                            {{ $inspection->brand ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Kode Ruangan</p>
                        <p class="mt-1 font-medium text-slate-900">
                            {{ $inspection->room_code ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Kategori</p>
                        <p class="mt-1 font-medium text-slate-900">
                            {{ $inspection->category ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Lokasi</p>
                        <p class="mt-1 break-words font-medium text-slate-900">
                            {{ $inspection->location ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Jenis Inspeksi</p>
                        <p class="mt-1 font-medium text-slate-900">
                            {{ $inspection->inspection_type ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Tanggal Inspeksi</p>
                        <p class="mt-1 font-medium text-slate-900">
                            {{ $inspection->inspection_date?->format('d/m/Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Waktu Inspeksi</p>
                        <p class="mt-1 font-medium text-slate-900">
                            {{ $inspection->inspection_time ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Nama Inspektor</p>
                        <p class="mt-1 font-medium text-slate-900">
                            {{ $inspection->inspector_name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Jabatan Inspektor</p>
                        <p class="mt-1 font-medium text-slate-900">
                            {{ $inspection->inspector_position ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Terminal</p>
                        <p class="mt-1 font-medium text-slate-900">
                            {{ $inspection->terminal ?? '-' }}
                        </p>
                    </div>

                    <div class="rounded-xl bg-slate-50 p-4 sm:col-span-2 lg:col-span-3">
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">Catatan Umum</p>
                        <p class="mt-1 whitespace-pre-line text-sm leading-6 text-slate-700">{{ $inspection->notes ?: 'Tidak ada catatan umum.' }}</p>
                    </div>
                </div>
            </section>

            {{-- Ringkasan Checklist --}}
            @php
                $totalChecklist = $checklistItems->count();
                $totalSesuai = $checklistItems->where('status', 'sesuai')->count();
                $totalTidakSesuai = $checklistItems->where('status', 'tidak_sesuai')->count();
                $totalTidakBerlaku = $checklistItems->where('status', 'tidak_berlaku')->count();
                $totalBelumDiperiksa = $checklistItems->filter(
                    fn ($item) => !in_array(
                        $item->status,
                        ['sesuai', 'tidak_sesuai', 'tidak_berlaku'],
                        true
                    )
                )->count();

                $totalSelesai = $totalChecklist - $totalBelumDiperiksa;
                $persentaseChecklist = $totalChecklist > 0
                    ? round(($totalSelesai / $totalChecklist) * 100)
                    : 0;
            @endphp

            <section id="ringkasan" class="scroll-mt-40 space-y-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        Ringkasan Pemeriksaan
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Pantau progres pemeriksaan seluruh kriteria inspeksi.
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
                    <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-sm font-medium text-slate-500">Total Kriteria</p>
                        <p class="mt-2 text-3xl font-bold text-[#17365D]">
                            {{ $totalChecklist }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                        <p class="text-sm font-medium text-emerald-700">Sesuai</p>
                        <p class="mt-2 text-3xl font-bold text-emerald-700">
                            {{ $totalSesuai }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5">
                        <p class="text-sm font-medium text-rose-700">Tidak Sesuai</p>
                        <p class="mt-2 text-3xl font-bold text-rose-700">
                            {{ $totalTidakSesuai }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-white p-5">
                        <p class="text-sm font-medium text-slate-600">Tidak Berlaku</p>
                        <p class="mt-2 text-3xl font-bold text-slate-700">
                            {{ $totalTidakBerlaku }}
                        </p>
                    </div>

                    <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">
                        <p class="text-sm font-medium text-amber-700">Belum Diperiksa</p>
                        <p class="mt-2 text-3xl font-bold text-amber-700">
                            {{ $totalBelumDiperiksa }}
                        </p>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <div>
                            <p class="font-semibold text-slate-800">Progres Checklist</p>
                            <p class="mt-1 text-xs text-slate-500">
                                {{ $totalSelesai }} dari {{ $totalChecklist }} kriteria telah diperiksa.
                            </p>
                        </div>

                        <span class="text-lg font-bold text-[#17365D]">
                            {{ $persentaseChecklist }}%
                        </span>
                    </div>

                    <div class="h-3 overflow-hidden rounded-full bg-slate-100">
                        <div class="h-full rounded-full bg-gradient-to-r from-[#17365D] to-[#2B5C94] transition-all duration-300"
                             style="width: {{ $persentaseChecklist }}%">
                        </div>
                    </div>
                </div>
            </section>

            {{-- Form Tambah Kriteria Checklist --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h3 class="text-base font-bold text-slate-800">
                        Tambah Kriteria Checklist
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Tambahkan kriteria pemeriksaan khusus sesuai kebutuhan inspeksi gerai.
                    </p>
                </div>

                <form action="{{ route('inspections.checklist-items.store', $inspection) }}"
                      method="POST"
                      class="space-y-5 p-6">
                    @csrf

                    <div>
                        <label for="new_section_code"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Bagian Checklist
                        </label>

                        <select name="section_code"
                                id="new_section_code"
                                required
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]">
                            @foreach (config('inspection_checklist', []) as $section)
                                <option value="{{ $section['code'] }}"
                                    @selected(old('section_code') == $section['code'])>
                                    Bagian {{ $section['code'] }} — {{ $section['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="new_code"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Kode Kriteria
                            <span class="font-normal text-slate-400">(opsional)</span>
                        </label>

                        <input type="text"
                               name="code"
                               id="new_code"
                               value="{{ old('code') }}"
                               maxlength="20"
                               placeholder="Contoh: A4 atau C16"
                               class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]">

                        <p class="mt-1 text-xs text-slate-500">
                            Isi kode sendiri jika diperlukan. Boleh dikosongkan.
                        </p>
                    </div>

                    <div>
                        <label for="new_criteria"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Kriteria Pemeriksaan
                        </label>

                        <textarea name="criteria"
                                  id="new_criteria"
                                  rows="3"
                                  required
                                  maxlength="2000"
                                  placeholder="Tuliskan kriteria pemeriksaan yang ingin ditambahkan..."
                                  class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]">{{ old('criteria') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center rounded-lg bg-[#17365D] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#28558A] focus:outline-none focus:ring-2 focus:ring-[#17365D] focus:ring-offset-2">
                            + Tambah Kriteria
                        </button>
                    </div>
                </form>
            </section>

            {{-- Form Simpan Checklist --}}
            <form action="{{ route('inspections.checklist.save', $inspection) }}"
                  method="POST"
                  id="checklist"
                  class="scroll-mt-40">
                @csrf
                @method('PUT')

                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-800">
                            Checklist Inspeksi
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Pilih status setiap kriteria dan tambahkan catatan jika diperlukan.
                        </p>
                    </div>

                    <button type="submit"
                            class="rounded-lg bg-[#17365D] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#28558A]">
                        Simpan Checklist
                    </button>
                </div>

                @foreach (config('inspection_checklist', []) as $section)
                    @php
                        $sectionItems = $checklistItems
                            ->where('section_code', $section['code'])
                            ->sortBy('sort_order');

                        $sectionDone = $sectionItems->filter(
                            fn ($i) => in_array($i->status, ['sesuai', 'tidak_sesuai', 'tidak_berlaku'], true)
                        )->count();
                    @endphp

                    <section class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="flex items-center justify-between gap-3 border-b border-slate-200 bg-slate-50 px-5 py-4">
                            <div class="flex items-center gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-[#17365D] text-sm font-bold text-white">
                                    {{ $section['code'] }}
                                </span>

                                <div>
                                    <h3 class="font-bold text-slate-800">
                                        {{ $section['name'] }}
                                    </h3>
                                    <p class="text-xs text-slate-500">
                                        {{ $sectionItems->count() }} kriteria
                                    </p>
                                </div>
                            </div>

                            <span class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $sectionItems->count() > 0 && $sectionDone === $sectionItems->count() ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-white text-slate-600 ring-slate-200' }}">
                                {{ $sectionDone }}/{{ $sectionItems->count() }} diperiksa
                            </span>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse ($sectionItems as $item)
                                <div class="p-5">
                                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
                                        <div class="lg:col-span-5">
                                            <div class="flex flex-col items-start gap-2">
                                                @if ($item->code)
                                                    <span class="inline-flex h-fit rounded-md bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                                        {{ $item->code }}
                                                    </span>
                                                @endif

                                                <p class="w-full text-sm font-medium leading-6 text-slate-800">
                                                    {{ $item->criteria }}
                                                </p>

                                                @if ($item->is_custom)
                                                    <span class="inline-flex h-fit rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-[#17365D]">
                                                        Kriteria Tambahan
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="lg:col-span-4">
                                            <p class="mb-2 text-xs font-medium text-slate-500">
                                                Status Pemeriksaan
                                            </p>

                                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-3">
                                                @foreach ([
                                                    'sesuai' => 'Sesuai',
                                                    'tidak_sesuai' => 'Tidak Sesuai',
                                                    'tidak_berlaku' => 'Tidak Berlaku',
                                                ] as $value => $label)
                                                    @php
                                                        $checkedStyle = match ($value) {
                                                            'sesuai'        => 'has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:text-emerald-800',
                                                            'tidak_sesuai'  => 'has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 has-[:checked]:text-rose-800',
                                                            default         => 'has-[:checked]:border-slate-500 has-[:checked]:bg-slate-100 has-[:checked]:text-slate-900',
                                                        };
                                                    @endphp
                                                    <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 p-2.5 transition hover:border-slate-300 hover:bg-slate-50 sm:flex-col sm:justify-center sm:gap-1 sm:text-center {{ $checkedStyle }}">
                                                        <input type="radio"
                                                               name="checklist[{{ $item->id }}][status]"
                                                               value="{{ $value }}"
                                                               @checked(old("checklist.{$item->id}.status", $item->status) === $value)
                                                               class="h-4 w-4 cursor-pointer border-slate-300 text-[#17365D] focus:ring-[#17365D]">

                                                        <span class="text-xs font-medium">
                                                            {{ $label }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="lg:col-span-3">
                                            <label for="notes_{{ $item->id }}"
                                                   class="mb-2 block text-xs font-medium text-slate-500">
                                                Catatan
                                            </label>

                                            <textarea name="checklist[{{ $item->id }}][notes]"
                                                      id="notes_{{ $item->id }}"
                                                      rows="3"
                                                      maxlength="2000"
                                                      placeholder="Catatan jika ada..."
                                                      class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]">{{ old("checklist.{$item->id}.notes", $item->notes) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-sm text-slate-500">
                                    Belum ada kriteria untuk bagian ini.
                                </div>
                            @endforelse
                        </div>
                    </section>
                @endforeach

                {{-- Kriteria Tambahan di Bagian yang Tidak Terdaftar pada Konfigurasi --}}
                @php
                    $configuredSectionCodes = collect(config('inspection_checklist', []))
                        ->pluck('code');

                    $unconfiguredItems = $checklistItems
                        ->filter(fn ($item) => !$configuredSectionCodes->contains($item->section_code));
                @endphp

                @if ($unconfiguredItems->isNotEmpty())
                    <section class="mb-6 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-200 bg-slate-50 px-5 py-4">
                            <h3 class="font-bold text-slate-800">
                                Kriteria Tambahan Lainnya
                            </h3>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @foreach ($unconfiguredItems as $item)
                                <div class="p-5">
                                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
                                        <div class="lg:col-span-5">
                                            @if ($item->code)
                                                <span class="inline-flex rounded-md bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                                    {{ $item->code }}
                                                </span>
                                            @endif

                                            <p class="mt-2 text-sm leading-6 text-slate-800">
                                                {{ $item->criteria }}
                                            </p>
                                        </div>

                                        <div class="lg:col-span-4">
                                            <p class="mb-2 text-xs font-medium text-slate-500">
                                                Status Pemeriksaan
                                            </p>

                                            <div class="space-y-2">
                                                @foreach ([
                                                    'sesuai' => 'Sesuai',
                                                    'tidak_sesuai' => 'Tidak Sesuai',
                                                    'tidak_berlaku' => 'Tidak Berlaku',
                                                ] as $value => $label)
                                                    @php
                                                        $checkedStyle = match ($value) {
                                                            'sesuai'        => 'has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50 has-[:checked]:text-emerald-800',
                                                            'tidak_sesuai'  => 'has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50 has-[:checked]:text-rose-800',
                                                            default         => 'has-[:checked]:border-slate-500 has-[:checked]:bg-slate-100 has-[:checked]:text-slate-900',
                                                        };
                                                    @endphp
                                                    <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 p-2.5 transition hover:bg-slate-50 {{ $checkedStyle }}">
                                                        <input type="radio"
                                                               name="checklist[{{ $item->id }}][status]"
                                                               value="{{ $value }}"
                                                               @checked(old("checklist.{$item->id}.status", $item->status) === $value)
                                                               class="h-4 w-4 border-slate-300 text-[#17365D] focus:ring-[#17365D]">

                                                        <span class="text-sm font-medium">
                                                            {{ $label }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>

                                        <div class="lg:col-span-3">
                                            <label for="notes_extra_{{ $item->id }}"
                                                   class="mb-2 block text-xs font-medium text-slate-500">
                                                Catatan
                                            </label>

                                            <textarea name="checklist[{{ $item->id }}][notes]"
                                                      id="notes_extra_{{ $item->id }}"
                                                      rows="3"
                                                      maxlength="2000"
                                                      placeholder="Catatan jika ada..."
                                                      class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]">{{ old("checklist.{$item->id}.notes", $item->notes) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <div class="sticky bottom-4 z-10 flex justify-end">
                    <button type="submit"
                            class="rounded-lg bg-[#17365D] px-6 py-3 text-sm font-semibold text-white shadow-lg transition hover:bg-[#28558A] focus:outline-none focus:ring-2 focus:ring-[#17365D] focus:ring-offset-2">
                        Simpan Checklist
                    </button>
                </div>
            </form>

            {{-- Form Tambah Temuan Inspeksi --}}
            <section id="temuan" class="scroll-mt-40 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h3 class="text-base font-bold text-slate-800">
                        Tambah Temuan Inspeksi
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Catat kondisi atau ketidaksesuaian yang ditemukan saat pemeriksaan.
                    </p>
                </div>

                <form action="{{ route('inspections.findings.store', $inspection) }}"
                      method="POST"
                      class="space-y-5 p-6">
                    @csrf

                    <div>
                        <label for="checklist_item_id"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Kriteria Checklist
                            <span class="font-normal text-slate-400">(opsional)</span>
                        </label>

                        <select name="checklist_item_id"
        id="checklist_item_id"
        class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]">

    <option value="">-- Pilih Kriteria Tidak Sesuai --</option>

    @foreach ($checklistItems->where('status', 'tidak_sesuai') as $item)
        <option value="{{ $item->id }}"
            @selected(old('checklist_item_id') == $item->id)>
            {{ $item->code ? $item->code . ' - ' : '' }}
            {{ $item->criteria }}
        </option>
    @endforeach

</select>
                    </div>

                    <div>
                        <label for="finding_description"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Deskripsi Temuan
                        </label>

                        <textarea name="description"
                                  id="finding_description"
                                  rows="4"
                                  maxlength="5000"
                                  required
                                  placeholder="Jelaskan temuan inspeksi secara rinci..."
                                  class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]">{{ old('description') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="rounded-lg bg-[#17365D] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#28558A]">
                            Simpan Temuan
                        </button>
                    </div>
                </form>
            </section>

            {{-- Daftar Temuan Inspeksi --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h3 class="text-base font-bold text-slate-800">
                        Daftar Temuan Inspeksi
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Temuan yang telah dicatat selama pemeriksaan.
                    </p>
                </div>

                @forelse ($findings as $finding)
                    <div class="border-b border-l-4 border-slate-100 border-l-rose-400 p-5 last:border-b-0">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            @if ($finding->code)
                                <span class="inline-flex w-fit rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-700">
                                    {{ $finding->code }}
                                </span>
                            @endif
                        </div>

                        @if ($finding->criteria)
                            <p class="mt-2 text-sm font-semibold text-slate-800">
                                {{ $finding->criteria }}
                            </p>
                        @endif

                        <p class="mt-2 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $finding->description }}</p>
                    </div>
                @empty
                    <div class="p-6 text-center text-sm text-slate-500">
                        Belum ada temuan inspeksi.
                    </div>
                @endforelse
            </section>

            {{-- Tanda Tangan Digital --}}
            @php
                $signatureParties = [
                    'pihak_pertama' => [
                        'label' => 'Pihak Pertama',
                        'company' => 'PT ANGKASA PURA INDONESIA (PERSERO)',
                        'name' => $inspection->inspector_name,
                        'position' => $inspection->inspector_position,
                        'editable_name' => false,
                    ],
                    'pihak_kedua' => [
                        'label' => 'Pihak Kedua',
                        'company' => $inspection->partner_name,
                        'name' => '',
                        'position' => '',
                        'editable_name' => true,
                    ],
                ];
            @endphp

            <section id="tanda-tangan" class="scroll-mt-40 space-y-5">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">
                        Tanda Tangan Para Pihak
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Lengkapi tanda tangan setelah seluruh checklist dan daftar temuan inspeksi selesai.
                    </p>
                </div>

                @foreach ($signatureParties as $partyKey => $partyData)
                    @php
                        $partySignature = $signatures->firstWhere('party', $partyKey);

                        $companyValue = $partyData['company'] ?? '';

                        $nameValue = $partyData['editable_name']
                            ? ($partySignature?->name ?? '')
                            : ($partyData['name'] ?? '');

                        $positionValue = $partyData['editable_name']
                            ? ($partySignature?->position ?? '')
                            : ($partyData['position'] ?? '');

                        $signatureSaved = $partySignature
                            && !empty($partySignature->signature_path);
                    @endphp

                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                        <div class="border-b border-slate-100 bg-slate-50 px-6 py-5">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <h4 class="text-base font-bold text-slate-800">
                                        {{ $partyData['label'] }}
                                    </h4>

                                    @if ($signatureSaved)
                                        <p class="mt-1 text-xs text-emerald-700">
                                            Tanda tangan tersimpan:
                                            {{ $partySignature->signed_at?->format('d/m/Y H:i') ?? '-' }}
                                        </p>
                                    @else
                                        <p class="mt-1 text-xs text-amber-700">
                                            Belum ada tanda tangan tersimpan.
                                        </p>
                                    @endif
                                </div>

                                <span class="inline-flex w-fit rounded-full px-3 py-1 text-xs font-semibold {{ $signatureSaved ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $signatureSaved ? 'Sudah Ditandatangani' : 'Menunggu Tanda Tangan' }}
                                </span>
                            </div>
                        </div>

                        <form action="{{ route('inspections.signatures.save', $inspection) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              class="space-y-5 p-6"
                              id="signature-form-{{ $partyKey }}">
                            @csrf

                            <input type="hidden"
                                   name="party"
                                   value="{{ $partyKey }}">

                            <input type="hidden"
                                   name="signature_data"
                                   id="signature-data-{{ $partyKey }}">

                            {{-- Nama Perusahaan --}}
                            <div>
                                <label for="signature-company-{{ $partyKey }}"
                                       class="mb-2 block text-sm font-medium text-slate-700">
                                    Nama Perusahaan
                                </label>

                                <input type="text"
                                       id="signature-company-{{ $partyKey }}"
                                       value="{{ $companyValue }}"
                                       readonly
                                       class="w-full rounded-lg border-slate-300 bg-slate-50 text-sm text-slate-700 shadow-sm">

                                <input type="hidden"
                                       name="company_name"
                                       value="{{ $companyValue }}">
                            </div>

                            {{-- Nama dan Jabatan --}}
                            @if ($partyData['editable_name'])
                                <div>
                                    <label for="signature-name-{{ $partyKey }}"
                                           class="mb-2 block text-sm font-medium text-slate-700">
                                        Nama Perwakilan Mitra
                                    </label>

                                    <input type="text"
                                           name="name"
                                           id="signature-name-{{ $partyKey }}"
                                           value="{{ old('party') === $partyKey ? old('name') : $nameValue }}"
                                           maxlength="255"
                                           required
                                           class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]">
                                </div>

                                <div>
                                    <label for="signature-position-{{ $partyKey }}"
                                           class="mb-2 block text-sm font-medium text-slate-700">
                                        Jabatan Perwakilan Mitra
                                    </label>

                                    <input type="text"
                                           name="position"
                                           id="signature-position-{{ $partyKey }}"
                                           value="{{ old('party') === $partyKey ? old('position') : $positionValue }}"
                                           maxlength="255"
                                           required
                                           class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]">
                                </div>
                            @else
                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        Nama Inspektor
                                    </label>

                                    <input type="text"
                                           value="{{ $partyData['name'] ?? '' }}"
                                           readonly
                                           class="w-full rounded-lg border-slate-300 bg-slate-50 text-sm text-slate-700 shadow-sm">

                                    <input type="hidden"
                                           name="name"
                                           value="{{ $partyData['name'] ?? '' }}">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-medium text-slate-700">
                                        Jabatan Inspektor
                                    </label>

                                    <input type="text"
                                           value="{{ $partyData['position'] ?? '' }}"
                                           readonly
                                           class="w-full rounded-lg border-slate-300 bg-slate-50 text-sm text-slate-700 shadow-sm">

                                    <input type="hidden"
                                           name="position"
                                           value="{{ $partyData['position'] ?? '' }}">
                                </div>
                            @endif

                            {{-- Area Tanda Tangan --}}
                            <div>
                                <label class="mb-2 block text-sm font-medium text-slate-700">
                                    Tanda Tangan
                                </label>

                                <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                                    <canvas id="signature-canvas-{{ $partyKey }}"
                                            width="700"
                                            height="220"
                                            class="block w-full touch-none"
                                            style="height:220px; cursor:crosshair;">
                                    </canvas>
                                </div>

                                <p class="mt-2 text-xs text-slate-500">
                                    Gunakan mouse, stylus, atau layar sentuh untuk membuat tanda tangan.
                                </p>

                                <div class="mt-3 flex flex-wrap items-center gap-3">
                                    <button type="button"
                                            data-clear-signature="{{ $partyKey }}"
                                            class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                                        Hapus Gambar
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="h-px flex-1 bg-slate-200"></div>
                                <span class="text-xs font-semibold tracking-wide text-slate-400">
                                    ATAU UPLOAD GAMBAR
                                </span>
                                <div class="h-px flex-1 bg-slate-200"></div>
                            </div>

                            {{-- Upload Tanda Tangan --}}
                            <div>
                                <label for="signature-upload-{{ $partyKey }}"
                                       class="mb-2 block text-sm font-medium text-slate-700">
                                    Upload Tanda Tangan
                                </label>

                                <input type="file"
                                       name="signature_upload"
                                       id="signature-upload-{{ $partyKey }}"
                                       accept=".png,.jpg,.jpeg,image/png,image/jpeg"
                                       class="block w-full rounded-lg border border-slate-200 text-sm text-slate-700 file:mr-4 file:rounded-l-lg file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:font-medium file:text-[#17365D] hover:file:bg-slate-200">

                                <p class="mt-1 text-xs text-slate-500">
                                    Format PNG, JPG, atau JPEG. Ukuran maksimal 2 MB.
                                </p>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit"
                                        class="rounded-lg bg-[#17365D] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#28558A] focus:outline-none focus:ring-2 focus:ring-[#17365D] focus:ring-offset-2">
                                    Simpan {{ $partyData['label'] }}
                                </button>
                            </div>
                        </form>

                        {{-- Pratinjau Tanda Tangan Tersimpan --}}
                        @if ($signatureSaved)
                            <div class="border-t border-slate-100 bg-slate-50 p-6">
                                <p class="mb-3 text-sm font-semibold text-slate-700">
                                    Pratinjau Tanda Tangan Tersimpan
                                </p>

                                <div class="rounded-xl border border-slate-200 bg-white p-4">
                                    <img src="{{ asset('storage/' . $partySignature->signature_path) }}"
                                         alt="Tanda tangan {{ $partyData['label'] }}"
                                         class="max-h-40 max-w-full object-contain">

                                    <div class="mt-4 border-t border-slate-100 pt-4">
                                        <p class="font-semibold text-slate-800">
                                            {{ $nameValue ?: '-' }}
                                        </p>

                                        <p class="text-sm text-slate-600">
                                            {{ $positionValue ?: '-' }}
                                        </p>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $companyValue ?: '-' }}
                                        </p>

                                        <p class="mt-2 text-xs text-slate-400">
                                            Ditandatangani pada
                                            {{ $partySignature->signed_at?->format('d/m/Y H:i') ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </section>

            {{-- Script Tanda Tangan Digital --}}
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    ['pihak_pertama', 'pihak_kedua'].forEach(function (party) {
                        const canvas = document.getElementById('signature-canvas-' + party);
                        const form = document.getElementById('signature-form-' + party);
                        const hiddenInput = document.getElementById('signature-data-' + party);
                        const uploadInput = document.getElementById('signature-upload-' + party);
                        const clearButton = document.querySelector(
                            '[data-clear-signature="' + party + '"]'
                        );

                        if (!canvas || !form || !hiddenInput) {
                            return;
                        }

                        const ctx = canvas.getContext('2d');

                        let drawing = false;
                        let hasDrawing = false;

                        ctx.strokeStyle = '#111827';
                        ctx.lineWidth = 2.5;
                        ctx.lineCap = 'round';
                        ctx.lineJoin = 'round';

                        function getPosition(event) {
                            const rect = canvas.getBoundingClientRect();

                            return {
                                x: (event.clientX - rect.left) * canvas.width / rect.width,
                                y: (event.clientY - rect.top) * canvas.height / rect.height
                            };
                        }

                        canvas.addEventListener('pointerdown', function (event) {
                            if (event.button !== undefined && event.button !== 0) {
                                return;
                            }

                            drawing = true;
                            hasDrawing = true;

                            if (canvas.setPointerCapture) {
                                canvas.setPointerCapture(event.pointerId);
                            }

                            const point = getPosition(event);

                            ctx.beginPath();
                            ctx.moveTo(point.x, point.y);
                            ctx.lineTo(point.x + 0.1, point.y + 0.1);
                            ctx.stroke();

                            event.preventDefault();
                        });

                        canvas.addEventListener('pointermove', function (event) {
                            if (!drawing) {
                                return;
                            }

                            const point = getPosition(event);

                            ctx.lineTo(point.x, point.y);
                            ctx.stroke();

                            event.preventDefault();
                        });

                        function stopDrawing() {
                            drawing = false;
                            ctx.beginPath();
                        }

                        canvas.addEventListener('pointerup', stopDrawing);
                        canvas.addEventListener('pointercancel', stopDrawing);
                        canvas.addEventListener('lostpointercapture', stopDrawing);

                        clearButton?.addEventListener('click', function () {
                            ctx.clearRect(0, 0, canvas.width, canvas.height);

                            hasDrawing = false;
                            hiddenInput.value = '';

                            if (uploadInput) {
                                uploadInput.value = '';
                            }
                        });

                        uploadInput?.addEventListener('change', function () {
                            if (uploadInput.files && uploadInput.files.length > 0) {
                                hiddenInput.value = '';
                            }
                        });

                        form.addEventListener('submit', function (event) {
                            if (uploadInput?.files?.length > 0) {
                                hiddenInput.value = '';
                                return;
                            }

                            if (!hasDrawing) {
                                event.preventDefault();

                                alert(
                                    'Silakan gambar tanda tangan atau unggah gambar terlebih dahulu.'
                                );

                                return;
                            }

                            hiddenInput.value = canvas.toDataURL('image/png');
                        });
                    });
                });
            </script>

            {{-- Form Upload Dokumentasi Foto --}}
            <section id="foto" class="scroll-mt-40 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h3 class="text-base font-bold text-slate-800">
                        Dokumentasi Foto Inspeksi
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Unggah foto kondisi gerai sebagai bukti dokumentasi pemeriksaan.
                    </p>
                </div>

                <form action="{{ route('inspections.photos.store', $inspection) }}"
                      method="POST"
                      enctype="multipart/form-data"
                      class="space-y-5 p-6">
                    @csrf

                    <div>
                        <label for="photo_category"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Kategori Foto
                        </label>

                        <select name="category"
                                id="photo_category"
                                required
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]">
                            <option value="temuan" @selected(old('category') === 'temuan')>
                                Temuan
                            </option>
                            <option value="kondisi_awal" @selected(old('category') === 'kondisi_awal')>
                                Kondisi Awal
                            </option>
                            <option value="kondisi_akhir" @selected(old('category') === 'kondisi_akhir')>
                                Kondisi Akhir
                            </option>
                            <option value="lainnya" @selected(old('category') === 'lainnya')>
                                Lainnya
                            </option>
                        </select>
                    </div>

                    <div>
                        <label for="finding_id"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Temuan Terkait
                            <span class="font-normal text-slate-400">(opsional)</span>
                        </label>

                        <select name="finding_id"
                                id="finding_id"
                                class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]">
                            <option value="">-- Tidak terkait dengan temuan tertentu --</option>

                            @foreach ($findings as $finding)
                                <option value="{{ $finding->id }}"
                                    @selected(old('finding_id') == $finding->id)>
                                    {{ $finding->code ? $finding->code . ' - ' : '' }}
                                    {{ \Illuminate\Support\Str::limit($finding->description, 100) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="photo"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Pilih Foto
                        </label>

                        <input type="file"
                               name="photo"
                               id="photo"
                               accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                               required
                               class="block w-full rounded-lg border border-slate-200 text-sm text-slate-700 file:mr-4 file:rounded-l-lg file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:font-medium file:text-[#17365D] hover:file:bg-slate-200">

                        <p class="mt-1 text-xs text-slate-500">
                            Format JPG, JPEG, PNG, atau WEBP. Ukuran maksimal 5 MB per foto.
                        </p>
                    </div>

                    <div>
                        <label for="photo_caption"
                               class="mb-2 block text-sm font-medium text-slate-700">
                            Keterangan Foto
                            <span class="font-normal text-slate-400">(opsional)</span>
                        </label>

                        <textarea name="caption"
                                  id="photo_caption"
                                  rows="2"
                                  maxlength="1000"
                                  placeholder="Contoh: Kondisi lantai di depan gerai..."
                                  class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]">{{ old('caption') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="rounded-lg bg-[#17365D] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#28558A]">
                            Upload Foto
                        </button>
                    </div>
                </form>
            </section>

            {{-- Galeri Dokumentasi Foto --}}
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-5">
                    <h3 class="text-base font-bold text-slate-800">
                        Galeri Dokumentasi Foto
                    </h3>
                    <p class="mt-1 text-sm text-slate-500">
                        Dokumentasi foto yang telah diunggah untuk inspeksi ini.
                    </p>
                </div>

                <div class="grid gap-5 p-6 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse ($photos as $photo)
                        <article class="overflow-hidden rounded-xl border border-slate-200 bg-white transition hover:shadow-md">
                            <a href="{{ asset('storage/' . $photo->file_path) }}"
                               target="_blank"
                               rel="noopener noreferrer">
                                <img src="{{ asset('storage/' . $photo->file_path) }}"
                                     alt="{{ $photo->caption ?? 'Foto inspeksi' }}"
                                     loading="lazy"
                                     class="h-52 w-full bg-slate-50 object-contain">
                            </a>

                            <div class="p-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                                        {{ ucfirst(str_replace('_', ' ', $photo->category)) }}
                                    </span>
                                </div>

                                <p class="mt-3 whitespace-pre-line text-sm leading-6 text-slate-600">{{ $photo->caption ?: 'Tidak ada keterangan foto.' }}</p>

                                @if ($photo->finding_id)
                                    <p class="mt-2 text-xs text-slate-500">
                                        Terhubung dengan temuan #{{ $photo->finding_id }}
                                    </p>
                                @endif
                            </div>
                        </article>
                    @empty
                        <div class="col-span-full rounded-xl border border-dashed border-slate-300 px-4 py-10 text-center">
                            <p class="text-sm font-medium text-slate-600">
                                Belum ada dokumentasi foto untuk inspeksi ini.
                            </p>
                            <p class="mt-1 text-xs text-slate-400">
                                Foto yang diunggah akan muncul di bagian ini.
                            </p>
                        </div>
                    @endforelse
                </div>
            </section>

            {{-- Riwayat Aktivitas --}}
            @if (isset($inspection->activityLogs) && $inspection->activityLogs->isNotEmpty())
                <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                    <div class="border-b border-slate-100 px-6 py-5">
                        <h3 class="text-base font-bold text-slate-800">
                            Riwayat Aktivitas
                        </h3>
                        <p class="mt-1 text-sm text-slate-500">
                            Catatan aktivitas yang dilakukan pada inspeksi ini.
                        </p>
                    </div>

                    <div class="divide-y divide-slate-100">
                        @foreach ($inspection->activityLogs->sortByDesc('created_at') as $log)
                            <div class="p-5">
                                <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                    <p class="text-sm font-medium leading-6 text-slate-800">
                                        {{ $log->description ?? $log->activity }}
                                    </p>

                                    <p class="shrink-0 text-xs text-slate-500">
                                        {{ $log->created_at?->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                @if ($log->user)
                                    <p class="mt-2 text-xs text-slate-500">
                                        Oleh: {{ $log->user->name }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif

    </div>
</x-app-layout>
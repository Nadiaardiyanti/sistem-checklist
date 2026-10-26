<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Detail Inspeksi Gerai
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Nomor inspeksi: {{ $inspection->inspection_number }}
                </p>
            </div>

            
<div class="flex flex-wrap gap-2">
    <a href="{{ route('inspections.index') }}"
       class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
        Kembali
    </a>

    <a href="{{ route('inspections.edit', $inspection) }}"
       class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700">
        Edit Inspeksi
    </a>

    <a href="{{ route('inspections.pdf', $inspection) }}"
       class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700">
        <svg xmlns="http://www.w3.org/2000/svg"
             width="18"
             height="18"
             viewBox="0 0 24 24"
             fill="none"
             stroke="currentColor"
             stroke-width="2">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
            <polyline points="7 10 12 15 17 10"/>
            <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Download PDF
    </a>
</div>

        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            {{-- Notifikasi --}}
            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="mb-2 font-semibold">Terdapat kesalahan:</p>
                    <ul class="list-inside list-disc space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Informasi Inspeksi --}}
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Informasi Inspeksi
                    </h3>
                </div>

                <div class="grid grid-cols-1 gap-5 p-6 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <p class="text-sm text-gray-500">Nomor Inspeksi</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $inspection->inspection_number ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Nama Mitra</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $inspection->partner_name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Brand</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $inspection->brand ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Kode Ruangan</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $inspection->room_code ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Kategori</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $inspection->category ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Lokasi</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $inspection->location ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Jenis Inspeksi</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $inspection->inspection_type ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Tanggal Inspeksi</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $inspection->inspection_date?->format('d/m/Y') ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Waktu Inspeksi</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $inspection->inspection_time ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Nama Inspektor</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $inspection->inspector_name ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Jabatan Inspektor</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $inspection->inspector_position ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Terminal</p>
                        <p class="mt-1 font-medium text-gray-900">
                            {{ $inspection->terminal ?? '-' }}
                        </p>
                    </div>

                    <div>
                        <p class="text-sm text-gray-500">Status</p>
                        <span class="mt-1 inline-flex rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800">
                            {{ ucfirst(str_replace('_', ' ', $inspection->status ?? 'draft')) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Checklist --}}
            @php
                $totalChecklist = $checklistItems->count();
                $totalSesuai = $checklistItems->where('status', 'sesuai')->count();
                $totalTidakSesuai = $checklistItems->where('status', 'tidak_sesuai')->count();
                $totalTidakBerlaku = $checklistItems->where('status', 'tidak_berlaku')->count();
                $totalBelumDiperiksa = $checklistItems->filter(fn ($item) => !$item->status)->count();
            @endphp

            <div class="grid grid-cols-2 gap-4 lg:grid-cols-5">
                <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <p class="text-sm text-gray-500">Total Kriteria</p>
                    <p class="mt-2 text-2xl font-bold text-gray-900">{{ $totalChecklist }}</p>
                </div>

                <div class="rounded-xl border border-green-200 bg-green-50 p-4">
                    <p class="text-sm text-green-700">Sesuai</p>
                    <p class="mt-2 text-2xl font-bold text-green-700">{{ $totalSesuai }}</p>
                </div>

                <div class="rounded-xl border border-red-200 bg-red-50 p-4">
                    <p class="text-sm text-red-700">Tidak Sesuai</p>
                    <p class="mt-2 text-2xl font-bold text-red-700">{{ $totalTidakSesuai }}</p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                    <p class="text-sm text-gray-600">Tidak Berlaku</p>
                    <p class="mt-2 text-2xl font-bold text-gray-700">{{ $totalTidakBerlaku }}</p>
                </div>

                <div class="rounded-xl border border-orange-200 bg-orange-50 p-4">
                    <p class="text-sm text-orange-700">Belum Diperiksa</p>
                    <p class="mt-2 text-2xl font-bold text-orange-700">{{ $totalBelumDiperiksa }}</p>
                </div>
            </div>

            {{-- FORM TAMBAH KRITERIA: DI LUAR FORM SIMPAN CHECKLIST --}}
            <div class="overflow-hidden rounded-xl border border-purple-200 bg-white shadow-sm">
                <div class="border-b border-purple-100 bg-purple-50 px-6 py-4">
                    <h3 class="text-lg font-semibold text-purple-900">
                        + Tambah Kriteria Checklist
                    </h3>
                    <p class="mt-1 text-sm text-purple-700">
                        Tambahkan kriteria pemeriksaan khusus sesuai kebutuhan inspeksi gerai.
                    </p>
                </div>

                <form action="{{ route('inspections.checklist-items.store', $inspection) }}"
                      method="POST"
                      class="space-y-5 p-6">
                    @csrf

                    <div>
                        <label for="new_section_code"
                               class="mb-2 block text-sm font-medium text-gray-700">
                            Bagian Checklist
                        </label>

                        <select name="section_code"
                                id="new_section_code"
                                required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            @foreach (config('inspection_checklist', []) as $section)
                                <option value="{{ $section['code'] }}"
                                    {{ old('section_code') == $section['code'] ? 'selected' : '' }}>
                                    Bagian {{ $section['code'] }} — {{ $section['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="new_code"
                               class="mb-2 block text-sm font-medium text-gray-700">
                            Kode Kriteria
                            <span class="font-normal text-gray-400">(opsional)</span>
                        </label>

                        <input type="text"
                               name="code"
                               id="new_code"
                               value="{{ old('code') }}"
                               maxlength="20"
                               placeholder="Contoh: A4 atau C16"
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">

                        <p class="mt-1 text-xs text-gray-500">
                            Isi kode sendiri jika diperlukan. Boleh dikosongkan.
                        </p>
                    </div>

                    <div>
                        <label for="new_criteria"
                               class="mb-2 block text-sm font-medium text-gray-700">
                            Kriteria Pemeriksaan
                        </label>

                        <textarea name="criteria"
                                  id="new_criteria"
                                  rows="3"
                                  required
                                  maxlength="2000"
                                  placeholder="Tuliskan kriteria pemeriksaan yang ingin ditambahkan..."
                                  class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">{{ old('criteria') }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                                class="inline-flex items-center rounded-lg bg-purple-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            + Tambah Kriteria
                        </button>
                    </div>
                </form>
            </div>

            {{-- FORM SIMPAN CHECKLIST --}}
            <form action="{{ route('inspections.checklist.save', $inspection) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">
                            Checklist Inspeksi
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            Pilih status setiap kriteria dan tambahkan catatan jika diperlukan.
                        </p>
                    </div>

                    <button type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
                        Simpan Checklist
                    </button>
                </div>

                @foreach (config('inspection_checklist', []) as $section)
                    @php
                        $sectionItems = $checklistItems
                            ->where('section_code', $section['code'])
                            ->sortBy('sort_order');
                    @endphp

                    <div class="mb-6 overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="border-b border-gray-200 bg-gray-50 px-5 py-4">
                            <h3 class="font-semibold text-gray-900">
                                {{ $section['code'] }}. {{ $section['name'] }}
                            </h3>
                        </div>

                        <div class="divide-y divide-gray-100">
                            @forelse ($sectionItems as $item)
                                <div class="p-5">
                                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-12">
                                        <div class="lg:col-span-6">
                                            <div class="flex flex-wrap items-start gap-2">
                                                @if ($item->code)
                                                    <span class="inline-flex h-fit rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                                        {{ $item->code }}
                                                    </span>
                                                @endif

                                                <p class="text-sm font-medium leading-6 text-gray-900">
                                                    {{ $item->criteria }}
                                                </p>

                                                @if ($item->is_custom)
                                                    <span class="inline-flex h-fit rounded-full bg-purple-100 px-2 py-1 text-xs font-medium text-purple-700">
                                                        Kriteria Tambahan
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="lg:col-span-3">
                                            <label for="status_{{ $item->id }}"
                                                   class="mb-1 block text-xs font-medium text-gray-500">
                                                Status Pemeriksaan
                                            </label>

                                            <select name="checklist[{{ $item->id }}][status]"
                                                    id="status_{{ $item->id }}"
                                                    class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="">-- Pilih Status --</option>
                                                <option value="sesuai" @selected($item->status === 'sesuai')>
                                                    Sesuai
                                                </option>
                                                <option value="tidak_sesuai" @selected($item->status === 'tidak_sesuai')>
                                                    Tidak Sesuai
                                                </option>
                                                <option value="tidak_berlaku" @selected($item->status === 'tidak_berlaku')>
                                                    Tidak Berlaku
                                                </option>
                                            </select>
                                        </div>

                                        <div class="lg:col-span-3">
                                            <label for="notes_{{ $item->id }}"
                                                   class="mb-1 block text-xs font-medium text-gray-500">
                                                Catatan
                                            </label>

                                            <textarea name="checklist[{{ $item->id }}][notes]"
                                                      id="notes_{{ $item->id }}"
                                                      rows="2"
                                                      maxlength="2000"
                                                      placeholder="Catatan jika ada..."
                                                      class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ $item->notes }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-sm text-gray-500">
                                    Belum ada kriteria untuk bagian ini.
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach

                {{-- Tombol Simpan Bagian Bawah --}}
                <div class="sticky bottom-4 z-10 flex justify-end">
                    <button type="submit"
                            class="rounded-lg bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-lg hover:bg-indigo-700">
                        Simpan Checklist
                    </button>
                </div>
            </form>

            
{{-- Form Tambah Temuan Inspeksi --}}
<div class="overflow-hidden rounded-xl border border-orange-200 bg-white shadow-sm">
    <div class="border-b border-orange-100 bg-orange-50 px-6 py-4">
        <h3 class="text-lg font-semibold text-orange-900">
            + Tambah Temuan Inspeksi
        </h3>
        <p class="mt-1 text-sm text-orange-700">
            Catat kondisi atau ketidaksesuaian yang ditemukan saat pemeriksaan.
        </p>
    </div>

    <form action="{{ route('inspections.findings.store', $inspection) }}"
          method="POST"
          class="space-y-5 p-6">
        @csrf

        <div>
            <label for="checklist_item_id"
                   class="mb-2 block text-sm font-medium text-gray-700">
                Kriteria Checklist
                <span class="font-normal text-gray-400">(opsional)</span>
            </label>

            <select name="checklist_item_id"
                    id="checklist_item_id"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                <option value="">-- Pilih Kriteria --</option>

                @foreach ($checklistItems as $item)
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
                   class="mb-2 block text-sm font-medium text-gray-700">
                Deskripsi Temuan
            </label>

            <textarea name="description"
                      id="finding_description"
                      rows="4"
                      maxlength="5000"
                      required
                      placeholder="Jelaskan temuan inspeksi secara rinci..."
                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-orange-500 focus:ring-orange-500">{{ old('description') }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="rounded-lg bg-orange-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-orange-700">
                Simpan Temuan
            </button>
        </div>
    </form>
</div>

{{-- Daftar Temuan Inspeksi --}}
<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-900">
            Daftar Temuan Inspeksi
        </h3>
    </div>

    @forelse ($findings as $finding)
        <div class="border-b border-gray-100 p-5 last:border-b-0">
            @if ($finding->code)
                <span class="inline-flex rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                    {{ $finding->code }}
                </span>
            @endif

            @if ($finding->criteria)
                <p class="mt-2 text-sm font-medium text-gray-900">
                    {{ $finding->criteria }}
                </p>
            @endif

            <p class="mt-2 whitespace-pre-line text-sm text-gray-700">
                {{ $finding->description }}
            </p>
        </div>
    @empty
        <div class="p-6 text-center text-sm text-gray-500">
            Belum ada temuan inspeksi.
        </div>
    @endforelse
</div>


            

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

<div class="space-y-6">
    <div>
        <h3 class="text-xl font-semibold text-gray-900">
            Tanda Tangan Para Pihak
        </h3>
        <p class="mt-1 text-sm text-gray-500">
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
        @endphp

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 bg-gray-50 px-6 py-4">
                <h4 class="text-lg font-semibold text-gray-900">
                    {{ $partyData['label'] }}
                </h4>

                @if ($partySignature?->signature_path)
                    <p class="mt-1 text-xs text-green-700">
                        Tanda tangan tersimpan:
                        {{ $partySignature->signed_at?->format('d/m/Y H:i') ?? '-' }}
                    </p>
                @else
                    <p class="mt-1 text-xs text-gray-500">
                        Belum ada tanda tangan tersimpan.
                    </p>
                @endif
            </div>

            <form action="{{ route('inspections.signatures.save', $inspection) }}"
                  method="POST"
                  enctype="multipart/form-data"
                  class="space-y-5 p-6"
                  id="signature-form-{{ $partyKey }}">
                @csrf

                <input type="hidden" name="party" value="{{ $partyKey }}">
                <input type="hidden"
                       name="signature_data"
                       id="signature-data-{{ $partyKey }}">

                {{-- Nama perusahaan otomatis --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Nama Perusahaan
                    </label>

                    <input type="text"
                           value="{{ $companyValue }}"
                           readonly
                           class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-700 shadow-sm">

                    <input type="hidden"
                           name="company_name"
                           value="{{ $companyValue }}">
                </div>

                {{-- Nama dan jabatan --}}
                @if ($partyData['editable_name'])
                    <div>
                        <label for="signature-name-{{ $partyKey }}"
                               class="mb-2 block text-sm font-medium text-gray-700">
                            Nama Perwakilan Mitra
                        </label>

                        <input type="text"
                               name="name"
                               id="signature-name-{{ $partyKey }}"
                               value="{{ old('party') === $partyKey ? old('name') : $nameValue }}"
                               maxlength="255"
                               required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label for="signature-position-{{ $partyKey }}"
                               class="mb-2 block text-sm font-medium text-gray-700">
                            Jabatan Perwakilan Mitra
                        </label>

                        <input type="text"
                               name="position"
                               id="signature-position-{{ $partyKey }}"
                               value="{{ old('party') === $partyKey ? old('position') : $positionValue }}"
                               maxlength="255"
                               required
                               class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                @else
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Nama Inspektor
                        </label>

                        <input type="text"
                               value="{{ $partyData['name'] ?? '' }}"
                               readonly
                               class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-700 shadow-sm">

                        <input type="hidden" name="name"
                               value="{{ $partyData['name'] ?? '' }}">
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">
                            Jabatan Inspektor
                        </label>

                        <input type="text"
                               value="{{ $partyData['position'] ?? '' }}"
                               readonly
                               class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-700 shadow-sm">

                        <input type="hidden" name="position"
                               value="{{ $partyData['position'] ?? '' }}">
                    </div>
                @endif

                {{-- Area tanda tangan --}}
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">
                        Tanda Tangan
                    </label>

                    <div class="overflow-hidden rounded-lg border border-gray-300 bg-white">
                        <canvas id="signature-canvas-{{ $partyKey }}"
                                width="700"
                                height="220"
                                class="block w-full touch-none"
                                style="height:220px; cursor:crosshair;"></canvas>
                    </div>

                    <div class="mt-2 flex flex-wrap items-center gap-3">
                        <button type="button"
                                data-clear-signature="{{ $partyKey }}"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Hapus Gambar
                        </button>

                        <span class="text-xs text-gray-500">
                            Gambar menggunakan mouse, stylus, atau layar sentuh.
                        </span>
                    </div>
                </div>

                <div class="text-center text-sm font-medium text-gray-500">
                    ATAU UPLOAD GAMBAR
                </div>

                <div>
                    <label for="signature-upload-{{ $partyKey }}"
                           class="mb-2 block text-sm font-medium text-gray-700">
                        Upload Tanda Tangan
                    </label>

                    <input type="file"
                           name="signature_upload"
                           id="signature-upload-{{ $partyKey }}"
                           accept=".png,.jpg,.jpeg,image/png,image/jpeg"
                           class="block w-full rounded-lg border border-gray-300 text-sm text-gray-700">

                    <p class="mt-1 text-xs text-gray-500">
                        PNG, JPG, atau JPEG. Maksimal 2 MB.
                    </p>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
                        Simpan {{ $partyData['label'] }}
                    </button>
                </div>
            </form>

            {{-- Tampilkan tanda tangan yang tersimpan --}}
            @if ($partySignature?->signature_path)
                <div class="border-t border-gray-200 bg-gray-50 p-6">
                    <p class="mb-3 text-sm font-medium text-gray-700">
                        Pratinjau Tanda Tangan Tersimpan
                    </p>

                    <img src="{{ asset('storage/' . $partySignature->signature_path) }}"
                         alt="Tanda tangan {{ $partyData['label'] }}"
                         class="max-h-32 max-w-full rounded border border-gray-200 bg-white object-contain p-2">

                    <p class="mt-3 text-sm font-semibold text-gray-800">
                        {{ $nameValue ?: '-' }}
                    </p>

                    <p class="text-sm text-gray-600">
                        {{ $positionValue ?: '-' }}
                    </p>

                    <p class="text-sm text-gray-500">
                        {{ $companyValue ?: '-' }}
                    </p>
                </div>
            @endif
        </div>
    @endforeach
</div>

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

        if (!canvas || !form || !hiddenInput) return;

        const ctx = canvas.getContext('2d');
        let drawing = false;
        let hasDrawing = false;

        ctx.strokeStyle = '#111827';
        ctx.lineWidth = 2.5;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        function position(event) {
            const rect = canvas.getBoundingClientRect();

            return {
                x: (event.clientX - rect.left) * canvas.width / rect.width,
                y: (event.clientY - rect.top) * canvas.height / rect.height
            };
        }

        canvas.addEventListener('pointerdown', function (event) {
            drawing = true;
            hasDrawing = true;
            canvas.setPointerCapture(event.pointerId);

            const p = position(event);
            ctx.beginPath();
            ctx.moveTo(p.x, p.y);
            ctx.lineTo(p.x + 0.1, p.y + 0.1);
            ctx.stroke();

            event.preventDefault();
        });

        canvas.addEventListener('pointermove', function (event) {
            if (!drawing) return;

            const p = position(event);
            ctx.lineTo(p.x, p.y);
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

            if (uploadInput) uploadInput.value = '';
        });

        uploadInput?.addEventListener('change', function () {
            if (uploadInput.files.length > 0) {
                hiddenInput.value = '';
            }
        });

        form.addEventListener('submit', function (event) {
            if (uploadInput?.files.length > 0) {
                hiddenInput.value = '';
                return;
            }

            if (!hasDrawing) {
                event.preventDefault();
                alert('Silakan gambar tanda tangan atau unggah gambar terlebih dahulu.');
                return;
            }

            hiddenInput.value = canvas.toDataURL('image/png');
        });
    });
});
</script>


           
{{-- Form Upload Dokumentasi Foto --}}
<div class="overflow-hidden rounded-xl border border-blue-200 bg-white shadow-sm">
    <div class="border-b border-blue-100 bg-blue-50 px-6 py-4">
        <h3 class="text-lg font-semibold text-blue-900">
            + Dokumentasi Foto Inspeksi
        </h3>
        <p class="mt-1 text-sm text-blue-700">
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
                   class="mb-2 block text-sm font-medium text-gray-700">
                Kategori Foto
            </label>

            <select name="category"
                    id="photo_category"
                    required
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                   class="mb-2 block text-sm font-medium text-gray-700">
                Temuan Terkait
                <span class="font-normal text-gray-400">(opsional)</span>
            </label>

            <select name="finding_id"
                    id="finding_id"
                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
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
                   class="mb-2 block text-sm font-medium text-gray-700">
                Pilih Foto
            </label>

            <input type="file"
                   name="photo"
                   id="photo"
                   accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                   required
                   class="block w-full rounded-lg border border-gray-300 text-sm text-gray-700 file:mr-4 file:rounded-l-lg file:border-0 file:bg-blue-50 file:px-4 file:py-3 file:font-medium file:text-blue-700 hover:file:bg-blue-100">

            <p class="mt-1 text-xs text-gray-500">
                Format JPG, JPEG, PNG, atau WEBP. Ukuran maksimal 5 MB per foto.
            </p>
        </div>

        <div>
            <label for="photo_caption"
                   class="mb-2 block text-sm font-medium text-gray-700">
                Keterangan Foto
                <span class="font-normal text-gray-400">(opsional)</span>
            </label>

            <textarea name="caption"
                      id="photo_caption"
                      rows="2"
                      maxlength="1000"
                      placeholder="Contoh: Kondisi lantai di depan gerai..."
                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('caption') }}</textarea>
        </div>

        <div class="flex justify-end">
            <button type="submit"
                    class="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700">
                Upload Foto
            </button>
        </div>
    </form>
</div>

{{-- Galeri Dokumentasi Foto --}}
<div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
    <div class="border-b border-gray-200 px-6 py-4">
        <h3 class="text-lg font-semibold text-gray-900">
            Galeri Dokumentasi Foto
        </h3>
    </div>

    <div class="p-6">
        @forelse ($photos as $photo)
            <div class="mb-5 overflow-hidden rounded-lg border border-gray-200 last:mb-0">
                <a href="{{ asset('storage/' . $photo->file_path) }}"
                   target="_blank"
                   rel="noopener noreferrer">
                    <img src="{{ asset('storage/' . $photo->file_path) }}"
                         alt="{{ $photo->caption ?? 'Foto inspeksi' }}"
                         loading="lazy"
                         class="h-56 w-full bg-gray-50 object-contain sm:h-72">
                </a>

                <div class="p-4">
                    <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                        {{ ucfirst(str_replace('_', ' ', $photo->category)) }}
                    </span>

                    <p class="mt-2 text-sm text-gray-700">
                        {{ $photo->caption ?? 'Tidak ada keterangan foto.' }}
                    </p>

                    @if ($photo->finding_id)
                        <p class="mt-1 text-xs text-gray-500">
                            Terhubung dengan temuan
                            #{{ $photo->finding_id }}
                        </p>
                    @endif
                </div>
            </div>
        @empty
            <p class="py-6 text-center text-sm text-gray-500">
                Belum ada dokumentasi foto untuk inspeksi ini.
            </p>
        @endforelse
    </div>
</div>


            {{-- Riwayat Aktivitas --}}
            @if (isset($inspection->activityLogs) && $inspection->activityLogs->isNotEmpty())
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 px-6 py-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Riwayat Aktivitas
                        </h3>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @foreach ($inspection->activityLogs->sortByDesc('created_at') as $log)
                            <div class="p-4">
                                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                    <p class="font-medium text-gray-900">
                                        {{ $log->description ?? $log->activity }}
                                    </p>

                                    <p class="text-xs text-gray-500">
                                        {{ $log->created_at?->format('d/m/Y H:i') }}
                                    </p>
                                </div>

                                @if ($log->user)
                                    <p class="mt-1 text-xs text-gray-500">
                                        Oleh: {{ $log->user->name }}
                                    </p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>  
<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                Edit Inspeksi Gerai
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Perbarui identitas gerai dan informasi pelaksanaan inspeksi.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">

            {{-- Pesan sukses --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Pesan error validasi --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="font-semibold">
                        Periksa kembali data berikut:
                    </p>

                    <ul class="mt-2 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('inspections.update', $inspection) }}"
                method="POST"
                class="space-y-6"
            >
                @csrf
                @method('PUT')

                {{-- INFORMASI GERAI --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 bg-[#17365D] px-6 py-4">
                        <h3 class="font-semibold text-white">
                            1. Informasi Gerai
                        </h3>
                        <p class="mt-1 text-sm text-blue-100">
                            Perbarui identitas mitra dan lokasi gerai.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                        {{-- Nama Mitra --}}
                        <div>
                            <label for="partner_name" class="mb-2 block text-sm font-medium text-gray-700">
                                Nama Mitra / PT <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="partner_name"
                                name="partner_name"
                                value="{{ old('partner_name', $inspection->partner_name) }}"
                                placeholder="Contoh: PT Nama Mitra"
                                required
                                maxlength="255"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]"
                            >

                            @error('partner_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Brand --}}
                        <div>
                            <label for="brand" class="mb-2 block text-sm font-medium text-gray-700">
                                Brand <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="brand"
                                name="brand"
                                value="{{ old('brand', $inspection->brand) }}"
                                placeholder="Contoh: Nama Brand"
                                required
                                maxlength="255"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]"
                            >

                            @error('brand')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kode Ruangan --}}
                        <div>
                            <label for="room_code" class="mb-2 block text-sm font-medium text-gray-700">
                                Kode Ruangan <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="room_code"
                                name="room_code"
                                value="{{ old('room_code', $inspection->room_code) }}"
                                placeholder="Masukkan kode ruangan"
                                required
                                maxlength="100"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]"
                            >

                            @error('room_code')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Kategori --}}
                        <div>
                            <label for="category" class="mb-2 block text-sm font-medium text-gray-700">
                                Kategori
                            </label>

                            <input
                                type="text"
                                id="category"
                                name="category"
                                value="{{ old('category', $inspection->category) }}"
                                placeholder="Masukkan kategori gerai"
                                maxlength="100"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]"
                            >

                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Lokasi --}}
                        <div class="md:col-span-2">
                            <label for="location" class="mb-2 block text-sm font-medium text-gray-700">
                                Lokasi Gerai <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="location"
                                name="location"
                                value="{{ old('location', $inspection->location) }}"
                                placeholder="Contoh: Area keberangkatan, lantai 2"
                                required
                                maxlength="255"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]"
                            >

                            @error('location')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- INFORMASI INSPEKSI --}}
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="border-b border-gray-200 bg-[#17365D] px-6 py-4">
                        <h3 class="font-semibold text-white">
                            2. Informasi Inspeksi
                        </h3>
                        <p class="mt-1 text-sm text-blue-100">
                            Perbarui jadwal dan informasi petugas inspeksi.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-5 p-6 md:grid-cols-2">

                        {{-- Jenis Inspeksi --}}
                        <div>
                            <label for="inspection_type" class="mb-2 block text-sm font-medium text-gray-700">
                                Jenis Inspeksi <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="inspection_type"
                                name="inspection_type"
                                value="{{ old('inspection_type', $inspection->inspection_type) }}"
                                placeholder="Masukkan jenis inspeksi"
                                required
                                maxlength="100"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]"
                            >

                            @error('inspection_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Terminal --}}
                        <div>
                            <label for="terminal" class="mb-2 block text-sm font-medium text-gray-700">
                                Terminal <span class="text-red-500">*</span>
                            </label>

                            <select
                                id="terminal"
                                name="terminal"
                                required
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]"
                            >
                                <option value="">Pilih terminal</option>

                                <option
                                    value="International"
                                    @selected(old('terminal', $inspection->terminal) === 'International')
                                >
                                    International
                                </option>

                                <option
                                    value="Domestic"
                                    @selected(old('terminal', $inspection->terminal) === 'Domestic')
                                >
                                    Domestic
                                </option>
                            </select>

                            @error('terminal')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tanggal Inspeksi --}}
                        <div>
                            <label for="inspection_date" class="mb-2 block text-sm font-medium text-gray-700">
                                Tanggal Inspeksi
                            </label>

                            <input
                                type="date"
                                id="inspection_date"
                                name="inspection_date"
                                value="{{ old('inspection_date', $inspection->inspection_date ? \Illuminate\Support\Carbon::parse($inspection->inspection_date)->format('Y-m-d') : '') }}"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]"
                            >

                            @error('inspection_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Waktu Inspeksi --}}
                        <div>
                            <label for="inspection_time" class="mb-2 block text-sm font-medium text-gray-700">
                                Waktu Inspeksi
                            </label>

                            <input
                                type="time"
                                id="inspection_time"
                                name="inspection_time"
                                value="{{ old('inspection_time', $inspection->inspection_time ? substr($inspection->inspection_time, 0, 5) : '') }}"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]"
                            >

                            @error('inspection_time')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Nama Inspektor --}}
                        <div>
                            <label for="inspector_name" class="mb-2 block text-sm font-medium text-gray-700">
                                Nama Inspektor <span class="text-red-500">*</span>
                            </label>

                            <input
                                type="text"
                                id="inspector_name"
                                name="inspector_name"
                                value="{{ old('inspector_name', $inspection->inspector_name) }}"
                                placeholder="Masukkan nama inspektor"
                                required
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm placeholder:text-gray-400 focus:border-[#17365D] focus:ring-[#17365D]"
                            >

                            @error('inspector_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Jabatan Inspektor --}}
                        <div>
                            <label for="inspector_position" class="mb-2 block text-sm font-medium text-gray-700">
                                Jabatan Petugas
                            </label>

                            <input
                                type="text"
                                id="inspector_position"
                                name="inspector_position"
                                value="{{ old('inspector_position', $inspection->inspector_position) }}"
                                placeholder="Masukkan jabatan petugas"
                                maxlength="255"
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]"
                            >

                            @error('inspector_position')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Catatan --}}
                        <div class="md:col-span-2">
                            <label for="notes" class="mb-2 block text-sm font-medium text-gray-700">
                                Catatan Umum
                            </label>

                            <textarea
                                id="notes"
                                name="notes"
                                rows="3"
                                placeholder="Tambahkan catatan jika diperlukan..."
                                class="w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-[#17365D] focus:ring-[#17365D]"
                            >{{ old('notes', $inspection->notes) }}</textarea>

                            @error('notes')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
                    <a
                        href="{{ route('inspections.show', $inspection) }}"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50"
                    >
                        Kembali
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-lg bg-[#17365D] px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-[#102844] focus:outline-none focus:ring-2 focus:ring-[#17365D] focus:ring-offset-2"
                    >
                        Simpan Perubahan
                    </button>
                </div>

                <p class="text-right text-xs text-gray-500">
                    <span class="text-red-500">*</span>
                    Wajib diisi
                </p>

            </form>
        </div>
    </div>
</x-app-layout>
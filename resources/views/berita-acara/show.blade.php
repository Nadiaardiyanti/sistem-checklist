<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Berita Acara
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Preview data Berita Acara
                </p>
            </div>

            <a href="{{ route('berita-acara.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent
                      rounded-md font-semibold text-xs text-white uppercase tracking-widest
                      hover:bg-gray-700">
                + Buat BA Baru
            </a>
        </div>
    </x-slot>


    <div class="py-8">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- SUCCESS --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-100 border border-green-300
                            px-5 py-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif


            {{-- ERROR --}}
            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200
                            px-5 py-4 text-red-700">
                    {{ session('error') }}
                </div>
            @endif


            {{-- CARD UTAMA --}}
            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">


                {{-- HEADER BA --}}
                <div class="px-8 py-6 border-b border-gray-200">

                    <div class="text-center">

                        <h1 class="text-2xl font-bold text-gray-900 uppercase">
                            Berita Acara
                        </h1>

                        <p class="mt-2 text-sm text-gray-500">
                            Divisi Komersial
                        </p>

                    </div>

                </div>


                {{-- DATA BA --}}
                <div class="px-8 py-6">

                    <div class="flex items-center justify-between mb-5">

                        <h3 class="text-lg font-semibold text-gray-800">
                            Informasi Berita Acara
                        </h3>

                        {{-- STATUS --}}
                        @if ($beritaAcara->status === 'selesai')

                            <span class="inline-flex items-center px-3 py-1
                                         rounded-full text-sm font-semibold
                                         bg-green-100 text-green-700">
                                ✓ Selesai
                            </span>

                        @else

                            <span class="inline-flex items-center px-3 py-1
                                         rounded-full text-sm font-semibold
                                         bg-yellow-100 text-yellow-700">
                                Draft
                            </span>

                        @endif

                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        {{-- NOMOR --}}
                        <div>
                            <p class="text-sm text-gray-500">
                                Nomor Berita Acara
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $beritaAcara->nomor_ba }}
                            </p>
                        </div>


                        {{-- TANGGAL --}}
                        <div>
                            <p class="text-sm text-gray-500">
                                Tanggal Surat
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $beritaAcara->tanggal_surat->format('d/m/Y') }}
                            </p>
                        </div>


                        {{-- MITRA --}}
                        <div>
                            <p class="text-sm text-gray-500">
                                Nama Mitra / Client
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $beritaAcara->nama_mitra }}
                            </p>
                        </div>


                        {{-- LUAS --}}
                        <div>
                            <p class="text-sm text-gray-500">
                                Luas Tenant
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ number_format($beritaAcara->luas_tenant, 2, ',', '.') }} m²
                            </p>
                        </div>


                        {{-- POSISI --}}
                        <div class="md:col-span-2">
                            <p class="text-sm text-gray-500">
                                Posisi Tenant di Bandara
                            </p>

                            <p class="mt-1 font-semibold text-gray-900">
                                {{ $beritaAcara->posisi_tenant }}
                            </p>
                        </div>

                    </div>

                </div>


                {{-- CHECKLIST --}}
                <div class="px-8 py-6 border-t border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-800 mb-5">
                        Checklist Dokumen
                    </h3>


                    @if ($beritaAcara->checklistItems->count())

                        <div class="space-y-3">

                            @foreach ($beritaAcara->checklistItems as $item)

                                <div class="flex items-center justify-between
                                            border border-gray-200 rounded-lg px-4 py-3">

                                    <div class="flex items-center gap-3">

                                        @if ($item->is_checked)

                                            <div class="w-6 h-6 rounded-full bg-green-100
                                                        flex items-center justify-center">

                                                <span class="text-green-600 font-bold">
                                                    ✓
                                                </span>

                                            </div>

                                            <span class="font-medium text-gray-800">
                                                {{ $item->nama_checklist }}
                                            </span>

                                        @else

                                            <div class="w-6 h-6 rounded-full bg-gray-100
                                                        flex items-center justify-center">

                                                <span class="text-gray-400 font-bold">
                                                    —
                                                </span>

                                            </div>

                                            <span class="font-medium text-gray-500">
                                                {{ $item->nama_checklist }}
                                            </span>

                                        @endif

                                    </div>


                                    <span class="text-sm
                                        {{ $item->is_checked
                                            ? 'text-green-600'
                                            : 'text-gray-400' }}">

                                        {{ $item->is_checked
                                            ? 'Lengkap'
                                            : 'Belum Lengkap' }}

                                    </span>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <p class="text-gray-500">
                            Belum ada checklist dokumen.
                        </p>

                    @endif

                </div>


                {{-- TANDA TANGAN --}}
                <div class="px-8 py-6 border-t border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-800 mb-6">
                        Tanda Tangan
                    </h3>


                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        @foreach (['Pihak Pertama', 'Pihak Kedua'] as $pihak)

                            @php
                                $signature = $beritaAcara->signatures
                                    ->where('pihak', $pihak)
                                    ->first();
                            @endphp


                            <div class="border border-gray-200 rounded-xl p-5">

                                <div class="text-center">

                                    <p class="font-semibold text-gray-800">
                                        {{ $pihak }}
                                    </p>


                                    @if ($signature)

                                        <div class="mt-4">

                                            @if ($signature->signature_file)

                                                <img
                                                    src="{{ asset('storage/' . $signature->signature_file) }}"
                                                    alt="Tanda tangan {{ $pihak }}"
                                                    class="mx-auto h-32 max-w-full object-contain"
                                                >

                                            @else

                                                <div class="h-32 flex items-center justify-center
                                                            text-gray-400">

                                                    Tanda tangan tidak tersedia

                                                </div>

                                            @endif

                                        </div>


                                        <div class="mt-4 text-left">

                                            @if ($signature->nama)

                                                <p class="text-sm text-gray-700">
                                                    <span class="font-medium">
                                                        Nama:
                                                    </span>

                                                    {{ $signature->nama }}
                                                </p>

                                            @endif


                                            @if ($signature->jabatan)

                                                <p class="text-sm text-gray-700 mt-1">
                                                    <span class="font-medium">
                                                        Jabatan:
                                                    </span>

                                                    {{ $signature->jabatan }}
                                                </p>

                                            @endif

                                        </div>

                                    @else

                                        <div class="h-32 mt-4 flex items-center justify-center
                                                    border border-dashed border-gray-300
                                                    rounded-lg text-gray-400">

                                            Belum ada tanda tangan

                                        </div>

                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>


                {{-- FOOTER INFO --}}
                <div class="px-8 py-5 border-t border-gray-200 bg-gray-50">

                    <p class="text-sm text-gray-500">

                        Dibuat oleh:

                        <span class="font-medium text-gray-700">
                            {{ $beritaAcara->creator->name ?? '-' }}
                        </span>

                    </p>


                    <p class="text-xs text-gray-400 mt-1">

                        Dibuat pada:

                        {{ $beritaAcara->created_at->format('d/m/Y H:i') }}

                    </p>

                </div>


                {{-- BUTTON --}}
                <div class="px-8 py-5 border-t border-gray-200
                            flex flex-col sm:flex-row justify-end gap-3">


                    {{-- KEMBALI --}}
                    <a href="{{ route('berita-acara.index') }}"
                       class="inline-flex justify-center items-center px-5 py-2.5
                              bg-gray-200 rounded-lg font-semibold text-sm text-gray-700
                              hover:bg-gray-300">

                        Kembali

                    </a>


                    {{-- EDIT --}}
                    <a href="{{ route('berita-acara.edit', $beritaAcara) }}"
                       class="inline-flex justify-center items-center px-5 py-2.5
                              bg-yellow-500 rounded-lg font-semibold text-sm text-white
                              hover:bg-yellow-600">

                        Edit

                    </a>


                    {{-- RIWAYAT PERUBAHAN --}}
                    <a href="{{ route('berita-acara.versions', $beritaAcara) }}"
                       class="inline-flex justify-center items-center px-5 py-2.5
                              bg-indigo-600 rounded-lg font-semibold text-sm text-white
                              hover:bg-indigo-700">

                        Riwayat Perubahan

                    </a>


                    {{-- DOWNLOAD PDF --}}
                    <a href="{{ route('berita-acara.pdf', $beritaAcara) }}"
                       target="_blank"
                       class="inline-flex justify-center items-center px-5 py-2.5
                              bg-red-600 rounded-lg font-semibold text-sm text-white
                              hover:bg-red-700">

                        Download PDF

                    </a>


                    {{-- TANDAI SELESAI --}}
                    @if ($beritaAcara->status !== 'selesai')

                        <form
                            method="POST"
                            action="{{ route('berita-acara.complete', $beritaAcara) }}"
                            onsubmit="return confirm(
                                'Apakah Anda yakin Berita Acara ini sudah lengkap dan ingin ditandai sebagai selesai?'
                            )"
                        >

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                class="inline-flex justify-center items-center px-5 py-2.5
                                       bg-green-600 rounded-lg font-semibold text-sm text-white
                                       hover:bg-green-700"
                            >

                                ✓ Tandai Selesai

                            </button>

                        </form>

                    @else

                        <span
                            class="inline-flex justify-center items-center px-5 py-2.5
                                   bg-green-100 rounded-lg font-semibold text-sm text-green-700"
                        >

                            ✓ Sudah Selesai

                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
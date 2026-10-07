<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Riwayat Perubahan BA
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    {{ $beritaAcara->nomor_ba }}
                </p>
            </div>

            <a
                href="{{ route('berita-acara.show', $beritaAcara) }}"
                class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
            >
                Kembali
            </a>

        </div>
    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- INFORMASI BA --}}

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">

                <div class="p-6">

                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        Informasi Berita Acara
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <p class="text-sm text-gray-500">
                                Nomor BA
                            </p>

                            <p class="font-medium text-gray-800">
                                {{ $beritaAcara->nomor_ba }}
                            </p>
                        </div>


                        <div>
                            <p class="text-sm text-gray-500">
                                Nama Mitra
                            </p>

                            <p class="font-medium text-gray-800">
                                {{ $beritaAcara->nama_mitra }}
                            </p>
                        </div>


                        <div>
                            <p class="text-sm text-gray-500">
                                Posisi Tenant
                            </p>

                            <p class="font-medium text-gray-800">
                                {{ $beritaAcara->posisi_tenant }}
                            </p>
                        </div>


                        <div>
                            <p class="text-sm text-gray-500">
                                Luas Tenant
                            </p>

                            <p class="font-medium text-gray-800">
                                {{ $beritaAcara->luas_tenant }} m²
                            </p>
                        </div>

                    </div>

                </div>

            </div>


            {{-- RIWAYAT --}}

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6">

                    <div class="flex items-center justify-between mb-6">

                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Riwayat Perubahan
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Menampilkan versi data sebelum dilakukan perubahan.
                            </p>
                        </div>

                    </div>


                    @if ($versions->count() > 0)

                        <div class="space-y-4">

                            @foreach ($versions as $index => $version)

                                <div class="border border-gray-200 rounded-lg p-5">

                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">

                                        <div>

                                            <p class="font-semibold text-gray-800">
                                                Versi #{{ $versions->count() - $index }}
                                            </p>

                                            <p class="text-sm text-gray-500">
                                                Diubah oleh:
                                                <span class="font-medium text-gray-700">
                                                    {{ $version->user->name ?? '-' }}
                                                </span>
                                            </p>

                                        </div>


                                        <div class="text-sm text-gray-500">
                                            {{ $version->created_at->format('d/m/Y H:i') }}
                                        </div>

                                    </div>


                                    <div class="border-t border-gray-200 mt-4 pt-4">

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                            <div>

                                                <p class="text-xs text-gray-500">
                                                    Nomor BA
                                                </p>

                                                <p class="font-medium text-gray-800">
                                                    {{ $version->nomor_ba }}
                                                </p>

                                            </div>


                                            <div>

                                                <p class="text-xs text-gray-500">
                                                    Tanggal Surat
                                                </p>

                                                <p class="font-medium text-gray-800">
                                                    {{ $version->tanggal_surat?->format('d/m/Y') ?? '-' }}
                                                </p>

                                            </div>


                                            <div>

                                                <p class="text-xs text-gray-500">
                                                    Nama Mitra
                                                </p>

                                                <p class="font-medium text-gray-800">
                                                    {{ $version->nama_mitra }}
                                                </p>

                                            </div>


                                            <div>

                                                <p class="text-xs text-gray-500">
                                                    Luas Tenant
                                                </p>

                                                <p class="font-medium text-gray-800">
                                                    {{ $version->luas_tenant }} m²
                                                </p>

                                            </div>


                                            <div class="md:col-span-2">

                                                <p class="text-xs text-gray-500">
                                                    Posisi Tenant
                                                </p>

                                                <p class="font-medium text-gray-800">
                                                    {{ $version->posisi_tenant }}
                                                </p>

                                            </div>

                                        </div>


                                        {{-- CHECKLIST --}}

                                        @php
                                            $checklists = json_decode(
                                                $version->checklist_data,
                                                true
                                            ) ?? [];
                                        @endphp

                                        <div class="mt-5">

                                            <p class="text-sm font-semibold text-gray-700 mb-2">
                                                Checklist
                                            </p>

                                            @if (count($checklists) > 0)

                                                <div class="space-y-2">

                                                    @foreach ($checklists as $checklist)

                                                        <div class="flex items-center gap-2">

                                                            @if ($checklist['is_checked'] ?? false)

                                                                <span class="text-green-600">
                                                                    ✓
                                                                </span>

                                                            @else

                                                                <span class="text-gray-400">
                                                                    ○
                                                                </span>

                                                            @endif

                                                            <span class="text-sm text-gray-700">
                                                                {{ $checklist['nama_checklist'] ?? '-' }}
                                                            </span>

                                                        </div>

                                                    @endforeach

                                                </div>

                                            @else

                                                <p class="text-sm text-gray-500">
                                                    Tidak ada data checklist.
                                                </p>

                                            @endif

                                        </div>


                                        {{-- SIGNATURE --}}

                                        @php
                                            $signatures = json_decode(
                                                $version->signature_data,
                                                true
                                            ) ?? [];
                                        @endphp

                                        <div class="mt-5">

                                            <p class="text-sm font-semibold text-gray-700 mb-2">
                                                Tanda Tangan
                                            </p>

                                            @if (count($signatures) > 0)

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                                    @foreach ($signatures as $signature)

                                                        <div class="border rounded-md p-3">

                                                            <p class="text-sm font-semibold text-gray-800">
                                                                {{ $signature['pihak'] ?? '-' }}
                                                            </p>

                                                            <p class="text-sm text-gray-600 mt-1">
                                                                {{ $signature['nama'] ?? '-' }}
                                                            </p>

                                                            <p class="text-xs text-gray-500">
                                                                {{ $signature['jabatan'] ?? '-' }}
                                                            </p>

                                                        </div>

                                                    @endforeach

                                                </div>

                                            @else

                                                <p class="text-sm text-gray-500">
                                                    Tidak ada data tanda tangan.
                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center py-10">

                            <p class="text-gray-500">
                                Belum ada riwayat perubahan.
                            </p>

                            <p class="text-sm text-gray-400 mt-1">
                                Riwayat akan muncul setelah BA diedit.
                            </p>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
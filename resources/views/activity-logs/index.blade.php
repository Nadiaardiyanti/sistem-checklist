<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between gap-4">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Log Aktivitas
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Riwayat aktivitas pengguna pada Sistem Pemeriksaan dan Supervisi (SPS)
                </p>
            </div>

            <a
                href="{{ route('inspections.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition"
            >
                Kembali ke Inspeksi
            </a>

        </div>
    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- INFORMASI --}}

            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <span class="text-blue-600 text-lg">
                            ℹ
                        </span>
                    </div>

                    <div>
                        <h3 class="font-semibold text-gray-800">
                            Riwayat Aktivitas Sistem
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Semua aktivitas pengguna pada fitur inspeksi SPS yang tercatat di sistem ditampilkan di halaman ini.
                        </p>
                    </div>

                </div>

            </div>


            {{-- TABEL AKTIVITAS --}}

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                <div class="px-6 py-5 border-b border-gray-200">

                    <div class="flex items-center justify-between gap-3 flex-wrap">

                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Daftar Aktivitas
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Riwayat aktivitas terbaru ditampilkan terlebih dahulu.
                            </p>
                        </div>

                        <div class="text-sm text-gray-500">
                            Total aktivitas:
                            <span class="font-semibold text-gray-800">
                                {{ $activityLogs->total() }}
                            </span>
                        </div>

                    </div>

                </div>


                @if ($activityLogs->count())

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Waktu
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Pengguna
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Inspeksi
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Aktivitas
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Detail
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach ($activityLogs as $log)

                                    <tr class="hover:bg-gray-50 transition">

                                        {{-- WAKTU --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm font-medium text-gray-800">
                                                {{ $log->created_at?->format('d/m/Y') ?? '-' }}
                                            </div>

                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $log->created_at?->format('H:i') ?? '-' }} WIB
                                            </div>

                                        </td>


                                        {{-- PENGGUNA --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm font-medium text-gray-800">
                                                {{ $log->user?->name ?? 'Pengguna tidak ditemukan' }}
                                            </div>

                                        </td>


                                        {{-- INSPEKSI --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if ($log->inspection)

                                                <a
                                                    href="{{ route('inspections.show', $log->inspection) }}"
                                                    class="text-sm font-medium text-blue-600 hover:text-blue-800"
                                                >
                                                    Inspeksi #{{ $log->inspection->id }}
                                                </a>

                                            @else

                                                <span class="text-sm text-gray-400">
                                                    -
                                                </span>

                                            @endif

                                        </td>


                                        {{-- AKTIVITAS --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @php

                                                $activityLabels = [
                                                    'inspection_created' => 'Membuat Inspeksi',
                                                    'inspection_updated' => 'Memperbarui Inspeksi',
                                                    'checklist_updated' => 'Memperbarui Checklist',
                                                    'finding_created' => 'Menambahkan Temuan',
                                                    'photo_uploaded' => 'Mengunggah Foto',
                                                    'signature_saved' => 'Menyimpan Tanda Tangan',
                                                ];

                                                $activityLabel = $activityLabels[$log->activity]
                                                    ?? \Illuminate\Support\Str::headline(
                                                        str_replace('_', ' ', $log->activity ?? '')
                                                    );

                                                $activityClass = match ($log->activity) {

                                                    'inspection_created' =>
                                                        'bg-green-100 text-green-700',

                                                    'inspection_updated' =>
                                                        'bg-blue-100 text-blue-700',

                                                    'checklist_updated' =>
                                                        'bg-yellow-100 text-yellow-700',

                                                    'finding_created' =>
                                                        'bg-orange-100 text-orange-700',

                                                    'photo_uploaded' =>
                                                        'bg-purple-100 text-purple-700',

                                                    'signature_saved' =>
                                                        'bg-indigo-100 text-indigo-700',

                                                    default =>
                                                        'bg-gray-100 text-gray-700',

                                                };

                                            @endphp

                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $activityClass }}"
                                            >
                                                {{ $activityLabel }}
                                            </span>

                                        </td>


                                        {{-- DETAIL AKTIVITAS --}}

                                        <td class="px-6 py-4">

                                            <p class="text-sm text-gray-600 min-w-48 max-w-md">
                                                {{ $log->description ?: '-' }}
                                            </p>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- PAGINATION --}}

                    <div class="px-6 py-4 border-t border-gray-200">

                        {{ $activityLogs->links() }}

                    </div>


                @else

                    {{-- EMPTY STATE --}}

                    <div class="p-12 text-center">

                        <div class="text-gray-400 text-5xl mb-4">
                            📋
                        </div>

                        <h3 class="text-lg font-semibold text-gray-800">
                            Belum Ada Aktivitas
                        </h3>

                        <p class="text-sm text-gray-500 mt-2">
                            Aktivitas pengguna akan muncul di sini setelah ada aktivitas yang tercatat pada sistem SPS.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>
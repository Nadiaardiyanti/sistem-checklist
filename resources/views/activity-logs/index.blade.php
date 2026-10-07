<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Log Aktivitas
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Riwayat aktivitas pengguna pada sistem Berita Acara
                </p>
            </div>

            <a
                href="{{ route('berita-acara.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
            >
                Kembali ke BA
            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- INFORMASI --}}

            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 text-lg">
                            ℹ
                        </span>
                    </div>

                    <div>

                        <h3 class="font-semibold text-gray-800">
                            Riwayat Aktivitas Sistem
                        </h3>

                        <p class="text-sm text-gray-500">
                            Semua perubahan dan aktivitas pada Berita Acara tercatat di halaman ini.
                        </p>

                    </div>

                </div>

            </div>


            {{-- TABLE --}}

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">

                <div class="px-6 py-5 border-b border-gray-200">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Daftar Aktivitas
                    </h3>

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
                                        User
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Nomor BA
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

                                    <tr class="hover:bg-gray-50">

                                        {{-- WAKTU --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm font-medium text-gray-800">
                                                {{ $log->created_at->format('d/m/Y') }}
                                            </div>

                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $log->created_at->format('H:i') }} WIB
                                            </div>

                                        </td>


                                        {{-- USER --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm font-medium text-gray-800">
                                                {{ $log->user->name ?? '-' }}
                                            </div>

                                        </td>


                                        {{-- NOMOR BA --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if ($log->beritaAcara)

                                                <a
                                                    href="{{ route('berita-acara.show', $log->beritaAcara) }}"
                                                    class="text-sm font-medium text-blue-600 hover:text-blue-800"
                                                >
                                                    {{ $log->beritaAcara->nomor_ba }}
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

                                                $activityClass = match ($log->activity) {

                                                    'Membuat BA' =>
                                                        'bg-green-100 text-green-700',

                                                    'Mengubah Nomor BA',
                                                    'Mengubah Nama Mitra',
                                                    'Mengubah Luas Tenant',
                                                    'Mengubah Posisi Tenant' =>
                                                        'bg-blue-100 text-blue-700',

                                                    'Mengubah Checklist' =>
                                                        'bg-yellow-100 text-yellow-700',

                                                    'Mengubah Tanda Tangan' =>
                                                        'bg-purple-100 text-purple-700',

                                                    default =>
                                                        'bg-gray-100 text-gray-700',
                                                };

                                            @endphp

                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium {{ $activityClass }}"
                                            >
                                                {{ $log->activity }}
                                            </span>

                                        </td>


                                        {{-- DETAIL --}}

                                        <td class="px-6 py-4">

                                            <p class="text-sm text-gray-600 max-w-md">
                                                {{ $log->description ?? '-' }}
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
                            Aktivitas pengguna akan muncul di sini setelah ada aktivitas pada sistem.
                        </p>

                    </div>

                @endif

            </div>

        </div>

    </div>

</x-app-layout>
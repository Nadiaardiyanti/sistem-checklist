<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>

                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    History Berita Acara
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Daftar Berita Acara yang telah dibuat
                </p>

            </div>


            <a
                href="{{ route('berita-acara.create') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
            >
                + Buat BA Baru
            </a>

        </div>

    </x-slot>


    <div class="py-8">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


            {{-- SUCCESS MESSAGE --}}

            @if (session('success'))

                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">

                    {{ session('success') }}

                </div>

            @endif


            {{-- SEARCH & FILTER --}}

            <div class="bg-white shadow-sm sm:rounded-lg p-6 mb-6">

                <div class="mb-5">

                    <h3 class="text-lg font-semibold text-gray-800">
                        Cari & Filter Berita Acara
                    </h3>

                    <p class="text-sm text-gray-500 mt-1">
                        Gunakan filter untuk menemukan data Berita Acara.
                    </p>

                </div>


                <form
                    method="GET"
                    action="{{ route('berita-acara.index') }}"
                >

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">


                        {{-- SEARCH --}}

                        <div class="lg:col-span-2">

                            <label
                                for="search"
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Cari
                            </label>

                            <input
                                type="text"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Nomor BA atau nama mitra..."
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>


                        {{-- TANGGAL --}}

                        <div>

                            <label
                                for="tanggal"
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Tanggal
                            </label>

                            <input
                                type="date"
                                id="tanggal"
                                name="tanggal"
                                value="{{ request('tanggal') }}"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                        </div>


                        {{-- STATUS --}}

                        <div>

                            <label
                                for="status"
                                class="block text-sm font-medium text-gray-700 mb-1"
                            >
                                Status
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >

                                <option value="">
                                    Semua Status
                                </option>

                                <option
                                    value="draft"
                                    {{ request('status') === 'draft' ? 'selected' : '' }}
                                >
                                    Draft
                                </option>

                                <option
                                    value="selesai"
                                    {{ request('status') === 'selesai' ? 'selected' : '' }}
                                >
                                    Selesai
                                </option>

                            </select>

                        </div>

                    </div>


                    {{-- BUTTON --}}

                    <div class="flex flex-wrap gap-3 mt-5">

                        <button
                            type="submit"
                            class="inline-flex items-center px-5 py-2.5 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                        >
                            🔍 Cari
                        </button>


                        <a
                            href="{{ route('berita-acara.index') }}"
                            class="inline-flex items-center px-5 py-2.5 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300"
                        >
                            Reset
                        </a>

                    </div>

                </form>

            </div>


            {{-- TABLE --}}

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">


                {{-- TABLE HEADER --}}

                <div class="px-6 py-5 border-b border-gray-200">

                    <div class="flex items-center justify-between">

                        <div>

                            <h3 class="text-lg font-semibold text-gray-800">
                                Daftar Berita Acara
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">

                                Menampilkan
                                <span class="font-medium">
                                    {{ $beritaAcaras->total() }}
                                </span>
                                data

                            </p>

                        </div>

                    </div>

                </div>


                @if ($beritaAcaras->count())


                    {{-- TABLE --}}

                    <div class="overflow-x-auto">

                        <table class="min-w-full divide-y divide-gray-200">

                            <thead class="bg-gray-50">

                                <tr>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        No
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Nomor BA
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Nama Mitra
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>

                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white divide-y divide-gray-200">

                                @foreach ($beritaAcaras as $index => $beritaAcara)

                                    <tr class="hover:bg-gray-50">


                                        {{-- NO --}}

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">

                                            {{ $beritaAcaras->firstItem() + $index }}

                                        </td>


                                        {{-- NOMOR BA --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm font-semibold text-gray-800">

                                                {{ $beritaAcara->nomor_ba }}

                                            </div>

                                        </td>


                                        {{-- NAMA MITRA --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm text-gray-800">

                                                {{ $beritaAcara->nama_mitra }}

                                            </div>

                                        </td>


                                        {{-- TANGGAL --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="text-sm text-gray-600">

                                                {{ $beritaAcara->tanggal_surat->format('d/m/Y') }}

                                            </div>

                                        </td>


                                        {{-- STATUS --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            @if ($beritaAcara->status === 'selesai')

                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">

                                                    Selesai

                                                </span>

                                            @else

                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">

                                                    Draft

                                                </span>

                                            @endif

                                        </td>


                                        {{-- AKSI --}}

                                        <td class="px-6 py-4 whitespace-nowrap">

                                            <div class="flex items-center gap-2">


                                                {{-- EDIT --}}

                                                <a
                                                    href="{{ route('berita-acara.edit', $beritaAcara) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-yellow-500 rounded-md text-xs font-semibold text-white hover:bg-yellow-600"
                                                >
                                                    Edit
                                                </a>


                                                {{-- DETAIL --}}

                                                <a
                                                    href="{{ route('berita-acara.show', $beritaAcara) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-blue-600 rounded-md text-xs font-semibold text-white hover:bg-blue-700"
                                                >
                                                    Detail
                                                </a>


                                                {{-- PDF --}}

                                                <a
                                                    href="{{ route('berita-acara.pdf', $beritaAcara) }}"
                                                    class="inline-flex items-center px-3 py-1.5 bg-gray-700 rounded-md text-xs font-semibold text-white hover:bg-gray-800"
                                                >
                                                    PDF
                                                </a>

                                            </div>

                                        </td>

                                    </tr>

                                @endforeach

                            </tbody>

                        </table>

                    </div>


                    {{-- PAGINATION --}}

                    <div class="px-6 py-4 border-t border-gray-200">

                        {{ $beritaAcaras->links() }}

                    </div>


                @else


                    {{-- EMPTY STATE --}}

                    <div class="p-12 text-center">

                        <div class="text-gray-400 text-5xl mb-4">
                            📋
                        </div>

                        <h3 class="text-lg font-semibold text-gray-800">
                            Data Tidak Ditemukan
                        </h3>

                        <p class="text-sm text-gray-500 mt-2">
                            Tidak ada Berita Acara yang sesuai dengan pencarian atau filter.
                        </p>

                        <a
                            href="{{ route('berita-acara.index') }}"
                            class="inline-flex items-center mt-5 px-4 py-2 bg-gray-800 rounded-md text-xs font-semibold text-white uppercase tracking-widest hover:bg-gray-700"
                        >
                            Reset Filter
                        </a>

                    </div>


                @endif

            </div>

        </div>

    </div>

</x-app-layout>

<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Monitoring Operasional Gerai
                </h2>
                <p class="mt-1 text-sm text-gray-500">
                    Daftar dan riwayat inspeksi gerai.
                </p>
            </div>

            <a href="{{ route('inspections.create') }}"
               class="inline-flex items-center justify-center rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                + Buat Inspeksi Baru
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold">Ada data yang perlu diperiksa.</p>
                    <ul class="mt-2 list-inside list-disc">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Total Inspeksi</p>
                    <p class="mt-2 text-3xl font-bold text-gray-800">
                        {{ $inspections->total() }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Sedang Dikerjakan</p>
                    <p class="mt-2 text-3xl font-bold text-amber-600">
                        {{ \App\Models\Inspection::where('status', 'draft')->count() }}
                    </p>
                </div>

                <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <p class="text-sm text-gray-500">Selesai</p>
                    <p class="mt-2 text-3xl font-bold text-green-600">
                        {{ \App\Models\Inspection::where('status', 'completed')->count() }}
                    </p>
                </div>
            </div>

            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="flex flex-col gap-2 border-b border-gray-200 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="font-semibold text-gray-800">Riwayat Inspeksi</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Data inspeksi gerai yang tersimpan.
                        </p>
                    </div>
                    <span class="text-sm text-gray-500">
                        {{ $inspections->total() }} data
                    </span>
                </div>

                @if ($inspections->count())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Nomor Inspeksi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Mitra / Brand
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Lokasi
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Tanggal
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-100 bg-white">
                                @foreach ($inspections as $inspection)
                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-800">
                                            {{ $inspection->inspection_number ?? '-' }}
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            <div class="font-medium">
                                                {{ $inspection->partner_name }}
                                            </div>
                                            <div class="mt-1 text-gray-500">
                                                {{ $inspection->brand }}
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-sm text-gray-700">
                                            <div>{{ $inspection->location }}</div>
                                            <div class="mt-1 text-gray-500">
                                                {{ $inspection->room_code }}
                                            </div>
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                                            {{ $inspection->inspection_date?->format('d/m/Y') ?? '-' }}
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4">
                                            @if ($inspection->status === 'completed')
                                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-medium text-green-800">
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-medium text-amber-800">
                                                    Draft
                                                </span>
                                            @endif
                                        </td>

                                        <td class="whitespace-nowrap px-6 py-4 text-right text-sm">
                                            <a href="{{ route('inspections.show', $inspection) }}"
                                               class="font-medium text-blue-700 hover:text-blue-900">
                                                Detail
                                            </a>

                                            <span class="mx-1 text-gray-300">|</span>

                                            <a href="{{ route('inspections.edit', $inspection) }}"
                                               class="font-medium text-gray-600 hover:text-gray-900">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="border-t border-gray-200 px-6 py-4">
                        {{ $inspections->links() }}
                    </div>
                @else
                    <div class="px-6 py-16 text-center">
                        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-blue-50 text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-7 w-7"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor">
                                <path stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="1.7"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 8l2 2 4-4"/>
                            </svg>
                        </div>

                        <h3 class="mt-4 text-base font-semibold text-gray-800">
                            Belum ada inspeksi
                        </h3>
                        <p class="mt-2 text-sm text-gray-500">
                            Mulai dengan membuat inspeksi gerai baru.
                        </p>

                        <a href="{{ route('inspections.create') }}"
                           class="mt-5 inline-flex items-center rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-blue-800">
                            + Buat Inspeksi Baru
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

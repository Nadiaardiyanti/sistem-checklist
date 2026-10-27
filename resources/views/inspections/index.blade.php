<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="mt-1 text-xl font-bold text-slate-800 sm:text-2xl">
                    Monitoring Operasional Gerai
                </h2>
                <p class="mt-1 text-sm text-slate-500">
                    Kelola dan pantau seluruh kegiatan inspeksi gerai.
                </p>
            </div>

            <a href="{{ route('inspections.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-[#17365D] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#28558A]">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 5v14m-7-7h14"/>
                </svg>
                Buat Inspeksi Baru
            </a>
        </div>
    </x-slot>

    <div class="space-y-8">

        {{-- Notifikasi berhasil --}}
        @if (session('success'))
            <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-800">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5 shrink-0" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12l2 2 4-4m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Notifikasi kesalahan --}}
        @if ($errors->any())
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 text-sm text-rose-800">
                <p class="font-semibold">Ada data yang perlu diperiksa.</p>
                <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Ringkasan statistik --}}
        <section>
    <div class="mb-4">
        <h3 class="text-base font-semibold text-slate-800">
            Ringkasan Inspeksi
        </h3>
    </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3">

                {{-- Total inspeksi --}}
                <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Total Inspeksi
                            </p>
                            <p class="mt-3 text-3xl font-bold text-slate-800">
                                {{ $inspections->total() }}
                            </p>
                            <p class="mt-2 text-xs text-slate-400">
                                Seluruh data inspeksi
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Inspeksi draft --}}
                <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Sedang Dikerjakan
                            </p>
                            <p class="mt-3 text-3xl font-bold text-amber-600">
                                {{ \App\Models\Inspection::where('status', 'draft')->count() }}
                            </p>
                            <p class="mt-2 text-xs text-slate-400">
                                Inspeksi berstatus draft
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Inspeksi selesai --}}
                <div class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:shadow-md">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-500">
                                Inspeksi Selesai
                            </p>
                            <p class="mt-3 text-3xl font-bold text-emerald-600">
                                {{ \App\Models\Inspection::where('status', 'completed')->count() }}
                            </p>
                            <p class="mt-2 text-xs text-slate-400">
                                Inspeksi yang telah diselesaikan
                            </p>
                        </div>

                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                 stroke="currentColor" stroke-width="1.7">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M9 12l2 2 4-4m6 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

            </div>
        </section>

        {{-- Tabel riwayat inspeksi --}}
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="flex flex-col gap-3 border-b border-slate-100 px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h3 class="text-base font-semibold text-slate-800">
            Riwayat Inspeksi
        </h3>
        <p class="mt-1 text-sm text-slate-500">
            Data inspeksi gerai yang tercatat dalam sistem.
        </p>
    </div>

                <span class="inline-flex w-fit items-center gap-2 rounded-xl bg-blue-50 px-3 py-2 text-sm font-semibold text-[#17365D]">
                    {{ $inspections->total() }} data
                </span>
            </div>

            @if ($inspections->count())
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-100">

                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Nomor Inspeksi
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Mitra / Brand
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Lokasi Gerai
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Tanggal
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Status
                                </th>
                                <th class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider text-slate-500">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach ($inspections as $inspection)
                                <tr class="transition hover:bg-blue-50/40">

                                    <td class="whitespace-nowrap px-5 py-4">
                                        <a href="{{ route('inspections.show', $inspection) }}"
                                           class="text-sm font-bold text-[#17365D] hover:text-blue-700">
                                            {{ $inspection->inspection_number ?? 'INS-' . $inspection->id }}
                                        </a>
                                        <p class="mt-1 text-xs text-slate-400">
                                            ID #{{ $inspection->id }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="text-sm font-semibold text-slate-800">
                                            {{ $inspection->partner_name }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ $inspection->brand ?: 'Brand belum diisi' }}
                                        </p>
                                    </td>

                                    <td class="px-6 py-5">
                                        <p class="text-sm text-slate-700">
                                            {{ $inspection->location }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-400">
                                            Kode ruang: {{ $inspection->room_code ?: '-' }}
                                        </p>
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 text-sm text-slate-600">
                                        {{ $inspection->inspection_date?->format('d M Y') ?? '-' }}
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5">
                                        @if ($inspection->status === 'completed')
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Selesai
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                                Draft
                                            </span>
                                        @endif
                                    </td>

                                    <td class="whitespace-nowrap px-6 py-5 text-right">
                                        <div class="inline-flex items-center gap-2">
    <a href="{{ route('inspections.show', $inspection) }}"
       class="inline-flex items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-medium text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-[#17365D]">
        Detail
    </a>

<a href="{{ route('inspections.edit', $inspection) }}"
   class="inline-flex items-center justify-center rounded-lg bg-[#17365D] px-3 py-2 text-xs font-medium text-white transition hover:bg-[#28558A]">
    Edit
</a>

</div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

                <div class="border-t border-slate-100 bg-white px-6 py-4">
                    {{ $inspections->links() }}
                </div>

            @else
                <div class="px-6 py-16 text-center">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-50 text-[#17365D]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-8 w-8" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="1.7">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 12l2 2 4-4"/>
                        </svg>
                    </div>

                    <h3 class="mt-5 text-lg font-bold text-slate-800">
                        Belum ada inspeksi
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-slate-500">
                        Data inspeksi yang dibuat akan muncul di halaman ini.
                        Mulai dengan menambahkan inspeksi gerai baru.
                    </p>

                    <a href="{{ route('inspections.create') }}"
                       class="mt-6 inline-flex items-center gap-2 rounded-xl bg-[#17365D] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#28558A]">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M12 5v14m-7-7h14"/>
                        </svg>
                        Buat Inspeksi Baru
                    </a>
                </div>
            @endif
        </section>

        {{-- Informasi tambahan --}}
        <section class="flex items-start gap-3 rounded-2xl border border-blue-100 bg-blue-50/70 p-5">
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white text-blue-700 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 110-20 10 10 0 010 20z"/>
                </svg>
            </div>

            <div>
                <h4 class="text-sm font-bold text-slate-800">
                    Informasi Sistem
                </h4>
                <p class="mt-1 text-sm leading-6 text-slate-600">
                    Gunakan tombol Detail untuk melihat data dan hasil pemeriksaan.
                    Gunakan tombol Edit untuk memperbarui informasi inspeksi.
                </p>
            </div>
        </section>

    </div>
</x-app-layout>
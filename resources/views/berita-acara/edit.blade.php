<x-app-layout>

    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Berita Acara
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Perbarui data Berita Acara yang sudah tersimpan.
            </p>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Error --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200
                            px-4 py-3 text-sm text-red-700">

                    <p class="font-semibold mb-2">
                        Terdapat kesalahan:
                    </p>

                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>

                </div>
            @endif

            <div class="bg-white shadow-sm sm:rounded-xl">

                <form method="POST"
                      action="{{ route('berita-acara.update', $beritaAcara) }}"
                      enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="p-6 space-y-8">

                        {{-- ========================= --}}
                        {{-- DATA BERITA ACARA --}}
                        {{-- ========================= --}}

                        <div>
                            <h3 class="text-lg font-semibold text-gray-800">
                                Data Berita Acara
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Perbarui informasi utama Berita Acara.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            {{-- Nomor BA --}}
                            <div>
                                <label for="nomor_ba"
                                       class="block text-sm font-medium
                                              text-gray-700 mb-2">
                                    Nomor BA
                                </label>

                                <input type="text"
                                       id="nomor_ba"
                                       name="nomor_ba"
                                       value="{{ old('nomor_ba', $beritaAcara->nomor_ba) }}"
                                       placeholder="BA/001/COM/X/2026"
                                       required
                                       class="w-full rounded-lg border-gray-300
                                              focus:border-blue-500
                                              focus:ring-blue-500">

                                @error('nomor_ba')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label class="block text-sm font-medium
                                              text-gray-700 mb-2">
                                    Tanggal Surat
                                </label>

                                <input type="text"
                                       value="{{ $beritaAcara->tanggal_surat->format('d/m/Y') }}"
                                       readonly
                                       class="w-full rounded-lg border-gray-300
                                              bg-gray-100 text-gray-600">
                            </div>

                            {{-- Nama Mitra --}}
                            <div>
                                <label for="nama_mitra"
                                       class="block text-sm font-medium
                                              text-gray-700 mb-2">
                                    Nama Client / Mitra
                                </label>

                                <input type="text"
                                       id="nama_mitra"
                                       name="nama_mitra"
                                       value="{{ old('nama_mitra', $beritaAcara->nama_mitra) }}"
                                       required
                                       class="w-full rounded-lg border-gray-300
                                              focus:border-blue-500
                                              focus:ring-blue-500">

                                @error('nama_mitra')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Luas Tenant --}}
                            <div>
                                <label for="luas_tenant"
                                       class="block text-sm font-medium
                                              text-gray-700 mb-2">
                                    Luas Tenant
                                </label>

                                <div class="flex">
                                    <input type="number"
                                           step="0.01"
                                           min="0"
                                           id="luas_tenant"
                                           name="luas_tenant"
                                           value="{{ old('luas_tenant', $beritaAcara->luas_tenant) }}"
                                           required
                                           class="w-full rounded-l-lg
                                                  border-gray-300
                                                  focus:border-blue-500
                                                  focus:ring-blue-500">

                                    <span class="inline-flex items-center
                                                 px-4 rounded-r-lg
                                                 border border-l-0
                                                 border-gray-300
                                                 bg-gray-100 text-gray-600">
                                        m²
                                    </span>
                                </div>

                                @error('luas_tenant')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            {{-- Posisi Tenant --}}
                            <div class="md:col-span-2">
                                <label for="posisi_tenant"
                                       class="block text-sm font-medium
                                              text-gray-700 mb-2">
                                    Posisi Tenant di Bandara
                                </label>

                                <input type="text"
                                       id="posisi_tenant"
                                       name="posisi_tenant"
                                       value="{{ old('posisi_tenant', $beritaAcara->posisi_tenant) }}"
                                       required
                                       class="w-full rounded-lg border-gray-300
                                              focus:border-blue-500
                                              focus:ring-blue-500">

                                @error('posisi_tenant')
                                    <p class="mt-1 text-sm text-red-600">
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                        </div>


                        {{-- ========================= --}}
                        {{-- CHECKLIST --}}
                        {{-- ========================= --}}

                        <div class="border-t pt-8">

                            <div class="flex items-center justify-between mb-4">

                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        Checklist Dokumen
                                    </h3>

                                    <p class="text-sm text-gray-500 mt-1">
                                        Centang dokumen yang sudah tersedia.
                                    </p>
                                </div>

                                <button type="button"
                                        onclick="addChecklist()"
                                        class="inline-flex items-center px-4 py-2
                                               bg-blue-600 text-white rounded-lg
                                               text-sm font-semibold
                                               hover:bg-blue-700">
                                    + Tambah Checklist
                                </button>

                            </div>


                            <div id="checklist-container" class="space-y-3">

                                @foreach ($beritaAcara->checklistItems as $index => $item)

                                    <div class="checklist-row flex items-center gap-3">

                                        <input type="hidden"
                                               name="checklist_id[]"
                                               value="{{ $item->id }}">

                                        <input type="checkbox"
                                               name="checklist_checked[{{ $item->id }}]"
                                               value="1"
                                               {{ $item->is_checked ? 'checked' : '' }}
                                               class="rounded border-gray-300
                                                      text-blue-600
                                                      focus:ring-blue-500">

                                        <input type="text"
                                               name="checklist_name[{{ $item->id }}]"
                                               value="{{ $item->nama_checklist }}"
                                               class="flex-1 rounded-lg
                                                      border-gray-300
                                                      focus:border-blue-500
                                                      focus:ring-blue-500">

                                        <button type="button"
                                                onclick="removeChecklist(this)"
                                                class="px-3 py-2 rounded-lg
                                                       bg-red-50 text-red-600
                                                       text-sm hover:bg-red-100">
                                            Hapus
                                        </button>

                                    </div>

                                @endforeach

                            </div>

                        </div>


                        {{-- ========================= --}}
                        {{-- TANDA TANGAN --}}
                        {{-- ========================= --}}

                        <div class="border-t pt-8">

                            <div class="mb-6">
                                <h3 class="text-lg font-semibold text-gray-800">
                                    Tanda Tangan
                                </h3>

                                <p class="text-sm text-gray-500 mt-1">
                                    Tanda tangan dapat diunggah atau digambar secara digital.
                                </p>
                            </div>


                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">


                                {{-- PIHAK PERTAMA --}}
                                <div class="border rounded-xl p-5">

                                    <h4 class="font-semibold text-gray-800 mb-4">
                                        Pihak Pertama
                                    </h4>

                                    <div class="space-y-4">

                                        <div>
                                            <label class="block text-sm font-medium
                                                          text-gray-700 mb-2">
                                                Nama
                                            </label>

                                            <input type="text"
                                                   name="signature_nama_pihak_pertama"
                                                   value="{{ old(
                                                       'signature_nama_pihak_pertama',
                                                       optional(
                                                           $beritaAcara->signatures
                                                               ->firstWhere('pihak', 'Pihak Pertama')
                                                       )->nama
                                                   ) }}"
                                                   class="w-full rounded-lg border-gray-300
                                                          focus:border-blue-500
                                                          focus:ring-blue-500">
                                        </div>


                                        <div>
                                            <label class="block text-sm font-medium
                                                          text-gray-700 mb-2">
                                                Jabatan
                                            </label>

                                            <input type="text"
                                                   name="signature_jabatan_pihak_pertama"
                                                   value="{{ old(
                                                       'signature_jabatan_pihak_pertama',
                                                       optional(
                                                           $beritaAcara->signatures
                                                               ->firstWhere('pihak', 'Pihak Pertama')
                                                       )->jabatan
                                                   ) }}"
                                                   class="w-full rounded-lg border-gray-300
                                                          focus:border-blue-500
                                                          focus:ring-blue-500">
                                        </div>


                                        <div>

                                            <label class="block text-sm font-medium
                                                          text-gray-700 mb-2">
                                                Gambar Tanda Tangan
                                            </label>

                                            <div class="border-2 border-dashed
                                                        border-gray-300 rounded-lg
                                                        p-2 bg-gray-50">

                                                @php
                                                    $signaturePertama =
                                                        $beritaAcara->signatures
                                                            ->firstWhere('pihak', 'Pihak Pertama');
                                                @endphp

                                                @if (
                                                    $signaturePertama &&
                                                    $signaturePertama->signature_file
                                                )

                                                    <img src="{{ asset('storage/' . $signaturePertama->signature_file) }}"
                                                         class="max-h-32 mx-auto mb-3"
                                                         alt="Tanda tangan Pihak Pertama">

                                                @endif

                                                <canvas id="signatureCanvas1"
                                                        width="500"
                                                        height="200"
                                                        class="w-full bg-white border
                                                               border-gray-300 rounded-lg">
                                                </canvas>

                                            </div>

                                            <input type="hidden"
                                                   name="signature_data_pihak_pertama"
                                                   id="signatureData1">

                                            <div class="flex items-center gap-3 mt-3">

                                                <button type="button"
                                                        onclick="clearSignature(1)"
                                                        class="px-3 py-2 rounded-lg
                                                               bg-gray-100 text-gray-700
                                                               text-sm hover:bg-gray-200">
                                                    Hapus Coretan
                                                </button>

                                                <label class="px-3 py-2 rounded-lg
                                                              bg-blue-50 text-blue-700
                                                              text-sm cursor-pointer
                                                              hover:bg-blue-100">

                                                    Upload PNG

                                                    <input type="file"
                                                           name="signature_upload_pihak_pertama"
                                                           id="signatureUpload1"
                                                           accept="image/png"
                                                           class="hidden">

                                                </label>

                                            </div>

                                            <p class="text-xs text-gray-500 mt-2">
                                                Maksimal 2 MB. Format PNG.
                                            </p>

                                        </div>

                                    </div>

                                </div>


                                {{-- PIHAK KEDUA --}}
                                <div class="border rounded-xl p-5">

                                    <h4 class="font-semibold text-gray-800 mb-4">
                                        Pihak Kedua
                                    </h4>

                                    <div class="space-y-4">

                                        <div>
                                            <label class="block text-sm font-medium
                                                          text-gray-700 mb-2">
                                                Nama
                                            </label>

                                            <input type="text"
                                                   name="signature_nama_pihak_kedua"
                                                   value="{{ old(
                                                       'signature_nama_pihak_kedua',
                                                       optional(
                                                           $beritaAcara->signatures
                                                               ->firstWhere('pihak', 'Pihak Kedua')
                                                       )->nama
                                                   ) }}"
                                                   class="w-full rounded-lg border-gray-300
                                                          focus:border-blue-500
                                                          focus:ring-blue-500">
                                        </div>


                                        <div>
                                            <label class="block text-sm font-medium
                                                          text-gray-700 mb-2">
                                                Jabatan
                                            </label>

                                            <input type="text"
                                                   name="signature_jabatan_pihak_kedua"
                                                   value="{{ old(
                                                       'signature_jabatan_pihak_kedua',
                                                       optional(
                                                           $beritaAcara->signatures
                                                               ->firstWhere('pihak', 'Pihak Kedua')
                                                       )->jabatan
                                                   ) }}"
                                                   class="w-full rounded-lg border-gray-300
                                                          focus:border-blue-500
                                                          focus:ring-blue-500">
                                        </div>


                                        <div>

                                            <label class="block text-sm font-medium
                                                          text-gray-700 mb-2">
                                                Gambar Tanda Tangan
                                            </label>

                                            <div class="border-2 border-dashed
                                                        border-gray-300 rounded-lg
                                                        p-2 bg-gray-50">

                                                @php
                                                    $signatureKedua =
                                                        $beritaAcara->signatures
                                                            ->firstWhere('pihak', 'Pihak Kedua');
                                                @endphp

                                                @if (
                                                    $signatureKedua &&
                                                    $signatureKedua->signature_file
                                                )

                                                    <img src="{{ asset('storage/' . $signatureKedua->signature_file) }}"
                                                         class="max-h-32 mx-auto mb-3"
                                                         alt="Tanda tangan Pihak Kedua">

                                                @endif

                                                <canvas id="signatureCanvas2"
                                                        width="500"
                                                        height="200"
                                                        class="w-full bg-white border
                                                               border-gray-300 rounded-lg">
                                                </canvas>

                                            </div>

                                            <input type="hidden"
                                                   name="signature_data_pihak_kedua"
                                                   id="signatureData2">

                                            <div class="flex items-center gap-3 mt-3">

                                                <button type="button"
                                                        onclick="clearSignature(2)"
                                                        class="px-3 py-2 rounded-lg
                                                               bg-gray-100 text-gray-700
                                                               text-sm hover:bg-gray-200">
                                                    Hapus Coretan
                                                </button>

                                                <label class="px-3 py-2 rounded-lg
                                                              bg-blue-50 text-blue-700
                                                              text-sm cursor-pointer
                                                              hover:bg-blue-100">

                                                    Upload PNG

                                                    <input type="file"
                                                           name="signature_upload_pihak_kedua"
                                                           id="signatureUpload2"
                                                           accept="image/png"
                                                           class="hidden">

                                                </label>

                                            </div>

                                            <p class="text-xs text-gray-500 mt-2">
                                                Maksimal 2 MB. Format PNG.
                                            </p>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ========================= --}}
                    {{-- BUTTON --}}
                    {{-- ========================= --}}

                    <div class="flex items-center justify-end gap-3
                                px-6 py-5 bg-gray-50 border-t">

                        <a href="{{ route('berita-acara.show', $beritaAcara) }}"
                           class="px-5 py-2.5 rounded-lg bg-gray-200
                                  text-gray-700 font-semibold text-sm
                                  hover:bg-gray-300">
                            Batal
                        </a>

                        <button type="submit"
                                class="px-5 py-2.5 rounded-lg bg-blue-600
                                       text-white font-semibold text-sm
                                       hover:bg-blue-700">
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>
    </div>


    {{-- ========================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================= --}}

    <script>

        // =====================================
        // CHECKLIST
        // =====================================

        function addChecklist() {

            const container =
                document.getElementById('checklist-container');

            const row =
                document.createElement('div');

            row.className =
                'checklist-row flex items-center gap-3';

            row.innerHTML = `
                <input type="hidden"
                       name="new_checklist[]"
                       value="">

                <input type="checkbox"
                       name="new_checklist_checked[]"
                       value="1"
                       class="rounded border-gray-300
                              text-blue-600
                              focus:ring-blue-500">

                <input type="text"
                       name="new_checklist_name[]"
                       placeholder="Nama checklist..."
                       class="flex-1 rounded-lg
                              border-gray-300
                              focus:border-blue-500
                              focus:ring-blue-500">

                <button type="button"
                        onclick="removeChecklist(this)"
                        class="px-3 py-2 rounded-lg
                               bg-red-50 text-red-600
                               text-sm hover:bg-red-100">
                    Hapus
                </button>
            `;

            container.appendChild(row);
        }


        function removeChecklist(button) {

            const row =
                button.closest('.checklist-row');

            if (row) {
                row.remove();
            }
        }


        // =====================================
        // SIGNATURE PAD
        // =====================================

        function setupSignature(canvasId, hiddenId, uploadId) {

            const canvas =
                document.getElementById(canvasId);

            const hidden =
                document.getElementById(hiddenId);

            const upload =
                document.getElementById(uploadId);

            if (!canvas) return;

            const ctx =
                canvas.getContext('2d');

            let drawing = false;


            function getPosition(event) {

                const rect =
                    canvas.getBoundingClientRect();

                const scaleX =
                    canvas.width / rect.width;

                const scaleY =
                    canvas.height / rect.height;

                if (event.touches) {

                    return {
                        x:
                            (event.touches[0].clientX - rect.left)
                            * scaleX,

                        y:
                            (event.touches[0].clientY - rect.top)
                            * scaleY
                    };

                }

                return {
                    x:
                        (event.clientX - rect.left)
                        * scaleX,

                    y:
                        (event.clientY - rect.top)
                        * scaleY
                };
            }


            function startDrawing(event) {

                event.preventDefault();

                drawing = true;

                const pos =
                    getPosition(event);

                ctx.beginPath();

                ctx.moveTo(
                    pos.x,
                    pos.y
                );
            }


            function draw(event) {

                if (!drawing) return;

                event.preventDefault();

                const pos =
                    getPosition(event);

                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.strokeStyle = '#000000';

                ctx.lineTo(
                    pos.x,
                    pos.y
                );

                ctx.stroke();

                hidden.value =
                    canvas.toDataURL('image/png');
            }


            function stopDrawing(event) {

                if (!drawing) return;

                event.preventDefault();

                drawing = false;

                hidden.value =
                    canvas.toDataURL('image/png');
            }


            canvas.addEventListener(
                'mousedown',
                startDrawing
            );

            canvas.addEventListener(
                'mousemove',
                draw
            );

            canvas.addEventListener(
                'mouseup',
                stopDrawing
            );

            canvas.addEventListener(
                'mouseleave',
                stopDrawing
            );


            canvas.addEventListener(
                'touchstart',
                startDrawing,
                { passive: false }
            );

            canvas.addEventListener(
                'touchmove',
                draw,
                { passive: false }
            );

            canvas.addEventListener(
                'touchend',
                stopDrawing,
                { passive: false }
            );


            upload.addEventListener(
                'change',
                function () {

                    if (this.files.length > 0) {

                        ctx.clearRect(
                            0,
                            0,
                            canvas.width,
                            canvas.height
                        );

                        hidden.value = '';
                    }

                }
            );
        }


        function clearSignature(number) {

            const canvas =
                document.getElementById(
                    'signatureCanvas' + number
                );

            const hidden =
                document.getElementById(
                    'signatureData' + number
                );

            const upload =
                document.getElementById(
                    'signatureUpload' + number
                );

            if (!canvas) return;

            const ctx =
                canvas.getContext('2d');

            ctx.clearRect(
                0,
                0,
                canvas.width,
                canvas.height
            );

            hidden.value = '';

            if (upload) {
                upload.value = '';
            }
        }


        setupSignature(
            'signatureCanvas1',
            'signatureData1',
            'signatureUpload1'
        );

        setupSignature(
            'signatureCanvas2',
            'signatureData2',
            'signatureUpload2'
        );

    </script>

</x-app-layout>
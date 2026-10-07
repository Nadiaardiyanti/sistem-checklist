<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Buat Berita Acara
        </h2>
    </x-slot>

    <div class="py-8">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- SUCCESS MESSAGE --}}
            @if (session('success'))
                <div class="mb-6 rounded-lg bg-green-100 border border-green-300 px-4 py-3 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- ERROR MESSAGE --}}
            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-100 border border-red-300 px-4 py-3 text-red-800">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="bg-white shadow-sm rounded-xl p-6">

                <form
                    method="POST"
                    action="{{ route('berita-acara.store') }}"
                    id="ba-form"
                    enctype="multipart/form-data"
                >

                    @csrf


                    {{-- ========================================================= --}}
                    {{-- DATA BERITA ACARA --}}
                    {{-- ========================================================= --}}

                    <div class="mb-8">

                        <h3 class="text-lg font-semibold text-gray-800 mb-5">
                            Data Berita Acara
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                            {{-- Nomor BA --}}
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nomor Dokumen
                                </label>

                                <input
                                    type="text"
                                    name="nomor_ba"
                                    value="{{ old('nomor_ba') }}"
                                    placeholder="BA/001/COM/X/2026"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                >

                            </div>


                            {{-- Tanggal --}}
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Surat
                                </label>

                                <input
                                    type="text"
                                    value="{{ now()->format('d/m/Y') }}"
                                    readonly
                                    class="w-full rounded-lg border-gray-300 bg-gray-100"
                                >

                            </div>


                            {{-- Nama Mitra --}}
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Mitra
                                </label>

                                <input
                                    type="text"
                                    name="nama_mitra"
                                    value="{{ old('nama_mitra') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                >

                            </div>


                            {{-- Luas Tenant --}}
                            <div>

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Luas Tenant (m²)
                                </label>

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    name="luas_tenant"
                                    value="{{ old('luas_tenant') }}"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                >

                            </div>


                            {{-- Posisi Tenant --}}
                            <div class="md:col-span-2">

                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Posisi Tenant di Bandara
                                </label>

                                <input
                                    type="text"
                                    name="posisi_tenant"
                                    value="{{ old('posisi_tenant') }}"
                                    placeholder="Contoh: Terminal 1, Area Keberangkatan"
                                    required
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                >

                            </div>

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- CHECKLIST --}}
                    {{-- ========================================================= --}}

                    <div class="mb-8">

                        <div class="flex items-center justify-between mb-5">

                            <h3 class="text-lg font-semibold text-gray-800">
                                Checklist Dokumen
                            </h3>

                            <button
                                type="button"
                                id="add-checklist"
                                class="px-4 py-2 rounded-lg bg-indigo-600 text-white text-sm font-medium hover:bg-indigo-700"
                            >
                                + Tambah Checklist
                            </button>

                        </div>


                        <div class="space-y-3">

                            {{-- KTP --}}
                            <label class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="default_checklist[]"
                                    value="KTP"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                >

                                <span class="text-gray-700">
                                    KTP
                                </span>

                            </label>


                            {{-- NIB --}}
                            <label class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="default_checklist[]"
                                    value="NIB"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                >

                                <span class="text-gray-700">
                                    NIB
                                </span>

                            </label>


                            {{-- NPWP --}}
                            <label class="flex items-center gap-3">

                                <input
                                    type="checkbox"
                                    name="default_checklist[]"
                                    value="NPWP"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                >

                                <span class="text-gray-700">
                                    NPWP
                                </span>

                            </label>

                        </div>


                        {{-- Checklist Tambahan --}}
                        <div
                            id="additional-checklist-container"
                            class="mt-4 space-y-3"
                        >
                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- TANDA TANGAN --}}
                    {{-- ========================================================= --}}

                    <div class="mb-8">

                        <h3 class="text-lg font-semibold text-gray-800 mb-5">
                            Tanda Tangan
                        </h3>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                            {{-- ================================================= --}}
                            {{-- PIHAK PERTAMA --}}
                            {{-- ================================================= --}}

                            <div class="border border-gray-200 rounded-xl p-5">

                                <h4 class="font-semibold text-gray-800 mb-4">
                                    Pihak Pertama
                                </h4>


                                {{-- Nama --}}
                                <div class="mb-4">

                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama
                                    </label>

                                    <input
                                        type="text"
                                        name="signature_nama_pihak_pertama"
                                        value="{{ old('signature_nama_pihak_pertama') }}"
                                        placeholder="Nama pihak pertama"
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                </div>


                                {{-- Jabatan --}}
                                <div class="mb-4">

                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Jabatan
                                    </label>

                                    <input
                                        type="text"
                                        name="signature_jabatan_pihak_pertama"
                                        value="{{ old('signature_jabatan_pihak_pertama') }}"
                                        placeholder="Jabatan"
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                </div>


                                {{-- Canvas --}}
                                <div>

                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanda Tangan Digital
                                    </label>

                                    <div class="border border-gray-300 rounded-lg bg-white overflow-hidden">

                                        <canvas
                                            id="signature-pad-pihak-pertama"
                                            width="500"
                                            height="220"
                                            style="
                                                width: 100%;
                                                height: 220px;
                                                touch-action: none;
                                                cursor: crosshair;
                                            "
                                        ></canvas>

                                    </div>


                                    <input
                                        type="hidden"
                                        name="signature_data_pihak_pertama"
                                        id="signature-data-pihak-pertama"
                                    >


                                    <button
                                        type="button"
                                        id="clear-signature-pihak-pertama"
                                        class="mt-3 px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100"
                                    >
                                        Hapus Tanda Tangan
                                    </button>

                                </div>


                                {{-- Upload PNG --}}
                                <div class="mt-5">

                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Atau Upload Tanda Tangan
                                    </label>

                                    <input
                                        type="file"
                                        name="signature_upload_pihak_pertama"
                                        id="signature-upload-pihak-pertama"
                                        accept="image/png"
                                        class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none"
                                    >

                                    <p class="mt-1 text-xs text-gray-500">
                                        Format PNG, maksimal 2 MB.
                                    </p>

                                </div>

                            </div>


                            {{-- ================================================= --}}
                            {{-- PIHAK KEDUA --}}
                            {{-- ================================================= --}}

                            <div class="border border-gray-200 rounded-xl p-5">

                                <h4 class="font-semibold text-gray-800 mb-4">
                                    Pihak Kedua
                                </h4>


                                {{-- Nama --}}
                                <div class="mb-4">

                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Nama
                                    </label>

                                    <input
                                        type="text"
                                        name="signature_nama_pihak_kedua"
                                        value="{{ old('signature_nama_pihak_kedua') }}"
                                        placeholder="Nama pihak kedua"
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                </div>


                                {{-- Jabatan --}}
                                <div class="mb-4">

                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Jabatan
                                    </label>

                                    <input
                                        type="text"
                                        name="signature_jabatan_pihak_kedua"
                                        value="{{ old('signature_jabatan_pihak_kedua') }}"
                                        placeholder="Jabatan"
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                    >

                                </div>


                                {{-- Canvas --}}
                                <div>

                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Tanda Tangan Digital
                                    </label>

                                    <div class="border border-gray-300 rounded-lg bg-white overflow-hidden">

                                        <canvas
                                            id="signature-pad-pihak-kedua"
                                            width="500"
                                            height="220"
                                            style="
                                                width: 100%;
                                                height: 220px;
                                                touch-action: none;
                                                cursor: crosshair;
                                            "
                                        ></canvas>

                                    </div>


                                    <input
                                        type="hidden"
                                        name="signature_data_pihak_kedua"
                                        id="signature-data-pihak-kedua"
                                    >


                                    <button
                                        type="button"
                                        id="clear-signature-pihak-kedua"
                                        class="mt-3 px-4 py-2 text-sm rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100"
                                    >
                                        Hapus Tanda Tangan
                                    </button>

                                </div>


                                {{-- Upload PNG --}}
                                <div class="mt-5">

                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Atau Upload Tanda Tangan
                                    </label>

                                    <input
                                        type="file"
                                        name="signature_upload_pihak_kedua"
                                        id="signature-upload-pihak-kedua"
                                        accept="image/png"
                                        class="block w-full text-sm text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none"
                                    >

                                    <p class="mt-1 text-xs text-gray-500">
                                        Format PNG, maksimal 2 MB.
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- ========================================================= --}}
                    {{-- BUTTON --}}
                    {{-- ========================================================= --}}

                    <div class="flex justify-end gap-3">

                        <a
                            href="{{ route('dashboard') }}"
                            class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="px-5 py-2.5 rounded-lg bg-indigo-600 text-white font-medium hover:bg-indigo-700"
                        >
                            Simpan Berita Acara
                        </button>

                    </div>


                </form>

            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- JAVASCRIPT --}}
    {{-- ========================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {


            // =========================================================
            // TAMBAH CHECKLIST
            // =========================================================

            const addChecklistButton =
                document.getElementById('add-checklist');

            const checklistContainer =
                document.getElementById('additional-checklist-container');

            let checklistIndex = 0;


            addChecklistButton.addEventListener('click', function () {

                const wrapper = document.createElement('div');

                wrapper.className =
                    'flex items-center gap-3';


                wrapper.innerHTML = `

                    <input
                        type="checkbox"
                        name="additional_checklist_checked[]"
                        value="${checklistIndex}"
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                    >

                    <input
                        type="text"
                        name="additional_checklist[]"
                        placeholder="Nama checklist tambahan"
                        class="flex-1 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    >

                    <button
                        type="button"
                        class="remove-checklist px-3 py-2 text-sm rounded-lg border border-red-300 text-red-600 hover:bg-red-50"
                    >
                        Hapus
                    </button>

                `;


                checklistContainer.appendChild(wrapper);


                wrapper
                    .querySelector('.remove-checklist')
                    .addEventListener('click', function () {

                        wrapper.remove();

                    });


                checklistIndex++;

            });


            // =========================================================
            // CANVAS SIGNATURE
            // =========================================================

            function setupSignaturePad(
                canvasId,
                clearButtonId,
                hiddenInputId,
                uploadInputId
            ) {

                const canvas =
                    document.getElementById(canvasId);

                const clearButton =
                    document.getElementById(clearButtonId);

                const hiddenInput =
                    document.getElementById(hiddenInputId);

                const uploadInput =
                    document.getElementById(uploadInputId);

                const ctx =
                    canvas.getContext('2d');


                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                ctx.strokeStyle = '#000000';


                let drawing = false;


                function getPosition(event) {

                    const rect =
                        canvas.getBoundingClientRect();

                    let clientX;
                    let clientY;


                    if (
                        event.touches &&
                        event.touches.length > 0
                    ) {

                        clientX =
                            event.touches[0].clientX;

                        clientY =
                            event.touches[0].clientY;

                    } else {

                        clientX =
                            event.clientX;

                        clientY =
                            event.clientY;

                    }


                    return {

                        x:
                            (clientX - rect.left)
                            *
                            (canvas.width / rect.width),

                        y:
                            (clientY - rect.top)
                            *
                            (canvas.height / rect.height)

                    };

                }


                function startDrawing(event) {

                    event.preventDefault();


                    // Kalau menggambar,
                    // upload PNG dikosongkan.
                    uploadInput.value = '';


                    drawing = true;


                    const position =
                        getPosition(event);


                    ctx.beginPath();

                    ctx.moveTo(
                        position.x,
                        position.y
                    );

                }


                function draw(event) {

                    if (!drawing) {
                        return;
                    }


                    event.preventDefault();


                    const position =
                        getPosition(event);


                    ctx.lineTo(
                        position.x,
                        position.y
                    );

                    ctx.stroke();

                }


                function stopDrawing(event) {

                    if (!drawing) {
                        return;
                    }


                    event.preventDefault();

                    drawing = false;

                    ctx.closePath();

                }


                // Mouse
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


                // Touch
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


                // Upload PNG
                uploadInput.addEventListener(
                    'change',
                    function () {

                        if (this.files.length > 0) {

                            // Kalau upload PNG,
                            // canvas dikosongkan.

                            ctx.clearRect(
                                0,
                                0,
                                canvas.width,
                                canvas.height
                            );

                            hiddenInput.value = '';

                        }

                    }
                );


                // Hapus
                clearButton.addEventListener(
                    'click',
                    function () {

                        ctx.clearRect(
                            0,
                            0,
                            canvas.width,
                            canvas.height
                        );

                        hiddenInput.value = '';

                        uploadInput.value = '';

                    }
                );


                return {

                    save: function () {

                        // Kalau pakai PNG,
                        // jangan simpan canvas kosong.

                        if (uploadInput.files.length > 0) {

                            hiddenInput.value = '';

                            return;

                        }


                        hiddenInput.value =
                            canvas.toDataURL('image/png');

                    }

                };

            }


            // =========================================================
            // PIHAK PERTAMA
            // =========================================================

            const signaturePihakPertama =
                setupSignaturePad(
                    'signature-pad-pihak-pertama',
                    'clear-signature-pihak-pertama',
                    'signature-data-pihak-pertama',
                    'signature-upload-pihak-pertama'
                );


            // =========================================================
            // PIHAK KEDUA
            // =========================================================

            const signaturePihakKedua =
                setupSignaturePad(
                    'signature-pad-pihak-kedua',
                    'clear-signature-pihak-kedua',
                    'signature-data-pihak-kedua',
                    'signature-upload-pihak-kedua'
                );


            // =========================================================
            // SUBMIT
            // =========================================================

            const form =
                document.getElementById('ba-form');


            form.addEventListener(
                'submit',
                function () {

                    signaturePihakPertama.save();

                    signaturePihakKedua.save();

                }
            );

        });

    </script>

</x-app-layout>
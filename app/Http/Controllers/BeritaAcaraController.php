<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BeritaAcara;
use App\Models\BeritaAcaraVersion;
use App\Services\GoogleSheetsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class BeritaAcaraController extends Controller
{
    public function __construct(
        protected GoogleSheetsService $googleSheets
    ) {
    }

    /*
    |--------------------------------------------------------------------------
    | HISTORY / DAFTAR BERITA ACARA
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = BeritaAcara::with('creator');

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where(
                    'nomor_ba',
                    'like',
                    '%' . $search . '%'
                )->orWhere(
                    'nama_mitra',
                    'like',
                    '%' . $search . '%'
                );
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate(
                'tanggal_surat',
                $request->tanggal
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        $beritaAcaras = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view(
            'berita-acara.index',
            compact('beritaAcaras')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN BUAT BERITA ACARA
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view('berita-acara.create');
    }

    /*
    |--------------------------------------------------------------------------
    | HALAMAN EDIT BERITA ACARA
    |--------------------------------------------------------------------------
    */

    public function edit(BeritaAcara $beritaAcara)
    {
        $beritaAcara->load([
            'checklistItems',
            'signatures',
        ]);

        return view(
            'berita-acara.edit',
            compact('beritaAcara')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE BERITA ACARA
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        BeritaAcara $beritaAcara
    ) {
        $validated = $request->validate([

            // DATA BA
            'nomor_ba' =>
                'required|string|max:255|unique:berita_acaras,nomor_ba,' .
                $beritaAcara->id,

            'nama_mitra' =>
                'required|string|max:255',

            'luas_tenant' =>
                'required|numeric|min:0',

            'posisi_tenant' =>
                'required|string|max:255',

            // CHECKLIST LAMA
            'checklist_id' =>
                'nullable|array',

            'checklist_id.*' =>
                'integer',

            'checklist_name' =>
                'nullable|array',

            'checklist_name.*' =>
                'nullable|string|max:255',

            'checklist_checked' =>
                'nullable|array',

            // CHECKLIST BARU
            'new_checklist_name' =>
                'nullable|array',

            'new_checklist_name.*' =>
                'nullable|string|max:255',

            'new_checklist_checked' =>
                'nullable|array',

            // PIHAK PERTAMA
            'signature_nama_pihak_pertama' =>
                'nullable|string|max:255',

            'signature_jabatan_pihak_pertama' =>
                'nullable|string|max:255',

            'signature_data_pihak_pertama' =>
                'nullable|string',

            'signature_upload_pihak_pertama' =>
                'nullable|file|mimes:png|max:2048',

            // PIHAK KEDUA
            'signature_nama_pihak_kedua' =>
                'nullable|string|max:255',

            'signature_jabatan_pihak_kedua' =>
                'nullable|string|max:255',

            'signature_data_pihak_kedua' =>
                'nullable|string',

            'signature_upload_pihak_kedua' =>
                'nullable|file|mimes:png|max:2048',
        ]);

        /*
        |--------------------------------------------------------------------------
        | AMBIL DATA LAMA
        |--------------------------------------------------------------------------
        */

        $beritaAcara->load([
            'checklistItems',
            'signatures',
        ]);

        $oldNomorBa = $beritaAcara->nomor_ba;
        $oldNamaMitra = $beritaAcara->nama_mitra;
        $oldLuasTenant = $beritaAcara->luas_tenant;
        $oldPosisiTenant = $beritaAcara->posisi_tenant;

        $oldChecklist = $beritaAcara->checklistItems
            ->map(function ($item) {
                return [
                    'id' =>
                        $item->id,

                    'nama_checklist' =>
                        $item->nama_checklist,

                    'is_checked' =>
                        $item->is_checked,
                ];
            })
            ->values()
            ->toArray();

        $oldSignatures = $beritaAcara->signatures
            ->map(function ($signature) {
                return [
                    'pihak' =>
                        $signature->pihak,

                    'nama' =>
                        $signature->nama,

                    'jabatan' =>
                        $signature->jabatan,

                    'signature_type' =>
                        $signature->signature_type,

                    'signature_file' =>
                        $signature->signature_file,
                ];
            })
            ->values()
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN SNAPSHOT DATA LAMA
        |--------------------------------------------------------------------------
        */

        BeritaAcaraVersion::create([
            'berita_acara_id' =>
                $beritaAcara->id,

            'user_id' =>
                auth()->id(),

            'nomor_ba' =>
                $beritaAcara->nomor_ba,

            'tanggal_surat' =>
                $beritaAcara->tanggal_surat,

            'nama_mitra' =>
                $beritaAcara->nama_mitra,

            'luas_tenant' =>
                $beritaAcara->luas_tenant,

            'posisi_tenant' =>
                $beritaAcara->posisi_tenant,

            'checklist_data' =>
                $beritaAcara->checklistItems
                    ->map(function ($item) {
                        return [
                            'id' =>
                                $item->id,

                            'nama_checklist' =>
                                $item->nama_checklist,

                            'is_checked' =>
                                $item->is_checked,
                        ];
                    })
                    ->toJson(),

            'signature_data' =>
                $beritaAcara->signatures
                    ->map(function ($signature) {
                        return [
                            'id' =>
                                $signature->id,

                            'pihak' =>
                                $signature->pihak,

                            'nama' =>
                                $signature->nama,

                            'jabatan' =>
                                $signature->jabatan,

                            'signature_type' =>
                                $signature->signature_type,

                            'signature_file' =>
                                $signature->signature_file,
                        ];
                    })
                    ->toJson(),

            'action' =>
                'update',
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE DATA UTAMA
        |--------------------------------------------------------------------------
        */

        $beritaAcara->update([
            'nomor_ba' =>
                $validated['nomor_ba'],

            'nama_mitra' =>
                $validated['nama_mitra'],

            'luas_tenant' =>
                $validated['luas_tenant'],

            'posisi_tenant' =>
                $validated['posisi_tenant'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE CHECKLIST LAMA
        |--------------------------------------------------------------------------
        */

        $checklistIds =
            $request->input(
                'checklist_id',
                []
            );

        $checklistNames =
            $request->input(
                'checklist_name',
                []
            );

        $checkedItems =
            $request->input(
                'checklist_checked',
                []
            );

        foreach ($checklistIds as $id) {
            $item = $beritaAcara
                ->checklistItems()
                ->where('id', $id)
                ->first();

            if (!$item) {
                continue;
            }

            $item->update([
                'nama_checklist' =>
                    $checklistNames[$id]
                    ?? $item->nama_checklist,

                'is_checked' =>
                    isset($checkedItems[$id]),
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | TAMBAH CHECKLIST BARU
        |--------------------------------------------------------------------------
        */

        $newChecklistNames =
            $request->input(
                'new_checklist_name',
                []
            );

        $newChecklistChecked =
            $request->input(
                'new_checklist_checked',
                []
            );

        foreach ($newChecklistNames as $index => $name) {
            if (empty(trim($name))) {
                continue;
            }

            $beritaAcara
                ->checklistItems()
                ->create([
                    'nama_checklist' =>
                        trim($name),

                    'is_checked' =>
                        isset(
                            $newChecklistChecked[$index]
                        ),
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE TANDA TANGAN PIHAK PERTAMA
        |--------------------------------------------------------------------------
        */

        $this->updateSignature(
            $request,
            $beritaAcara,
            'Pihak Pertama',
            'signature_nama_pihak_pertama',
            'signature_jabatan_pihak_pertama',
            'signature_data_pihak_pertama',
            'signature_upload_pihak_pertama'
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE TANDA TANGAN PIHAK KEDUA
        |--------------------------------------------------------------------------
        */

        $this->updateSignature(
            $request,
            $beritaAcara,
            'Pihak Kedua',
            'signature_nama_pihak_kedua',
            'signature_jabatan_pihak_kedua',
            'signature_data_pihak_kedua',
            'signature_upload_pihak_kedua'
        );

         /*
        |--------------------------------------------------------------------------
        | CEK KELENGKAPAN DAN UPDATE STATUS OTOMATIS
        |--------------------------------------------------------------------------
        */

        $beritaAcara->load([
            'checklistItems',
            'signatures',
        ]);

        $semuaChecklistLengkap =
            $beritaAcara->checklistItems->isNotEmpty()
            && $beritaAcara->checklistItems->every(
                fn ($item) => (bool) $item->is_checked
            );

        $pihakPertama = $beritaAcara->signatures
            ->firstWhere('pihak', 'Pihak Pertama');

        $pihakKedua = $beritaAcara->signatures
            ->firstWhere('pihak', 'Pihak Kedua');

        $semuaTandaTanganLengkap =
            $pihakPertama
            && !empty($pihakPertama->signature_file)
            && $pihakKedua
            && !empty($pihakKedua->signature_file);

        $statusBaru =
            ($semuaChecklistLengkap && $semuaTandaTanganLengkap)
                ? 'selesai'
                : 'draft';

        $beritaAcara->update([
            'status' => $statusBaru,
        ]);

        $beritaAcara->refresh();
        /*
        |--------------------------------------------------------------------------
        | CATAT PERUBAHAN
        |--------------------------------------------------------------------------
        */

        $this->logChanges(
            $beritaAcara,
            $oldNomorBa,
            $oldNamaMitra,
            $oldLuasTenant,
            $oldPosisiTenant,
            $oldChecklist,
            $oldSignatures
        );

        /*
        |--------------------------------------------------------------------------
        | UPDATE GOOGLE SHEETS - DATA BA
        |--------------------------------------------------------------------------
        |
        | Setelah BA selesai diedit, cari ID BA yang sama
        | di Google Sheets lalu update baris tersebut.
        |
        */

        $this->syncDataBA($beritaAcara);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'berita-acara.show',
                $beritaAcara
            )
            ->with(
                'success',
                'Berita Acara berhasil diperbarui.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN BERITA ACARA BARU
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $validated = $request->validate([

            'nomor_ba' =>
                'required|string|max:255|unique:berita_acaras,nomor_ba',

            'nama_mitra' =>
                'required|string|max:255',

            'luas_tenant' =>
                'required|numeric|min:0',

            'posisi_tenant' =>
                'required|string|max:255',

            'default_checklist' =>
                'nullable|array',

            'additional_checklist' =>
                'nullable|array',

            'additional_checklist.*' =>
                'nullable|string|max:255',

            'signature_nama_pihak_pertama' =>
                'nullable|string|max:255',

            'signature_jabatan_pihak_pertama' =>
                'nullable|string|max:255',

            'signature_data_pihak_pertama' =>
                'nullable|string',

            'signature_upload_pihak_pertama' =>
                'nullable|file|mimes:png|max:2048',

            'signature_nama_pihak_kedua' =>
                'nullable|string|max:255',

            'signature_jabatan_pihak_kedua' =>
                'nullable|string|max:255',

            'signature_data_pihak_kedua' =>
                'nullable|string',

            'signature_upload_pihak_kedua' =>
                'nullable|file|mimes:png|max:2048',
        ]);

        /*
        |--------------------------------------------------------------------------
        | DATA DASAR BA
        |--------------------------------------------------------------------------
        */

        $validated['tanggal_surat'] =
            now()->toDateString();

        $validated['status'] =
            'draft';

        $validated['created_by'] =
            auth()->id();

        /*
        |--------------------------------------------------------------------------
        | SIMPAN DATA BA
        |--------------------------------------------------------------------------
        */

        $beritaAcara = BeritaAcara::create([
            'nomor_ba' =>
                $validated['nomor_ba'],

            'tanggal_surat' =>
                $validated['tanggal_surat'],

            'nama_mitra' =>
                $validated['nama_mitra'],

            'luas_tenant' =>
                $validated['luas_tenant'],

            'posisi_tenant' =>
                $validated['posisi_tenant'],

            'status' =>
                $validated['status'],

            'created_by' =>
                $validated['created_by'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | CHECKLIST DEFAULT
        |--------------------------------------------------------------------------
        */

        $defaultChecklist = [
            'KTP',
            'NIB',
            'NPWP',
        ];

        foreach ($defaultChecklist as $item) {
            $isChecked =
                in_array(
                    $item,
                    $request->input(
                        'default_checklist',
                        []
                    )
                );

            $beritaAcara
                ->checklistItems()
                ->create([
                    'nama_checklist' =>
                        $item,

                    'is_checked' =>
                        $isChecked,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | CHECKLIST TAMBAHAN
        |--------------------------------------------------------------------------
        */

        $additionalChecklist =
            $request->input(
                'additional_checklist',
                []
            );

        $checkedItems =
            $request->input(
                'additional_checklist_checked',
                []
            );

        foreach (
            $additionalChecklist as $index => $item
        ) {
            if (empty(trim($item))) {
                continue;
            }

            $isChecked =
                isset(
                    $checkedItems[$index]
                );

            $beritaAcara
                ->checklistItems()
                ->create([
                    'nama_checklist' =>
                        $item,

                    'is_checked' =>
                        $isChecked,
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | SIMPAN TANDA TANGAN
        |--------------------------------------------------------------------------
        */

        $this->saveSignature(
            $request,
            $beritaAcara,
            'Pihak Pertama',
            'signature_nama_pihak_pertama',
            'signature_jabatan_pihak_pertama',
            'signature_data_pihak_pertama',
            'signature_upload_pihak_pertama'
        );

        $this->saveSignature(
            $request,
            $beritaAcara,
            'Pihak Kedua',
            'signature_nama_pihak_kedua',
            'signature_jabatan_pihak_kedua',
            'signature_data_pihak_kedua',
            'signature_upload_pihak_kedua'
        );

        /*
        |--------------------------------------------------------------------------
        | GOOGLE SHEETS - DATA BA
        |--------------------------------------------------------------------------
        */

        $this->syncDataBA($beritaAcara);

        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG - BUAT BA
        |--------------------------------------------------------------------------
        */

        $this->createActivityLog(
            $beritaAcara,
            'Membuat BA',
            'Membuat Berita Acara ' .
                $beritaAcara->nomor_ba
        );

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'berita-acara.show',
                $beritaAcara
            )
            ->with(
                'success',
                'Berita Acara berhasil disimpan.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | TANDAI BERITA ACARA SELESAI
    |--------------------------------------------------------------------------
    */

    public function markAsCompleted(
        BeritaAcara $beritaAcara
    ) {
        if ($beritaAcara->status === 'selesai') {
            return redirect()
                ->route(
                    'berita-acara.show',
                    $beritaAcara
                )
                ->with(
                    'success',
                    'Berita Acara sudah berstatus selesai.'
                );
        }

        $beritaAcara->load([
            'checklistItems',
            'signatures',
        ]);

        /*
        |--------------------------------------------------------------------------
        | CEK CHECKLIST
        |--------------------------------------------------------------------------
        */

        $hasIncompleteChecklist =
            $beritaAcara
                ->checklistItems
                ->contains(function ($item) {
                    return !$item->is_checked;
                });

        if ($hasIncompleteChecklist) {
            return redirect()
                ->route(
                    'berita-acara.show',
                    $beritaAcara
                )
                ->with(
                    'error',
                    'Berita Acara belum dapat diselesaikan karena masih ada checklist yang belum lengkap.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | CEK TANDA TANGAN
        |--------------------------------------------------------------------------
        */

        $pihakPertama =
            $beritaAcara
                ->signatures
                ->firstWhere(
                    'pihak',
                    'Pihak Pertama'
                );

        $pihakKedua =
            $beritaAcara
                ->signatures
                ->firstWhere(
                    'pihak',
                    'Pihak Kedua'
                );

        if (
            !$pihakPertama ||
            !$pihakKedua ||
            !$pihakPertama->signature_file ||
            !$pihakKedua->signature_file
        ) {
            return redirect()
                ->route(
                    'berita-acara.show',
                    $beritaAcara
                )
                ->with(
                    'error',
                    'Berita Acara belum dapat diselesaikan karena kedua tanda tangan belum lengkap.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE STATUS
        |--------------------------------------------------------------------------
        */

        $beritaAcara->update([
            'status' =>
                'selesai',
        ]);

        /*
        |--------------------------------------------------------------------------
        | UPDATE GOOGLE SHEETS
        |--------------------------------------------------------------------------
        */

        $this->syncDataBA($beritaAcara);

        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        $this->createActivityLog(
            $beritaAcara,
            'Menyelesaikan BA',
            'Berita Acara ' .
                $beritaAcara->nomor_ba .
                ' diubah menjadi status Selesai.'
        );

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'berita-acara.show',
                $beritaAcara
            )
            ->with(
                'success',
                'Berita Acara berhasil ditandai sebagai selesai.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | RIWAYAT PERUBAHAN BERITA ACARA
    |--------------------------------------------------------------------------
    */

    public function versions(
        BeritaAcara $beritaAcara
    ) {
        $versions = $beritaAcara
            ->versions()
            ->with('user')
            ->latest()
            ->get();

        return view(
            'berita-acara.versions',
            compact(
                'beritaAcara',
                'versions'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DETAIL / PREVIEW BERITA ACARA
    |--------------------------------------------------------------------------
    */

    public function show(
        BeritaAcara $beritaAcara
    ) {
        $beritaAcara->load([
            'checklistItems',
            'signatures',
            'creator',
        ]);

        return view(
            'berita-acara.show',
            compact('beritaAcara')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD PDF
    |--------------------------------------------------------------------------
    */

    public function pdf(
        BeritaAcara $beritaAcara
    ) {
        $beritaAcara->load([
            'checklistItems',
            'signatures',
            'creator',
        ]);

        $pdf = Pdf::loadView(
            'berita-acara.pdf',
            compact('beritaAcara')
        );

        $pdf->setPaper(
            'A4',
            'portrait'
        );

        return $pdf->download(
            'BA-' .
                $beritaAcara->nomor_ba .
                '.pdf'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOG AKTIVITAS
    |--------------------------------------------------------------------------
    */

    public function activityLogs()
    {
        $activityLogs = ActivityLog::with([
            'user',
            'beritaAcara',
        ])
            ->latest()
            ->paginate(15);

        return view(
            'activity-logs.index',
            compact('activityLogs')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CATAT PERUBAHAN DETAIL
    |--------------------------------------------------------------------------
    */

    private function logChanges(
        BeritaAcara $beritaAcara,
        $oldNomorBa,
        $oldNamaMitra,
        $oldLuasTenant,
        $oldPosisiTenant,
        array $oldChecklist,
        array $oldSignatures
    ) {
        /*
        |--------------------------------------------------------------------------
        | NOMOR BA
        |--------------------------------------------------------------------------
        */

        if (
            $oldNomorBa !=
            $beritaAcara->nomor_ba
        ) {
            $this->createActivityLog(
                $beritaAcara,
                'Mengubah Nomor BA',
                'Nomor BA diubah dari "' .
                    $oldNomorBa .
                    '" menjadi "' .
                    $beritaAcara->nomor_ba .
                    '"'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | NAMA MITRA
        |--------------------------------------------------------------------------
        */

        if (
            $oldNamaMitra !=
            $beritaAcara->nama_mitra
        ) {
            $this->createActivityLog(
                $beritaAcara,
                'Mengubah Nama Mitra',
                'Nama mitra diubah dari "' .
                    $oldNamaMitra .
                    '" menjadi "' .
                    $beritaAcara->nama_mitra .
                    '"'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | LUAS TENANT
        |--------------------------------------------------------------------------
        */

        if (
            (float) $oldLuasTenant !=
            (float) $beritaAcara->luas_tenant
        ) {
            $this->createActivityLog(
                $beritaAcara,
                'Mengubah Luas Tenant',
                'Luas tenant diubah dari "' .
                    number_format(
                        (float) $oldLuasTenant,
                        2,
                        ',',
                        '.'
                    ) .
                    ' m²" menjadi "' .
                    number_format(
                        (float) $beritaAcara->luas_tenant,
                        2,
                        ',',
                        '.'
                    ) .
                    ' m²"'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | POSISI TENANT
        |--------------------------------------------------------------------------
        */

        if (
            $oldPosisiTenant !=
            $beritaAcara->posisi_tenant
        ) {
            $this->createActivityLog(
                $beritaAcara,
                'Mengubah Posisi Tenant',
                'Posisi tenant diubah dari "' .
                    $oldPosisiTenant .
                    '" menjadi "' .
                    $beritaAcara->posisi_tenant .
                    '"'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CHECKLIST
        |--------------------------------------------------------------------------
        */

        $newChecklist =
            $beritaAcara
                ->fresh()
                ->checklistItems()
                ->get()
                ->map(function ($item) {
                    return [
                        'id' =>
                            $item->id,

                        'nama_checklist' =>
                            $item->nama_checklist,

                        'is_checked' =>
                            $item->is_checked,
                    ];
                })
                ->values()
                ->toArray();

        if (
            $oldChecklist !=
            $newChecklist
        ) {
            $this->createActivityLog(
                $beritaAcara,
                'Mengubah Checklist',
                'Checklist Berita Acara diubah.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TANDA TANGAN
        |--------------------------------------------------------------------------
        */

        $newSignatures =
            $beritaAcara
                ->fresh()
                ->signatures()
                ->get()
                ->map(function ($signature) {
                    return [
                        'pihak' =>
                            $signature->pihak,

                        'nama' =>
                            $signature->nama,

                        'jabatan' =>
                            $signature->jabatan,

                        'signature_type' =>
                            $signature->signature_type,

                        'signature_file' =>
                            $signature->signature_file,
                    ];
                })
                ->values()
                ->toArray();

        if (
            $oldSignatures !=
            $newSignatures
        ) {
            $this->createActivityLog(
                $beritaAcara,
                'Mengubah Tanda Tangan',
                'Tanda tangan Berita Acara diubah.'
            );
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER ACTIVITY LOG
    |--------------------------------------------------------------------------
    |
    | Menyimpan activity ke:
    | 1. MySQL
    | 2. Google Sheets
    |
    */

    private function createActivityLog(
        BeritaAcara $beritaAcara,
        string $activity,
        string $description
    ): void {
        $activityLog = ActivityLog::create([
            'user_id' =>
                auth()->id(),

            'berita_acara_id' =>
                $beritaAcara->id,

            'activity' =>
                $activity,

            'description' =>
                $description,
        ]);

        /*
        |--------------------------------------------------------------------------
        | GOOGLE SHEETS - LOG AKTIVITAS
        |--------------------------------------------------------------------------
        */

        try {
            $userName =
                auth()->user()?->name ?? 'Unknown';

            $this->googleSheets->append(
                'Log Aktivitas',
                [
                    $activityLog->created_at
                        ->format('Y-m-d H:i:s'),

                    $userName,

                    $beritaAcara->nomor_ba,

                    $activity,

                    $description,
                ]
            );
        } catch (Throwable $e) {
            report($e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SYNC DATA BA KE GOOGLE SHEETS
    |--------------------------------------------------------------------------
    |
    | Fungsi ini digunakan untuk:
    | 1. BA baru
    | 2. BA yang diedit
    | 3. BA yang berubah status menjadi selesai
    |
    */

    private function syncDataBA(
        BeritaAcara $beritaAcara
    ): void {
        try {
            $beritaAcara->load('creator');

            $values = [
                $beritaAcara->id,

                $beritaAcara->nomor_ba,

                $beritaAcara->tanggal_surat
                    ? $beritaAcara->tanggal_surat
                        ->format('Y-m-d')
                    : '-',

                $beritaAcara->nama_mitra,

                $beritaAcara->luas_tenant,

                $beritaAcara->posisi_tenant,

                ucfirst(
                    $beritaAcara->status
                ),

                $beritaAcara->creator?->name
                    ?? 'Unknown',

                $beritaAcara->created_at
                    ? $beritaAcara->created_at
                        ->format('Y-m-d H:i:s')
                    : '-',

                $beritaAcara->updated_at
                    ? $beritaAcara->updated_at
                        ->format('Y-m-d H:i:s')
                    : '-',
            ];

            /*
            |--------------------------------------------------------------------------
            | CARI BARIS BERDASARKAN ID
            |--------------------------------------------------------------------------
            */

            $updated =
                $this->googleSheets->updateById(
                    'Data BA',
                    $beritaAcara->id,
                    $values
                );

            /*
            |--------------------------------------------------------------------------
            | JIKA BELUM ADA, TAMBAHKAN BARU
            |--------------------------------------------------------------------------
            |
            | Ini membuat sinkronisasi lebih aman.
            | Kalau data BA belum ada di Google Sheets,
            | sistem akan membuat baris baru.
            |
            */

            if (!$updated) {
                $this->googleSheets->append(
                    'Data BA',
                    $values
                );
            }
        } catch (Throwable $e) {
            /*
            |--------------------------------------------------------------------------
            | GOOGLE SHEETS ERROR TIDAK BOLEH
            | MEMBUAT PROSES BA GAGAL
            |--------------------------------------------------------------------------
            */

            report($e);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN TANDA TANGAN SAAT CREATE
    |--------------------------------------------------------------------------
    */

    private function saveSignature(
        Request $request,
        BeritaAcara $beritaAcara,
        string $pihak,
        string $namaField,
        string $jabatanField,
        string $dataField,
        string $uploadField
    ) {
        $nama =
            $request->input(
                $namaField
            );

        $jabatan =
            $request->input(
                $jabatanField
            );

        /*
        |--------------------------------------------------------------------------
        | UPLOAD PNG
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                $uploadField
            )
        ) {
            $file =
                $request->file(
                    $uploadField
                );

            $path =
                $file->store(
                    'signatures',
                    'public'
                );

            $beritaAcara
                ->signatures()
                ->create([
                    'pihak' =>
                        $pihak,

                    'nama' =>
                        $nama,

                    'jabatan' =>
                        $jabatan,

                    'signature_type' =>
                        'upload',

                    'signature_file' =>
                        $path,
                ]);

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | CANVAS
        |--------------------------------------------------------------------------
        */

        $signatureData =
            $request->input(
                $dataField
            );

        if (
            !empty($signatureData) &&
            str_starts_with(
                $signatureData,
                'data:image/png;base64,'
            )
        ) {
            $image =
                str_replace(
                    'data:image/png;base64,',
                    '',
                    $signatureData
                );

            $image =
                base64_decode(
                    $image
                );

            if ($image !== false) {
                $filename =
                    'signatures/' .
                    Str::uuid() .
                    '.png';

                Storage::disk('public')
                    ->put(
                        $filename,
                        $image
                    );

                $beritaAcara
                    ->signatures()
                    ->create([
                        'pihak' =>
                            $pihak,

                        'nama' =>
                            $nama,

                        'jabatan' =>
                            $jabatan,

                        'signature_type' =>
                            'digital',

                        'signature_file' =>
                            $filename,
                    ]);

                return;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | NAMA / JABATAN SAJA
        |--------------------------------------------------------------------------
        */

        if (
            !empty($nama) ||
            !empty($jabatan)
        ) {
            $beritaAcara
                ->signatures()
                ->create([
                    'pihak' =>
                        $pihak,

                    'nama' =>
                        $nama,

                    'jabatan' =>
                        $jabatan,

                    'signature_type' =>
                        'digital',

                    'signature_file' =>
                        null,
                ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE TANDA TANGAN SAAT EDIT
    |--------------------------------------------------------------------------
    */

    private function updateSignature(
        Request $request,
        BeritaAcara $beritaAcara,
        string $pihak,
        string $namaField,
        string $jabatanField,
        string $dataField,
        string $uploadField
    ) {
        $nama =
            $request->input(
                $namaField
            );

        $jabatan =
            $request->input(
                $jabatanField
            );

        /*
        |--------------------------------------------------------------------------
        | CARI SIGNATURE LAMA
        |--------------------------------------------------------------------------
        */

        $signature =
            $beritaAcara
                ->signatures()
                ->where(
                    'pihak',
                    $pihak
                )
                ->first();

        /*
        |--------------------------------------------------------------------------
        | UPLOAD SIGNATURE BARU
        |--------------------------------------------------------------------------
        */

        if (
            $request->hasFile(
                $uploadField
            )
        ) {
            $file =
                $request->file(
                    $uploadField
                );

            $path =
                $file->store(
                    'signatures',
                    'public'
                );

            if ($signature) {
                if (
                    $signature->signature_file &&
                    Storage::disk('public')
                        ->exists(
                            $signature->signature_file
                        )
                ) {
                    Storage::disk('public')
                        ->delete(
                            $signature->signature_file
                        );
                }

                $signature->update([
                    'nama' =>
                        $nama,

                    'jabatan' =>
                        $jabatan,

                    'signature_type' =>
                        'upload',

                    'signature_file' =>
                        $path,
                ]);
            } else {
                $beritaAcara
                    ->signatures()
                    ->create([
                        'pihak' =>
                            $pihak,

                        'nama' =>
                            $nama,

                        'jabatan' =>
                            $jabatan,

                        'signature_type' =>
                            'upload',

                        'signature_file' =>
                            $path,
                    ]);
            }

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | CANVAS SIGNATURE BARU
        |--------------------------------------------------------------------------
        */

        $signatureData =
            $request->input(
                $dataField
            );

        if (
            !empty($signatureData) &&
            str_starts_with(
                $signatureData,
                'data:image/png;base64,'
            )
        ) {
            $image =
                str_replace(
                    'data:image/png;base64,',
                    '',
                    $signatureData
                );

            $image =
                base64_decode(
                    $image
                );

            if ($image !== false) {
                $filename =
                    'signatures/' .
                    Str::uuid() .
                    '.png';

                Storage::disk('public')
                    ->put(
                        $filename,
                        $image
                    );

                if ($signature) {
                    if (
                        $signature->signature_file &&
                        Storage::disk('public')
                            ->exists(
                                $signature->signature_file
                            )
                    ) {
                        Storage::disk('public')
                            ->delete(
                                $signature->signature_file
                            );
                    }

                    $signature->update([
                        'nama' =>
                            $nama,

                        'jabatan' =>
                            $jabatan,

                        'signature_type' =>
                            'digital',

                        'signature_file' =>
                            $filename,
                    ]);
                } else {
                    $beritaAcara
                        ->signatures()
                        ->create([
                            'pihak' =>
                                $pihak,

                            'nama' =>
                                $nama,

                            'jabatan' =>
                                $jabatan,

                            'signature_type' =>
                                'digital',

                            'signature_file' =>
                                $filename,
                        ]);
                }

                return;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | TIDAK MENGGANTI GAMBAR TANDA TANGAN
        |--------------------------------------------------------------------------
        */

        if ($signature) {
            $signature->update([
                'nama' =>
                    $nama,

                'jabatan' =>
                    $jabatan,
            ]);
        } elseif (
            !empty($nama) ||
            !empty($jabatan)
        ) {
            $beritaAcara
                ->signatures()
                ->create([
                    'pihak' =>
                        $pihak,

                    'nama' =>
                        $nama,

                    'jabatan' =>
                        $jabatan,

                    'signature_type' =>
                        'digital',

                    'signature_file' =>
                        null,
                ]);
        }
    }
}
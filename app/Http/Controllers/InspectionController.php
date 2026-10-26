<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\InspectionActivityLog;
use App\Models\InspectionChecklistItem;
use App\Models\InspectionFinding;
use App\Models\InspectionPhoto;
use App\Models\InspectionSignature;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InspectionController extends Controller
{
    /**
     * Daftar inspeksi.
     */
    public function index()
    {
        $inspections = Inspection::latest()->paginate(10);

        return view('inspections.index', compact('inspections'));
    }

    /**
     * Form inspeksi baru.
     */
    public function create()
    {
        $checklist = config('inspection_checklist', []);

        return view('inspections.create', compact('checklist'));
    }

    /**
     * Simpan inspeksi baru beserta checklist.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'partner_name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'room_code' => ['required', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'inspection_type' => ['required', 'string', 'max:100'],
            'inspector_name' => ['required', 'string', 'max:255'],
            'inspector_position' => ['nullable', 'string', 'max:255'],
            'terminal' => ['required', 'in:International,Domestic'],
            'notes' => ['nullable', 'string'],
        ]);

        $inspectionDateTime = now();

        $inspection = DB::transaction(function () use (
            $validated,
            $request,
            $inspectionDateTime
        ) {
            $inspection = Inspection::create(array_merge(
                $validated,
                [
                    'inspection_number' => 'INS-'
                        . $inspectionDateTime->format('YmdHis')
                        . '-'
                        . strtoupper(Str::random(4)),
                    'inspection_date' => $inspectionDateTime->toDateString(),
                    'inspection_time' => $inspectionDateTime->format('H:i:s'),
                    'status' => 'draft',
                    'created_by' => $request->user()->id,
                ]
            ));

            foreach (config('inspection_checklist', []) as $section) {
                foreach ($section['items'] as $index => $item) {
                    InspectionChecklistItem::create([
                        'inspection_id' => $inspection->id,
                        'section_code' => $section['code'],
                        'section_name' => $section['name'],
                        'code' => $item['code'] ?? null,
                        'criteria' => $item['criteria'],
                        'status' => null,
                        'notes' => null,
                        'is_custom' => false,
                        'sort_order' => $index + 1,
                    ]);
                }
            }

            InspectionActivityLog::create([
                'inspection_id' => $inspection->id,
                'user_id' => $request->user()->id,
                'activity' => 'created',
                'description' => 'Membuat inspeksi gerai baru.',
            ]);

            return $inspection;
        });

        return redirect()
            ->route('inspections.index')
            ->with('success', 'Inspeksi berhasil dibuat.');
    }

    /**
     * Menampilkan detail inspeksi.
     */
    public function show(Inspection $inspection)
    {
        $inspection->load([
            'checklistItems',
            'findings',
            'signatures',
            'photos',
            'activityLogs.user',
        ]);

        $checklistItems = $inspection->checklistItems;
        $findings = $inspection->findings;
        $signatures = $inspection->signatures;
        $photos = $inspection->photos;

        return view('inspections.show', compact(
            'inspection',
            'checklistItems',
            'findings',
            'signatures',
            'photos'
        ));
    }

    /**
     * Form edit identitas inspeksi.
     */
    public function edit(Inspection $inspection)
    {
        $inspection->load([
            'checklistItems',
            'findings',
            'signatures',
            'photos',
        ]);

        $checklist = config('inspection_checklist', []);

        return view(
            'inspections.edit',
            compact('inspection', 'checklist')
        );
    }

    /**
     * Memperbarui identitas inspeksi.
     */
    public function update(Request $request, Inspection $inspection)
    {
        $validated = $request->validate([
            'partner_name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'room_code' => ['required', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'location' => ['required', 'string', 'max:255'],
            'inspection_type' => ['required', 'string', 'max:100'],
            'inspector_name' => ['required', 'string', 'max:255'],
            'inspector_position' => ['nullable', 'string', 'max:255'],
            'terminal' => ['required', 'in:International,Domestic'],
            'notes' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use (
            $validated,
            $request,
            $inspection
        ) {
            $inspection->update($validated);

            InspectionActivityLog::create([
                'inspection_id' => $inspection->id,
                'user_id' => $request->user()->id,
                'activity' => 'updated',
                'description' => 'Memperbarui data identitas inspeksi gerai.',
            ]);
        });

        return redirect()
            ->route('inspections.show', $inspection)
            ->with('success', 'Data inspeksi berhasil diperbarui.');
    }

    /**
     * Menyimpan status dan catatan checklist.
     */
    public function saveChecklist(Request $request, Inspection $inspection)
    {
        $validated = $request->validate([
            'checklist' => ['required', 'array'],
            'checklist.*.status' => [
                'nullable',
                'in:sesuai,tidak_sesuai,tidak_berlaku',
            ],
            'checklist.*.notes' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $request,
            $inspection
        ) {
            $updatedCount = 0;

            foreach ($validated['checklist'] as $itemId => $data) {
                $item = InspectionChecklistItem::where(
                    'inspection_id',
                    $inspection->id
                )->whereKey($itemId)->first();

                if (!$item) {
                    continue;
                }

                $item->update([
                    'status' => $data['status'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ]);

                $updatedCount++;
            }

            InspectionActivityLog::create([
                'inspection_id' => $inspection->id,
                'user_id' => $request->user()->id,
                'activity' => 'checklist_updated',
                'description' => "Menyimpan checklist inspeksi ({$updatedCount} kriteria diproses).",
            ]);
        });

        return redirect()
            ->route('inspections.show', $inspection)
            ->with('success', 'Checklist inspeksi berhasil disimpan.');
    }

    /**
     * Menambahkan kriteria checklist khusus.
     */
    public function addChecklistItem(
        Request $request,
        Inspection $inspection
    ) {
        $validated = $request->validate([
            'section_code' => ['required', 'string', 'max:10'],
            'code' => ['nullable', 'string', 'max:20'],
            'criteria' => ['required', 'string', 'max:2000'],
        ]);

        $sections = collect(config('inspection_checklist', []));
        $section = $sections->firstWhere(
            'code',
            $validated['section_code']
        );

        if (!$section) {
            return back()
                ->withInput()
                ->withErrors([
                    'section_code' => 'Bagian checklist tidak ditemukan.',
                ]);
        }

        DB::transaction(function () use (
            $validated,
            $request,
            $inspection,
            $section
        ) {
            $lastSortOrder = InspectionChecklistItem::where(
                'inspection_id',
                $inspection->id
            )
                ->where('section_code', $validated['section_code'])
                ->max('sort_order');

            InspectionChecklistItem::create([
                'inspection_id' => $inspection->id,
                'section_code' => $validated['section_code'],
                'section_name' => $section['name'],
                'code' => $validated['code'] ?: null,
                'criteria' => $validated['criteria'],
                'status' => null,
                'notes' => null,
                'is_custom' => true,
                'sort_order' => ($lastSortOrder ?? 0) + 1,
            ]);

            InspectionActivityLog::create([
                'inspection_id' => $inspection->id,
                'user_id' => $request->user()->id,
                'activity' => 'checklist_item_added',
                'description' => 'Menambahkan kriteria checklist pada bagian '
                    . $section['name'] . '.',
            ]);
        });

        return redirect()
            ->route('inspections.show', $inspection)
            ->with('success', 'Kriteria checklist berhasil ditambahkan.');
    }

    /**
     * Menambahkan temuan inspeksi.
     */
    public function storeFinding(
        Request $request,
        Inspection $inspection
    ) {
        $validated = $request->validate([
            'checklist_item_id' => [
                'nullable',
                'integer',
                'exists:inspection_checklist_items,id',
            ],
            'description' => ['required', 'string', 'max:5000'],
        ]);

        DB::transaction(function () use (
            $validated,
            $request,
            $inspection
        ) {
            $checklistItem = null;

            if (!empty($validated['checklist_item_id'])) {
                $checklistItem = InspectionChecklistItem::where(
                    'inspection_id',
                    $inspection->id
                )->findOrFail($validated['checklist_item_id']);
            }

            $lastSortOrder = InspectionFinding::where(
                'inspection_id',
                $inspection->id
            )->max('sort_order');

            InspectionFinding::create([
                'inspection_id' => $inspection->id,
                'checklist_item_id' => $checklistItem?->id,
                'code' => $checklistItem?->code,
                'criteria' => $checklistItem?->criteria,
                'description' => $validated['description'],
                'sort_order' => ($lastSortOrder ?? 0) + 1,
            ]);

            InspectionActivityLog::create([
                'inspection_id' => $inspection->id,
                'user_id' => $request->user()->id,
                'activity' => 'finding_added',
                'description' => 'Menambahkan temuan inspeksi.',
            ]);
        });

        return redirect()
            ->route('inspections.show', $inspection)
            ->with('success', 'Temuan inspeksi berhasil ditambahkan.');
    }

    /**
     * Mengunggah dokumentasi foto inspeksi.
     */
    public function storePhoto(
        Request $request,
        Inspection $inspection
    ) {
        $validated = $request->validate([
            'finding_id' => [
                'nullable',
                'integer',
                'exists:inspection_findings,id',
            ],
            'category' => [
                'required',
                'in:temuan,kondisi_awal,kondisi_akhir,lainnya',
            ],
            'photo' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'caption' => ['nullable', 'string', 'max:1000'],
        ]);

        // Pastikan temuan yang dipilih milik inspeksi ini.
        if (!empty($validated['finding_id'])) {
            $findingExists = InspectionFinding::where(
                'inspection_id',
                $inspection->id
            )
                ->whereKey($validated['finding_id'])
                ->exists();

            if (!$findingExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'finding_id' => 'Temuan tidak sesuai dengan inspeksi ini.',
                    ]);
            }
        }

        $path = $request->file('photo')->store(
            'inspections/' . $inspection->id,
            'public'
        );

        if (!$path) {
            return back()
                ->withInput()
                ->withErrors([
                    'photo' => 'Gagal menyimpan foto dokumentasi.',
                ]);
        }

        try {
            DB::transaction(function () use (
                $validated,
                $request,
                $inspection,
                $path
            ) {
                $lastSortOrder = InspectionPhoto::where(
                    'inspection_id',
                    $inspection->id
                )->max('sort_order');

                InspectionPhoto::create([
                    'inspection_id' => $inspection->id,
                    'finding_id' => $validated['finding_id'] ?? null,
                    'category' => $validated['category'],
                    'file_path' => $path,
                    'caption' => $validated['caption'] ?? null,
                    'sort_order' => ($lastSortOrder ?? 0) + 1,
                ]);

                InspectionActivityLog::create([
                    'inspection_id' => $inspection->id,
                    'user_id' => $request->user()->id,
                    'activity' => 'photo_uploaded',
                    'description' => 'Mengunggah foto dokumentasi inspeksi.',
                ]);
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($path);
            throw $e;
        }

        return redirect()
            ->route('inspections.show', $inspection)
            ->with('success', 'Foto dokumentasi berhasil diunggah.');
    }

    /**
     * Menyimpan atau memperbarui tanda tangan digital.
     *
     * Pihak Pertama:
     * - Perusahaan tetap PT ANGKASA PURA INDONESIA (PERSERO).
     * - Nama dan jabatan mengikuti data inspektor.
     *
     * Pihak Kedua:
     * - Perusahaan mengikuti nama mitra.
     * - Nama perwakilan dan jabatan diisi pengguna.
     *
     * Mendukung tanda tangan dari canvas maupun file upload.
     */
    public function saveSignature(
        Request $request,
        Inspection $inspection
    ) {
        $validated = $request->validate([
            'party' => [
                'required',
                'in:pihak_pertama,pihak_kedua',
            ],
            'signature_upload' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg',
                'max:2048',
            ],
            'signature_data' => [
                'nullable',
                'string',
                'max:1000000',
            ],
        ]);

        /*
         * Data penanda tangan ditentukan di server.
         * Jangan mengambil nama perusahaan dari input browser.
         */
        if ($validated['party'] === 'pihak_pertama') {
            $companyName = 'PT ANGKASA PURA INDONESIA (PERSERO)';
            $signerName = $inspection->inspector_name;
            $signerPosition = $inspection->inspector_position;

            if (blank($signerName)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'signature_data' => 'Nama inspektor belum tersedia. Lengkapi data inspeksi terlebih dahulu.',
                    ]);
            }
        } else {
            $partyData = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'position' => ['required', 'string', 'max:255'],
            ]);

            $companyName = $inspection->partner_name;
            $signerName = $partyData['name'];
            $signerPosition = $partyData['position'];

            if (blank($companyName)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'signature_data' => 'Nama mitra belum tersedia pada data inspeksi.',
                    ]);
            }
        }

        if (blank($companyName)) {
            return back()
                ->withInput()
                ->withErrors([
                    'signature_data' => 'Nama perusahaan tidak boleh kosong.',
                ]);
        }

        $path = null;

        /*
         * Upload gambar diprioritaskan jika tersedia.
         */
        if ($request->hasFile('signature_upload')) {
            $path = $request->file('signature_upload')->store(
                'inspections/' . $inspection->id . '/signatures',
                'public'
            );

            if (!$path) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'signature_upload' => 'Gagal menyimpan gambar tanda tangan.',
                    ]);
            }
        } elseif (!empty($validated['signature_data'])) {
            $data = $validated['signature_data'];

            // Hanya menerima data URL PNG dari canvas.
            if (!preg_match(
                '/^data:image\/png;base64,([A-Za-z0-9+\/=\r\n]+)$/',
                $data,
                $matches
            )) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'signature_data' => 'Format tanda tangan digital tidak valid.',
                    ]);
            }

            $binary = base64_decode($matches[1], true);

            // Validasi ukuran dan header file PNG.
            if (
                $binary === false ||
                strlen($binary) > 700000 ||
                !str_starts_with($binary, "\x89PNG\r\n\x1a\n")
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'signature_data' => 'Gambar tanda tangan tidak valid atau terlalu besar.',
                    ]);
            }

            $path = 'inspections/' . $inspection->id
                . '/signatures/' . Str::uuid() . '.png';

            $stored = Storage::disk('public')->put($path, $binary);

            if (!$stored) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'signature_data' => 'Gagal menyimpan gambar tanda tangan.',
                    ]);
            }
        }

        if (!$path) {
            return back()
                ->withInput()
                ->withErrors([
                    'signature_data' => 'Silakan gambar atau unggah tanda tangan terlebih dahulu.',
                ]);
        }

        $signature = InspectionSignature::where(
            'inspection_id',
            $inspection->id
        )
            ->where('party', $validated['party'])
            ->first();

        $oldPath = $signature?->signature_path;

        try {
            DB::transaction(function () use (
                $validated,
                $request,
                $inspection,
                $path,
                $signature,
                $companyName,
                $signerName,
                $signerPosition
            ) {
                $data = [
                    'company_name' => $companyName,
                    'name' => $signerName,
                    'position' => $signerPosition,
                    'signature_path' => $path,
                    'signed_at' => now(),
                ];

                if ($signature) {
                    $signature->update($data);
                } else {
                    InspectionSignature::create(array_merge(
                        $data,
                        [
                            'inspection_id' => $inspection->id,
                            'party' => $validated['party'],
                        ]
                    ));
                }

                InspectionActivityLog::create([
                    'inspection_id' => $inspection->id,
                    'user_id' => $request->user()->id,
                    'activity' => 'signature_saved',
                    'description' => 'Menyimpan tanda tangan '
                        . str_replace('_', ' ', $validated['party']) . '.',
                ]);
            });
        } catch (\Throwable $e) {
            Storage::disk('public')->delete($path);
            throw $e;
        }

        // Hapus file tanda tangan sebelumnya setelah penyimpanan sukses.
        if ($oldPath && $oldPath !== $path) {
            Storage::disk('public')->delete($oldPath);
        }

        return redirect()
            ->route('inspections.show', $inspection)
            ->with('success', 'Tanda tangan berhasil disimpan.');
    }

    /**
 * Download laporan hasil inspeksi dalam format PDF.
 */
public function downloadPdf(Inspection $inspection)
{
    $inspection->load([
        'checklistItems',
        'findings',
        'signatures',
        'photos',
    ]);

    $checklistItems = $inspection->checklistItems
        ->sortBy([
            ['section_code', 'asc'],
            ['sort_order', 'asc'],
        ])
        ->values();

    $findings = $inspection->findings
        ->sortBy('sort_order')
        ->values();

    $photos = $inspection->photos
        ->sortBy('sort_order')
        ->values();

    $signatures = $inspection->signatures
        ->keyBy('party');

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView(
        'inspections.pdf',
        compact(
            'inspection',
            'checklistItems',
            'findings',
            'photos',
            'signatures'
        )
    );

    $pdf->setPaper('a4', 'portrait');

    return $pdf->download(
        'Laporan-Inspeksi-' . $inspection->inspection_number . '.pdf'
    );
}
}

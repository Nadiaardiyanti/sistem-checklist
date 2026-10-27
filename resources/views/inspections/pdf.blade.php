
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Inspeksi Gerai</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 22mm 12mm 18mm 12mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 8px;
            color: #222;
            line-height: 1.35;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 0.5px solid #777;
            padding: 4px;
            vertical-align: top;
        }

        th {
            text-align: center;
            font-weight: bold;
            background: #e9edf2;
            vertical-align: middle;
        }

        .no-border td,
        .no-border th {
            border: none;
        }

        .header-table td {
            border: none;
            vertical-align: middle;
        }

        .logo {
            max-width: 100px;
            max-height: 48px;
        }

        .title-main {
            font-size: 15px;
            font-weight: bold;
            text-align: center;
            margin: 0;
        }

        .title-sub {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            margin-top: 5px;
        }

        .section-title {
            font-size: 10px;
            font-weight: bold;
            margin: 12px 0 5px;
        }

        .identity td {
            padding: 4px 5px;
        }

        .identity .label {
            font-weight: bold;
            width: 18%;
        }

        .identity .value {
            width: 32%;
        }

        .summary td {
            text-align: center;
            font-weight: bold;
            padding: 7px 3px;
        }

        .summary-label {
            font-size: 7px;
            color: #444;
        }

        .summary-value {
            font-size: 12px;
            margin-top: 3px;
        }

        .checklist {
            table-layout: fixed;
        }

        .checklist th,
        .checklist td {
            padding: 4px 3px;
            overflow-wrap: break-word;
        }

        /* Header tabel checklist: biru tua dan tulisan putih */
        .checklist thead th {
        background-color: #4472C4;
        color: #FFFFFF;
        font-weight: bold;
        text-align: center;
        vertical-align: middle;
        }

        .checklist .code {
            width: 8%;
            text-align: center;
        }

        .checklist .criteria {
            width: 43%;
        }

        .checklist .result {
            width: 9%;
            text-align: center;
            vertical-align: middle;
        }

        .checklist .notes {
            width: 22%;
        }

        .category-row td {
            background: #e8ebef;
            font-weight: bold;
        }

        /* Baris checklist sesuai: putih */
        .checklist tr.status-sesuai td {
        background-color: #ffffff;
        }

        /* Baris checklist tidak sesuai: merah muda */
        .checklist tr.status-tidak-sesuai td {
         background-color: #fcebea;
        }

        /* Baris checklist tidak berlaku: abu-abu */
        .checklist tr.status-tidak-berlaku td {
        background-color: #e0e0e0;
        }

        /* Judul kategori tetap biru */
        .checklist tr.category-row td {
        background-color: #b4c7e7;
        font-weight: bold;
        }

        .check {
            font-size: 12px;
            font-weight: bold;
        }

        .center {
            text-align: center;
        }

        .muted {
            color: #666;
        }

        .finding-table {
            table-layout: fixed;
        }

        .finding-table th,
        .finding-table td {
            padding: 5px;
        }

        .finding-number {
            width: 7%;
            text-align: center;
        }

        .finding-code {
            width: 10%;
            text-align: center;
        }

        .finding-criteria {
            width: 35%;
        }

        .finding-description {
            width: 48%;
        }

        .signature-table {
            margin-top: 8px;
            page-break-inside: avoid;
        }

        .signature-table th {
            background: #fff;
        }

        .signature-cell {
            width: 50%;
            height: 115px;
            text-align: center;
            vertical-align: top;
            padding: 8px;
        }

        .signature-image {
            max-width: 125px;
            max-height: 55px;
        }

        .signature-space {
            height: 62px;
            text-align: center;
            vertical-align: middle;
        }

        .page-break {
            page-break-before: always;
        }

        .photo-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 8px;
        }

        .photo {
            max-width: 220px;
            max-height: 180px;
        }

        .photo-caption {
            margin-top: 5px;
            text-align: left;
        }

        .footer {
            text-align: right;
            font-size: 7px;
            color: #666;
            margin-top: 10px;
        }

        .keep-together {
            page-break-inside: avoid;
        }
    </style>
</head>

<body>

@php
    /*
     * Ringkasan hasil checklist.
     */
    $totalSesuai = $checklistItems->where('status', 'sesuai')->count();

    $totalTidakSesuai = $checklistItems
        ->where('status', 'tidak_sesuai')
        ->count();

    $totalTidakBerlaku = $checklistItems
        ->where('status', 'tidak_berlaku')
        ->count();

    $totalBelumDiisi = $checklistItems
        ->filter(fn ($item) => !in_array(
            $item->status,
            ['sesuai', 'tidak_sesuai', 'tidak_berlaku'],
            true
        ))
        ->count();

    $totalDinilai = $totalSesuai + $totalTidakSesuai;

    $kepatuhan = $totalDinilai > 0
        ? round(($totalSesuai / $totalDinilai) * 100, 1)
        : 0;

    /*
     * Logo menggunakan path lokal agar dapat dibaca DomPDF.
     */
    $logoApi = public_path('images/logo-api.png');
    $logoInjourney = public_path('images/logo-injourney.png');

    /*
     * Urutan kategori inspeksi.
     */
    $urutanKategori = [
        'A', 'B', 'C', 'P', 'D', 'E', 'F', 'G', 'H', 'K'
    ];

    $groupedChecklist = $checklistItems
        ->groupBy('section_code')
        ->sortBy(function ($items, $code) use ($urutanKategori) {
            $posisi = array_search($code, $urutanKategori, true);

            return $posisi === false
                ? 999
                : $posisi;
        });

    $formatTanggal = function ($tanggal) {
        return $tanggal
            ? \Illuminate\Support\Carbon::parse($tanggal)->format('d/m/Y')
            : '-';
    };
@endphp

{{-- HEADER --}}
<table class="header-table">
    <tr>
        <td style="width: 20%; text-align: left;">
            @if (is_file($logoApi))
                <img src="{{ $logoApi }}" class="logo" alt="Logo API">
            @endif
        </td>

        <td style="width: 60%; text-align: center;">
            <div class="title-main">MONITORING OPERASIONAL GERAI</div>
            <div class="title-sub">FORM INSPEKSI GERAI</div>
        </td>

        <td style="width: 20%; text-align: right;">
            @if (is_file($logoInjourney))
                <img src="{{ $logoInjourney }}" class="logo" alt="Logo InJourney">
            @endif
        </td>
    </tr>
</table>

<hr style="border: 0; border-top: 1.5px solid #333; margin: 5px 0 10px;">

{{-- IDENTITAS --}}
<div class="section-title">IDENTITAS INSPEKSI</div>

<table class="identity">
    <tbody>
        <tr>
            <td class="label">Nama Mitra / PT</td>
            <td class="value">{{ $inspection->partner_name ?: '-' }}</td>
            <td class="label">Jenis Inspeksi</td>
            <td class="value">{{ $inspection->inspection_type ?: '-' }}</td>
        </tr>

        <tr>
            <td class="label">Brand</td>
            <td class="value">{{ $inspection->brand ?: '-' }}</td>
            <td class="label">Tanggal</td>
            <td class="value">{{ $formatTanggal($inspection->inspection_date) }}</td>
        </tr>

        <tr>
            <td class="label">Kode Ruangan</td>
            <td class="value">{{ $inspection->room_code ?: '-' }}</td>
            <td class="label">Waktu</td>
            <td class="value">
                {{ $inspection->inspection_time
                    ? substr((string) $inspection->inspection_time, 0, 5) . ' WIB'
                    : '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">Kategori</td>
            <td class="value">{{ $inspection->category ?: '-' }}</td>
            <td class="label">Inspeksi oleh</td>
            <td class="value">{{ $inspection->inspector_name ?: '-' }}</td>
        </tr>

        <tr>
            <td class="label">Lokasi</td>
            <td class="value">{{ $inspection->location ?: '-' }}</td>
            <td class="label">Terminal</td>
            <td class="value">{{ $inspection->terminal ?: '-' }}</td>
        </tr>

        <tr>
            <td class="label">Nomor Inspeksi</td>
            <td colspan="3">{{ $inspection->inspection_number ?: '-' }}</td>
        </tr>
    </tbody>
</table>

{{-- RINGKASAN --}}
<div class="section-title">RINGKASAN HASIL PEMERIKSAAN</div>

<table class="summary">
    <tr>
        <td>
            <div class="summary-label">SESUAI</div>
            <div class="summary-value">{{ $totalSesuai }}</div>
        </td>

        <td>
            <div class="summary-label">TIDAK SESUAI</div>
            <div class="summary-value">{{ $totalTidakSesuai }}</div>
        </td>

        <td>
            <div class="summary-label">TIDAK BERLAKU</div>
            <div class="summary-value">{{ $totalTidakBerlaku }}</div>
        </td>

        <td>
            <div class="summary-label">BELUM DIISI</div>
            <div class="summary-value">{{ $totalBelumDiisi }}</div>
        </td>

        <td>
            <div class="summary-label">KEPATUHAN</div>
            <div class="summary-value">{{ number_format($kepatuhan, 1, ',', '.') }}%</div>
        </td>
    </tr>
</table>

{{-- CHECKLIST --}}
<div class="section-title">PEMERIKSAAN CHECKLIST</div>

@if ($checklistItems->isNotEmpty())
    <table class="checklist">
        <thead>
            <tr>
                <th rowspan="2" class="code">Kode</th>
                <th rowspan="2" class="criteria">Kriteria</th>
                <th colspan="3">PEMERIKSAAN</th>
                <th rowspan="2" class="notes">Catatan</th>
            </tr>
            <tr>
                <th class="result">Sesuai</th>
                <th class="result">Tidak<br>Sesuai</th>
                <th class="result">Tidak<br>Berlaku</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($groupedChecklist as $sectionCode => $items)
                <tr class="category-row">
                    <td colspan="6">
                        {{ $sectionCode }}
                        &nbsp; {{ $items->first()->section_name ?: $sectionCode }}
                    </td>
                </tr>

                ```blade
            @foreach ($items as $item)
              <tr class="@if ($item->status === 'sesuai') status-sesuai @elseif ($item->status === 'tidak_sesuai') status-tidak-sesuai @elseif ($item->status === 'tidak_berlaku') status-tidak-berlaku @endif">
                <td class="center">{{ $item->code ?: '-' }}</td>
                <td>{{ $item->criteria }}</td>

                <td class="result">
                @if ($item->status === 'sesuai')
                <span class="check">✓</span>
            @endif
        </td>

        <td class="result">
            @if ($item->status === 'tidak_sesuai')
                <span class="check">✓</span>
            @endif
        </td>

        <td class="result">
            @if ($item->status === 'tidak_berlaku')
                <span class="check">✓</span>
            @endif
        </td>

        <td>{{ $item->notes ?: '-' }}</td>
         </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
@else
    <p>Belum ada data checklist.</p>
@endif

{{-- TEMUAN --}}
<div class="section-title">DAFTAR TEMUAN</div>

@if ($findings->isNotEmpty())
    <table class="finding-table">
        <thead>
            <tr>
                <th class="finding-number">No.</th>
                <th class="finding-code">Kode</th>
                <th class="finding-criteria">Kriteria</th>
                <th class="finding-description">Keterangan Temuan</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($findings as $index => $finding)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td class="center">{{ $finding->code ?: '-' }}</td>
                    <td>{{ $finding->criteria ?: '-' }}</td>
                    <td>{{ $finding->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <p>Tidak ada temuan yang tercatat.</p>
@endif

{{-- TANDA TANGAN --}}
<div class="section-title">PENGESAHAN</div>

<table class="signature-table">
    <thead>
        <tr>
            <th>PT ANGKASA PURA INDONESIA (PERSERO)</th>
            <th>{{ $inspection->partner_name ?: 'PIHAK MITRA' }}</th>
        </tr>
    </thead>

    <tbody>
        <tr>
            @foreach (['pihak_pertama', 'pihak_kedua'] as $party)
                @php
                    $signature = $signatures->get($party);
                @endphp

                <td class="signature-cell">
                    <div>
                        {{ $party === 'pihak_pertama'
                            ? 'Pihak Pertama'
                            : 'Pihak Kedua' }}
                    </div>

                    <div class="signature-space">
                        @if ($signature && $signature->signature_path)
                            @php
                                $signaturePath = \Illuminate\Support\Facades\Storage::disk('public')
                                    ->path($signature->signature_path);
                            @endphp

                            @if (is_file($signaturePath))
                                <img
                                    src="{{ $signaturePath }}"
                                    class="signature-image"
                                    alt="Tanda tangan"
                                >
                            @endif
                        @endif
                    </div>

                    <strong>
                        <u>
                            {{ $signature->name
                                ?? ($party === 'pihak_pertama'
                                    ? ($inspection->inspector_name ?: '-')
                                    : 'Belum ditandatangani') }}
                        </u>
                    </strong>

                    <br>

                    {{ $signature->position
                        ?? ($party === 'pihak_pertama'
                            ? ($inspection->inspector_position ?: '-')
                            : '-') }}

                    @if ($signature && $signature->signed_at)
                        <br>
                        <span class="muted">
                            {{ $formatTanggal($signature->signed_at) }}
                        </span>
                    @endif
                </td>
            @endforeach
        </tr>
    </tbody>
</table>

{{-- LAMPIRAN FOTO --}}
@if ($photos->isNotEmpty())
    <div class="page-break"></div>

    <table class="header-table">
        <tr>
            <td style="width: 20%;">
                @if (is_file($logoApi))
                    <img src="{{ $logoApi }}" class="logo" alt="Logo API">
                @endif
            </td>

            <td style="width: 60%; text-align: center;">
                <div class="title-main">LAMPIRAN DOKUMENTASI</div>
                <div style="text-align: center;">
                    {{ $inspection->partner_name ?: '-' }}
                    — {{ $inspection->room_code ?: '-' }}
                    — {{ $inspection->location ?: '-' }}
                </div>
            </td>

            <td style="width: 20%; text-align: right;">
                @if (is_file($logoInjourney))
                    <img src="{{ $logoInjourney }}" class="logo" alt="Logo InJourney">
                @endif
            </td>
        </tr>
    </table>

    <hr style="border: 0; border-top: 1px solid #333; margin: 8px 0 14px;">

    @foreach ($photos->chunk(2) as $photoGroup)
        <table class="photo-table" style="margin-bottom: 12px;">
            <tr>
                @foreach ($photoGroup as $photo)
                    <td>
                        <strong>
                            @if ($photo->finding_id)
                                @php
                                    $relatedFinding = $findings->firstWhere(
                                        'id',
                                        $photo->finding_id
                                    );
                                @endphp

                                @if ($relatedFinding && $relatedFinding->code)
                                    {{ $relatedFinding->code }} -
                                @endif
                            @endif

                            {{ ucfirst(str_replace('_', ' ', $photo->category)) }}
                        </strong>

                        <br><br>

                        @php
                            $photoPath = \Illuminate\Support\Facades\Storage::disk('public')
                                ->path($photo->file_path);
                        @endphp

                        @if ($photo->file_path && is_file($photoPath))
                            <img
                                src="{{ $photoPath }}"
                                class="photo"
                                alt="Foto dokumentasi inspeksi"
                            >
                        @else
                            <p class="muted">Foto tidak ditemukan.</p>
                        @endif

                        @if ($photo->caption)
                            <div class="photo-caption">
                                <strong>Keterangan:</strong>
                                {{ $photo->caption }}
                            </div>
                        @endif
                    </td>
                @endforeach

                @if ($photoGroup->count() === 1)
                    <td></td>
                @endif
            </tr>
        </table>
    @endforeach
@endif

<div class="footer">
    Dicetak pada {{ now()->format('d/m/Y H:i') }}
    &nbsp; | &nbsp;
    {{ $inspection->inspection_number ?: '-' }}
</div>

</body>
</html>

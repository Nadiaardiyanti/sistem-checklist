<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>Berita Acara {{ $beritaAcara->nomor_ba }}</title>

    <style>

        @page {
            size: A4;
            margin: 25mm 20mm 25mm 20mm;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #222;
            line-height: 1.5;
        }

        .text-center {
            text-align: center;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .subtitle {
            font-size: 12px;
            margin-bottom: 20px;
        }

        .line {
            border-bottom: 2px solid #222;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin-top: 18px;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 5px 3px;
            vertical-align: top;
        }

        .info-label {
            width: 35%;
            font-weight: bold;
        }

        .checklist-table {
            margin-top: 5px;
        }

        .checklist-table th,
        .checklist-table td {
            border: 1px solid #999;
            padding: 7px;
        }

        .checklist-table th {
            background: #f2f2f2;
            text-align: left;
        }

        .check {
            text-align: center;
            width: 15%;
        }

        .signature-container {
            width: 100%;
            margin-top: 35px;
        }

        .signature-box {
            width: 45%;
            display: inline-block;
            vertical-align: top;
            text-align: center;
        }

        .signature-box.right {
            margin-left: 9%;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .signature-image {
            width: 150px;
            height: 90px;
            object-fit: contain;
        }

        .signature-space {
            height: 90px;
        }

        .signature-name {
            margin-top: 8px;
            font-weight: bold;
        }

        .signature-jabatan {
            font-size: 10px;
        }

        .footer {
            margin-top: 35px;
            font-size: 9px;
            color: #666;
        }

    </style>
</head>

<body>

    {{-- HEADER --}}

    <div class="text-center">

        <div class="title">
            BERITA ACARA
        </div>

        <div class="subtitle">
            DIVISI KOMERSIAL
        </div>

    </div>

    <div class="line"></div>


    {{-- INFORMASI BA --}}

    <div class="section-title">
        DATA BERITA ACARA
    </div>

    <table class="info-table">

        <tr>
            <td class="info-label">
                Nomor Berita Acara
            </td>

            <td>
                : {{ $beritaAcara->nomor_ba }}
            </td>
        </tr>

        <tr>
            <td class="info-label">
                Tanggal Surat
            </td>

            <td>
                : {{ $beritaAcara->tanggal_surat->format('d/m/Y') }}
            </td>
        </tr>

        <tr>
            <td class="info-label">
                Nama Mitra
            </td>

            <td>
                : {{ $beritaAcara->nama_mitra }}
            </td>
        </tr>

        <tr>
            <td class="info-label">
                Luas Tenant
            </td>

            <td>
                : {{ number_format($beritaAcara->luas_tenant, 2, ',', '.') }} m²
            </td>
        </tr>

        <tr>
            <td class="info-label">
                Posisi Tenant
            </td>

            <td>
                : {{ $beritaAcara->posisi_tenant }}
            </td>
        </tr>

    </table>


    {{-- CHECKLIST --}}

    <div class="section-title">
        CHECKLIST DOKUMEN
    </div>

    <table class="checklist-table">

        <thead>
            <tr>
                <th>No.</th>
                <th>Dokumen</th>
                <th class="check">
                    Status
                </th>
            </tr>
        </thead>

        <tbody>

            @foreach ($beritaAcara->checklistItems as $index => $item)

                <tr>

                    <td>
                        {{ $index + 1 }}
                    </td>

                    <td>
                        {{ $item->nama_checklist }}
                    </td>

                    <td class="check">

                        @if ($item->is_checked)
                            ✓ Lengkap
                        @else
                            — Belum Lengkap
                        @endif

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>


    {{-- TANDA TANGAN --}}

    <div class="section-title">
        TANDA TANGAN
    </div>

    <div class="signature-container">

        @foreach (['Pihak Pertama', 'Pihak Kedua'] as $index => $pihak)

            @php
                $signature = $beritaAcara->signatures
                    ->where('pihak', $pihak)
                    ->first();
            @endphp

            <div class="signature-box {{ $index === 1 ? 'right' : '' }}">

                <div class="signature-title">
                    {{ $pihak }}
                </div>


                @if ($signature && $signature->signature_file)

                    @php
                        $signaturePath = storage_path(
                            'app/public/' . $signature->signature_file
                        );

                        $signatureImage = null;

                        if (file_exists($signaturePath)) {
                            $signatureImage =
                                'data:image/png;base64,' .
                                base64_encode(
                                    file_get_contents($signaturePath)
                                );
                        }
                    @endphp


                    @if ($signatureImage)

                        <img
                            src="{{ $signatureImage }}"
                            class="signature-image"
                        >

                    @else

                        <div class="signature-space"></div>

                    @endif

                @else

                    <div class="signature-space"></div>

                @endif


                @if ($signature)

                    <div class="signature-name">
                        {{ $signature->nama ?? '-' }}
                    </div>

                    <div class="signature-jabatan">
                        {{ $signature->jabatan ?? '-' }}
                    </div>

                @else

                    <div class="signature-name">
                        -
                    </div>

                @endif

            </div>

        @endforeach

    </div>


    {{-- FOOTER --}}

    <div class="footer">

        Dibuat oleh:
        {{ $beritaAcara->creator->name ?? '-' }}

        <br>

        Dibuat pada:
        {{ $beritaAcara->created_at->format('d/m/Y H:i') }}

    </div>

</body>

</html>
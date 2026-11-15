<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Eksekutif Inspeksi Gerai</title>
    <style>
        @page { size: A4 portrait; margin: 18mm 12mm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 9px; color: #222; line-height: 1.4; }
        h1 { font-size: 15px; margin: 0 0 2px; }
        h2 { font-size: 11px; margin: 16px 0 6px; border-bottom: 1px solid #17365D; padding-bottom: 3px; color: #17365D; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #cbd5e1; padding: 4px 5px; text-align: left; }
        th { background: #f1f5f9; }
        .r { text-align: right; }
        .muted { color: #64748b; }
    </style>
</head>
<body>
    <h1>Laporan Eksekutif Inspeksi Gerai</h1>
    <p class="muted">
        Dibuat: {{ $generated->format('d/m/Y H:i') }} WIB oleh {{ $user }}
        @if ($terminal) &middot; Terminal: {{ $terminal }} @endif
        @if ($brand) &middot; Brand: {{ $brand }} @endif
    </p>

    <p>Rata-rata skor kepatuhan:
        <strong>{{ $avg === null ? '-' : number_format((float) $avg, 1, ',', '.') . '%' }}</strong>
        (sesuai &divide; (sesuai + tidak sesuai) &times; 100)
    </p>

    <h2>Ringkasan per Terminal</h2>
    <table>
        <thead>
            <tr><th>Terminal</th><th class="r">Inspeksi</th><th class="r">Tidak Sesuai</th><th class="r">Temuan</th><th class="r">Skor</th></tr>
        </thead>
        <tbody>
            @forelse ($summary as $row)
                <tr>
                    <td>{{ $row->label }}</td>
                    <td class="r">{{ $row->total }}</td>
                    <td class="r">{{ (int) $row->not_ok }}</td>
                    <td class="r">{{ (int) $row->findings }}</td>
                    <td class="r">{{ $row->avg_score === null ? '-' : number_format((float) $row->avg_score, 1, ',', '.') . '%' }}</td>
                </tr>
            @empty
                <tr><td colspan="5">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>10 Kriteria Paling Sering Tidak Sesuai</h2>
    <table>
        <thead><tr><th style="width:40px">Kode</th><th>Kriteria</th><th class="r" style="width:50px">Jumlah</th></tr></thead>
        <tbody>
            @forelse ($topFailed as $item)
                <tr><td>{{ $item->code }}</td><td>{{ $item->criteria }}</td><td class="r">{{ $item->total }}</td></tr>
            @empty
                <tr><td colspan="3">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Daftar Inspeksi (maks. 200 terbaru)</h2>
    <table>
        <thead>
            <tr><th>No. Inspeksi</th><th>Tanggal</th><th>Terminal</th><th>Brand</th><th class="r">Tidak Sesuai</th><th class="r">Skor</th></tr>
        </thead>
        <tbody>
            @forelse ($facts as $f)
                <tr>
                    <td>{{ $f->inspection_number }}</td>
                    <td>{{ $f->inspection_date?->format('d/m/Y') }}</td>
                    <td>{{ $f->terminal }}</td>
                    <td>{{ $f->brand }}</td>
                    <td class="r">{{ $f->tidak_sesuai_count }}</td>
                    <td class="r">{{ $f->compliance_score === null ? '-' : number_format($f->compliance_score, 1, ',', '.') . '%' }}</td>
                </tr>
            @empty
                <tr><td colspan="6">Tidak ada data.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

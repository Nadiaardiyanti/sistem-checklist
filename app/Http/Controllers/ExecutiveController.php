<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\InspectionChecklistItem;
use App\Models\InspectionFact;
use App\Models\SecurityEvent;
use App\Services\EtlService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * SIE - Analisis Eksekutif: KPI, grafik, drill-down, what-if, ETL, laporan.
 */
class ExecutiveController extends Controller
{
    /** Dashboard + drill-down: Terminal -> Brand -> Inspeksi. */
    public function index(Request $request, EtlService $etl): View
    {
        // Pertama kali dibuka dan tabel fakta masih kosong: jalankan ETL otomatis.
        if (! InspectionFact::query()->exists() && Inspection::query()->exists()) {
            $etl->run();
        }

        [$terminal, $brand] = $this->filters($request);
        $base = $this->baseQuery($terminal, $brand);

        $kpi = [
            'inspections' => (clone $base)->count(),
            'avg_score'   => (clone $base)->avg('compliance_score'),
            'not_ok'      => (int) (clone $base)->sum('tidak_sesuai_count'),
            'findings'    => (int) (clone $base)->sum('findings_count'),
            'extracted'   => InspectionFact::max('extracted_at'),
        ];

        // Level drill-down saat ini.
        if (! $terminal) {
            $level = 'terminal';
            $column = 'terminal';
        } elseif (! $brand) {
            $level = 'brand';
            $column = 'brand';
        } else {
            $level = 'inspection';
            $column = null;
        }

        $rows = collect();
        $inspections = collect();

        if ($column) {
            $rows = (clone $base)
                ->selectRaw(
                    $column . ' as label, COUNT(*) as total, AVG(compliance_score) as avg_score, '
                    . 'SUM(tidak_sesuai_count) as not_ok, SUM(findings_count) as findings'
                )
                ->groupBy($column)
                ->orderBy('label')
                ->get();
        } else {
            $inspections = (clone $base)->orderByDesc('inspection_date')->orderByDesc('id')->limit(50)->get();
        }

        // Tren skor kepatuhan per bulan (12 bulan terakhir).
        $trend = (clone $base)
            ->selectRaw('period, AVG(compliance_score) as avg_score, COUNT(*) as total')
            ->groupBy('period')
            ->orderByDesc('period')
            ->limit(12)
            ->get()
            ->reverse()
            ->values();

        // Exception reporting: kriteria yang paling sering tidak sesuai.
        $topFailed = InspectionChecklistItem::query()
            ->where('status', 'tidak_sesuai')
            ->where('is_custom', false)
            ->whereIn('inspection_id', (clone $base)->select('inspection_id'))
            ->selectRaw('code, MIN(criteria) as criteria, COUNT(*) as total')
            ->groupBy('code')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('executive.index', compact(
            'terminal', 'brand', 'level', 'kpi', 'rows', 'inspections', 'trend', 'topFailed'
        ));
    }

    /** What-if analysis: proyeksi skor kepatuhan bila temuan diperbaiki. */
    public function whatIf(Request $request, EtlService $etl): View
    {
        if (! InspectionFact::query()->exists() && Inspection::query()->exists()) {
            $etl->run();
        }

        $input = $request->validate([
            'fix_percent' => ['nullable', 'integer', 'between:0,100'],
            'target'      => ['nullable', 'numeric', 'between:1,100'],
        ]);

        $fixPercent = (int) ($input['fix_percent'] ?? 50);
        $target = (float) ($input['target'] ?? 90);

        $perTerminal = InspectionFact::query()
            ->selectRaw('terminal as label, COUNT(*) as total, SUM(sesuai_count) as ok, SUM(tidak_sesuai_count) as not_ok')
            ->groupBy('terminal')
            ->orderBy('label')
            ->get();

        $scenarios = [0, 25, 50, 75, 100];

        $results = $perTerminal->map(function ($row) use ($fixPercent, $target, $scenarios) {
            $ok = (int) $row->ok;
            $notOk = (int) $row->not_ok;
            $assessed = $ok + $notOk;

            $project = fn (int $pct) => $assessed > 0
                ? round(($ok + $notOk * $pct / 100) / $assessed * 100, 1)
                : null;

            $needed = $assessed > 0 ? (int) ceil($target / 100 * $assessed - $ok) : 0;

            return [
                'label'     => $row->label,
                'total'     => (int) $row->total,
                'ok'        => $ok,
                'not_ok'    => $notOk,
                'assessed'  => $assessed,
                'current'   => $assessed > 0 ? round($ok / $assessed * 100, 1) : null,
                'projected' => $project($fixPercent),
                'needed'    => max(0, $needed),
                'reachable' => $needed <= $notOk,
                'scenarios' => collect($scenarios)->mapWithKeys(fn ($p) => [$p => $project($p)])->all(),
            ];
        });

        return view('executive.whatif', compact('fixPercent', 'target', 'results', 'scenarios'));
    }

    /** Jalankan ETL manual (hanya admin, dijaga di route). */
    public function runEtl(Request $request, EtlService $etl): RedirectResponse
    {
        $result = $etl->run();

        SecurityEvent::record(
            'etl_run',
            $request->user()->id,
            "ETL: {$result['extracted']} inspeksi diekstrak, {$result['loaded']} dimuat ({$result['duration_ms']} ms).",
            $request
        );

        return back()->with(
            'success',
            "ETL selesai: {$result['loaded']} inspeksi dimuat ke tabel fakta ({$result['duration_ms']} ms)."
        );
    }

    /** Laporan CSV. */
    public function exportCsv(Request $request, EtlService $etl): StreamedResponse
    {
        $this->ensureFacts($etl);

        [$terminal, $brand] = $this->filters($request);
        $facts = $this->baseQuery($terminal, $brand)->orderByDesc('inspection_date')->get();

        $filename = 'laporan-eksekutif-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($facts) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM agar Excel membaca UTF-8

            fputcsv($out, [
                'No. Inspeksi', 'Tanggal', 'Terminal', 'Brand', 'Mitra',
                'Sesuai', 'Tidak Sesuai', 'Tidak Berlaku', 'Belum Dinilai',
                'Temuan', 'Skor Kepatuhan (%)', 'Status',
            ]);

            foreach ($facts as $f) {
                fputcsv($out, array_map([$this, 'safeCsv'], [
                    $f->inspection_number,
                    $f->inspection_date?->format('Y-m-d'),
                    $f->terminal,
                    $f->brand,
                    $f->partner_name,
                    $f->sesuai_count,
                    $f->tidak_sesuai_count,
                    $f->tidak_berlaku_count,
                    $f->belum_dinilai_count,
                    $f->findings_count,
                    $f->compliance_score,
                    $f->status,
                ]));
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    /** Laporan PDF. */
    public function exportPdf(Request $request, EtlService $etl)
    {
        $this->ensureFacts($etl);

        [$terminal, $brand] = $this->filters($request);
        $base = $this->baseQuery($terminal, $brand);

        $summary = (clone $base)
            ->selectRaw('terminal as label, COUNT(*) as total, AVG(compliance_score) as avg_score, SUM(tidak_sesuai_count) as not_ok, SUM(findings_count) as findings')
            ->groupBy('terminal')
            ->orderBy('label')
            ->get();

        $facts = (clone $base)->orderByDesc('inspection_date')->limit(200)->get();

        $topFailed = InspectionChecklistItem::query()
            ->where('status', 'tidak_sesuai')
            ->where('is_custom', false)
            ->whereIn('inspection_id', (clone $base)->select('inspection_id'))
            ->selectRaw('code, MIN(criteria) as criteria, COUNT(*) as total')
            ->groupBy('code')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $avg = (clone $base)->avg('compliance_score');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('executive.report-pdf', [
            'terminal'  => $terminal,
            'brand'     => $brand,
            'summary'   => $summary,
            'facts'     => $facts,
            'topFailed' => $topFailed,
            'avg'       => $avg,
            'generated' => now(),
            'user'      => $request->user()->name,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-eksekutif-' . now()->format('Ymd-His') . '.pdf');
    }

    // ------------------------------------------------------------------

    /** Jika tabel fakta masih kosong padahal ada inspeksi, jalankan ETL dulu. */
    private function ensureFacts(EtlService $etl): void
    {
        if (! InspectionFact::query()->exists() && Inspection::query()->exists()) {
            $etl->run();
        }
    }

    /** @return array{0:?string,1:?string} */
    private function filters(Request $request): array
    {
        $data = $request->validate([
            'terminal' => ['nullable', 'string', 'max:30'],
            'brand'    => ['nullable', 'string', 'max:255'],
        ]);

        $terminal = $data['terminal'] ?? null;
        $brand = $data['brand'] ?? null;

        // Brand hanya bermakna jika terminal sudah dipilih.
        if (! $terminal) {
            $brand = null;
        }

        return [$terminal ?: null, $brand ?: null];
    }

    private function baseQuery(?string $terminal, ?string $brand): Builder
    {
        return InspectionFact::query()
            ->when($terminal, fn (Builder $q) => $q->where('terminal', $terminal))
            ->when($brand, fn (Builder $q) => $q->where('brand', $brand));
    }

    /** Cegah CSV injection (sel diawali = + - @ dibaca sebagai rumus). */
    private function safeCsv(mixed $value): mixed
    {
        if (is_string($value) && preg_match('/^[=+\-@\t\r]/', $value)) {
            return "'" . $value;
        }

        return $value;
    }
}

<?php

namespace App\Services;

use App\Models\Inspection;
use App\Models\InspectionFact;
use Illuminate\Support\Str;

/**
 * SIE - ETL (Extract, Transform, Load).
 *
 *  Extract   : baca data operasional (inspections, checklist, findings).
 *  Transform : bersihkan nama brand/terminal, hitung skor kepatuhan, tentukan periode.
 *  Load      : upsert ke tabel fakta inspection_facts (data mart untuk Analisis Eksekutif).
 */
class EtlService
{
    /** @return array{extracted:int, loaded:int, duration_ms:int} */
    public function run(): array
    {
        $startedAt = microtime(true);
        $now = now();
        $extracted = 0;
        $loaded = 0;

        // EXTRACT: per potongan agar hemat memori.
        Inspection::query()
            ->with('checklistItems:id,inspection_id,status')
            ->withCount('findings')
            ->orderBy('id')
            ->chunkById(200, function ($inspections) use (&$extracted, &$loaded, $now) {
                $rows = [];

                foreach ($inspections as $inspection) {
                    $extracted++;
                    $rows[] = $this->transform($inspection, $now);
                }

                // LOAD
                if ($rows !== []) {
                    InspectionFact::upsert(
                        $rows,
                        ['inspection_id'],
                        [
                            'inspection_number', 'terminal', 'brand', 'partner_name',
                            'inspection_date', 'period', 'total_items', 'sesuai_count',
                            'tidak_sesuai_count', 'tidak_berlaku_count', 'belum_dinilai_count',
                            'findings_count', 'compliance_score', 'status',
                            'extracted_at', 'updated_at',
                        ]
                    );

                    $loaded += count($rows);
                }
            });

        return [
            'extracted'   => $extracted,
            'loaded'      => $loaded,
            'duration_ms' => (int) round((microtime(true) - $startedAt) * 1000),
        ];
    }

    /** TRANSFORM: satu inspeksi -> satu baris fakta. */
    private function transform(Inspection $inspection, $now): array
    {
        $items = $inspection->checklistItems;

        $sesuai = $items->where('status', 'sesuai')->count();
        $tidakSesuai = $items->where('status', 'tidak_sesuai')->count();
        $tidakBerlaku = $items->where('status', 'tidak_berlaku')->count();
        $total = $items->count();
        $belumDinilai = $total - $sesuai - $tidakSesuai - $tidakBerlaku;

        $dinilai = $sesuai + $tidakSesuai;
        $score = $dinilai > 0 ? round($sesuai / $dinilai * 100, 2) : null;

        $date = $inspection->inspection_date ?? $inspection->created_at ?? $now;

        return [
            'inspection_id'       => $inspection->id,
            'inspection_number'   => $inspection->inspection_number,
            'terminal'            => $this->clean($inspection->terminal) ?: 'Tidak diketahui',
            'brand'               => $this->clean($inspection->brand) ?: 'Tidak diketahui',
            'partner_name'        => $this->clean($inspection->partner_name),
            'inspection_date'     => $date->toDateString(),
            'period'              => $date->format('Y-m'),
            'total_items'         => $total,
            'sesuai_count'        => $sesuai,
            'tidak_sesuai_count'  => $tidakSesuai,
            'tidak_berlaku_count' => $tidakBerlaku,
            'belum_dinilai_count' => $belumDinilai,
            'findings_count'      => (int) $inspection->findings_count,
            'compliance_score'    => $score,
            'status'              => $inspection->status ?? 'draft',
            'extracted_at'        => $now,
            'created_at'          => $now,
            'updated_at'          => $now,
        ];
    }

    private function clean(?string $value): string
    {
        return Str::of((string) $value)->squish()->title()->toString();
    }
}

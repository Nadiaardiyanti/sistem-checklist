<?php

namespace App\Console\Commands;

use App\Services\EtlService;
use Illuminate\Console\Command;

class EtlInspectionFacts extends Command
{
    protected $signature = 'etl:inspection-facts';

    protected $description = 'Jalankan ETL: ekstrak data inspeksi, transformasi, lalu muat ke tabel fakta untuk Analisis Eksekutif.';

    public function handle(EtlService $etl): int
    {
        $this->info('Menjalankan ETL data inspeksi...');

        $result = $etl->run();

        $this->table(
            ['Diekstrak', 'Dimuat', 'Durasi (ms)'],
            [[$result['extracted'], $result['loaded'], $result['duration_ms']]]
        );

        return self::SUCCESS;
    }
}

<?php

namespace App\Console\Commands;

use App\Models\InspectionActivityLog;
use App\Services\GoogleSheetsService;
use Illuminate\Console\Command;

class SyncActivityLogsToSheet extends Command
{
    protected $signature = 'sheets:sync-activity-logs';

    protected $description = 'Kirim log aktivitas yang belum ada di Google Sheets (aman dijalankan berulang kali).';

    public function handle(GoogleSheetsService $sheets): int
    {
        if (! $sheets->isConfigured()) {
            $this->error('Google Sheets belum dikonfigurasi. Periksa credentials.json dan GOOGLE_SHEETS_SPREADSHEET_ID.');

            return self::FAILURE;
        }

        $sheet = InspectionActivityLog::sheetName();

        try {
            $existing = $sheets->readColumn($sheet);

            // Sheet masih kosong: tulis baris judul kolom lebih dulu.
            if ($existing === []) {
                $sheets->appendMany($sheet, [InspectionActivityLog::sheetHeader()]);
            }

            $known = array_flip($existing);
            $sent = 0;

            InspectionActivityLog::with('user', 'inspection')
                ->orderBy('id')
                ->chunkById(200, function ($logs) use ($sheets, $sheet, $known, &$sent) {
                    $rows = [];

                    foreach ($logs as $log) {
                        if (! isset($known[(string) $log->id])) {
                            $rows[] = $log->toSheetRow();
                        }
                    }

                    $sheets->appendMany($sheet, $rows);
                    $sent += count($rows);
                });
        } catch (\Throwable $e) {
            $this->error('Gagal: ' . $e->getMessage());
            $this->line("Pastikan tab \"{$sheet}\" sudah dibuat di spreadsheet.");

            return self::FAILURE;
        }

        $this->info("Selesai. {$sent} log aktivitas dikirim ke tab \"{$sheet}\".");

        return self::SUCCESS;
    }
}
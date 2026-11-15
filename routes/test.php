<?php

use App\Models\InspectionActivityLog;
use App\Services\GoogleSheetsService;
use Illuminate\Support\Facades\Route;

// KSI: route uji koneksi hanya untuk admin yang sedang login.
// (Di bootstrap/app.php file ini juga hanya dimuat saat APP_ENV=local.)
// Hanya MEMBACA sheet log aktivitas, tidak menulis data apa pun.
Route::middleware(['auth', 'role:admin'])->get('/test-google-sheets', function (GoogleSheetsService $googleSheets) {

    if (! $googleSheets->isConfigured()) {
        return 'Google Sheets belum dikonfigurasi.';
    }

    $rows = $googleSheets->readColumn(InspectionActivityLog::sheetName());

    return 'Google Sheets berhasil terhubung! Sheet "' . InspectionActivityLog::sheetName() . '" berisi ' . count($rows) . ' baris.';
});

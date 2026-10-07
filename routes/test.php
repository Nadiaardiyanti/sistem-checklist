<?php

use App\Services\GoogleSheetsService;
use Illuminate\Support\Facades\Route;

Route::get('/test-google-sheets', function (GoogleSheetsService $googleSheets) {

    $googleSheets->append('Data BA', [
        'TEST',
        'TEST/001',
        now()->format('Y-m-d'),
        'Test Koneksi',
        '10',
        'Test',
        'Draft',
        'System',
        now()->format('Y-m-d H:i:s'),
        now()->format('Y-m-d H:i:s'),
    ]);

    return 'Google Sheets berhasil terhubung!';
});
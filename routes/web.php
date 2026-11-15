<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InspectionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ExecutiveController;
use App\Http\Controllers\SecurityController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // =========================
    // PROFILE
    // =========================

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // =========================
    // INSPEKSI GERAI
    // =========================

    // Daftar inspeksi
    Route::get('/inspections', [InspectionController::class, 'index'])
        ->name('inspections.index');

    // Form inspeksi baru
    Route::get('/inspections/create', [InspectionController::class, 'create'])
        ->name('inspections.create');

    // Simpan inspeksi baru
    Route::post('/inspections', [InspectionController::class, 'store'])
        ->name('inspections.store');

    // Simpan checklist inspeksi
    Route::put('/inspections/{inspection}/checklist', [InspectionController::class, 'saveChecklist'])
        ->middleware('can:update,inspection')
        ->name('inspections.checklist.save');

    // Tambah kriteria checklist khusus
    Route::post('/inspections/{inspection}/checklist-items', [InspectionController::class, 'addChecklistItem'])
        ->middleware('can:update,inspection')
        ->name('inspections.checklist-items.store');

    // Form edit identitas inspeksi
    Route::get('/inspections/{inspection}/edit', [InspectionController::class, 'edit'])
        ->middleware('can:update,inspection')
        ->name('inspections.edit');

    // Update identitas inspeksi
    Route::put('/inspections/{inspection}', [InspectionController::class, 'update'])
        ->middleware('can:update,inspection')
        ->name('inspections.update');

    // Simpan temuan inspeksi
    Route::post('/inspections/{inspection}/findings', [InspectionController::class, 'storeFinding'])
        ->middleware('can:update,inspection')
        ->name('inspections.findings.store');

    // Simpan foto inspeksi
    Route::post('/inspections/{inspection}/photos', [InspectionController::class, 'storePhoto'])
        ->middleware(['can:update,inspection', 'throttle:30,1'])
        ->name('inspections.photos.store');

    // Simpan tanda tangan digital Pihak Pertama / Pihak Kedua
    Route::post('/inspections/{inspection}/signatures', [InspectionController::class, 'saveSignature'])
        ->middleware(['can:update,inspection', 'throttle:30,1'])
        ->name('inspections.signatures.save');

    // Detail inspeksi (letakkan setelah route spesifik)
    Route::get('/inspections/{inspection}', [InspectionController::class, 'show'])
        ->name('inspections.show');

    Route::get('/inspections/{inspection}/pdf', [InspectionController::class, 'downloadPdf'])
        ->name('inspections.pdf');


    // =========================
    // LOG AKTIVITAS
    // =========================
    
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])
        ->name('activity-logs.index');



    // =========================
    // SIE - ANALISIS EKSEKUTIF
    // =========================

    // KPI + grafik + drill-down (Terminal -> Brand -> Inspeksi)
    Route::get('/executive', [ExecutiveController::class, 'index'])
        ->name('executive.index');

    // What-if analysis
    Route::get('/executive/what-if', [ExecutiveController::class, 'whatIf'])
        ->name('executive.whatif');

    // Laporan CSV / PDF dari data hasil ETL
    Route::get('/executive/export/csv', [ExecutiveController::class, 'exportCsv'])
        ->middleware('throttle:20,1')
        ->name('executive.export.csv');

    Route::get('/executive/export/pdf', [ExecutiveController::class, 'exportPdf'])
        ->middleware('throttle:20,1')
        ->name('executive.export.pdf');

    // Jalankan ETL manual (admin)
    Route::post('/executive/etl', [ExecutiveController::class, 'runEtl'])
        ->middleware(['role:admin', 'throttle:6,1'])
        ->name('executive.etl');


    // =========================
    // KSI - KEAMANAN SISTEM
    // =========================

    // Audit keamanan & manajemen peran (admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/security/audit', [SecurityController::class, 'audit'])
            ->name('security.audit');

        Route::post('/security/users', [SecurityController::class, 'storeUser'])
            ->middleware('throttle:20,1')
            ->name('security.users.store');

        Route::patch('/security/users/{user}/role', [SecurityController::class, 'updateRole'])
            ->name('security.users.role');
    });

});

require __DIR__.'/auth.php';
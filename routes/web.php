<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BeritaAcaraController;
use App\Http\Controllers\InspectionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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
    // BERITA ACARA
    // =========================

    // History BA
    Route::get('/berita-acara', [BeritaAcaraController::class, 'index'])
        ->name('berita-acara.index');

    // Buat BA
    Route::get('/berita-acara/create', [BeritaAcaraController::class, 'create'])
        ->name('berita-acara.create');

    // Simpan BA
    Route::post('/berita-acara', [BeritaAcaraController::class, 'store'])
        ->name('berita-acara.store');

    // Edit BA
    Route::get('/berita-acara/{beritaAcara}/edit', [BeritaAcaraController::class, 'edit'])
        ->name('berita-acara.edit');

    // Update BA
    Route::put('/berita-acara/{beritaAcara}', [BeritaAcaraController::class, 'update'])
        ->name('berita-acara.update');

    // Download PDF
    Route::get('/berita-acara/{beritaAcara}/pdf', [BeritaAcaraController::class, 'pdf'])
        ->name('berita-acara.pdf');

    // Riwayat Perubahan BA
    Route::get('/berita-acara/{beritaAcara}/versions', [BeritaAcaraController::class, 'versions'])
        ->name('berita-acara.versions');

    // Tandai BA Selesai
    Route::patch('/berita-acara/{beritaAcara}/selesai', [BeritaAcaraController::class, 'markAsCompleted'])
        ->name('berita-acara.complete');

    // Detail / Preview BA
    Route::get('/berita-acara/{beritaAcara}', [BeritaAcaraController::class, 'show'])
        ->name('berita-acara.show');


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
        ->name('inspections.checklist.save');

    // Tambah kriteria checklist khusus
    Route::post('/inspections/{inspection}/checklist-items', [InspectionController::class, 'addChecklistItem'])
        ->name('inspections.checklist-items.store');

    // Form edit identitas inspeksi
    Route::get('/inspections/{inspection}/edit', [InspectionController::class, 'edit'])
        ->name('inspections.edit');

    // Update identitas inspeksi
    Route::put('/inspections/{inspection}', [InspectionController::class, 'update'])
        ->name('inspections.update');

    // Simpan temuan inspeksi
    Route::post('/inspections/{inspection}/findings', [InspectionController::class, 'storeFinding'])
        ->name('inspections.findings.store');

    // Simpan foto inspeksi
    Route::post('/inspections/{inspection}/photos', [InspectionController::class, 'storePhoto'])
        ->name('inspections.photos.store');

    // Simpan tanda tangan digital Pihak Pertama / Pihak Kedua
    Route::post('/inspections/{inspection}/signatures', [InspectionController::class, 'saveSignature'])
        ->name('inspections.signatures.save');

    // Detail inspeksi (letakkan setelah route spesifik)
    Route::get('/inspections/{inspection}', [InspectionController::class, 'show'])
        ->name('inspections.show');

    Route::get('/inspections/{inspection}/pdf', [InspectionController::class, 'downloadPdf'])
        ->name('inspections.pdf');


    // =========================
    // LOG AKTIVITAS
    // =========================

    Route::get('/activity-logs', [BeritaAcaraController::class, 'activityLogs'])
        ->name('activity-logs.index');

});

require __DIR__.'/auth.php';
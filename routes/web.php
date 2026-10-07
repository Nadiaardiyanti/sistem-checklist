<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BeritaAcaraController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile
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

    // Log Aktivitas
    Route::get('/activity-logs', [BeritaAcaraController::class, 'activityLogs'])
        ->name('activity-logs.index');

});

require __DIR__.'/auth.php';
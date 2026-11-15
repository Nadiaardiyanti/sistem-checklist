<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Modul Berita Acara dihapus. Tabel lama dibuang (urutan: anak dulu, baru induk).
 * PERHATIAN: data di tabel-tabel ini ikut hilang. Backup dulu jika masih perlu.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('berita_acara_versions');
        Schema::dropIfExists('signatures');
        Schema::dropIfExists('checklist_items');
        Schema::dropIfExists('berita_acaras');
    }

    public function down(): void
    {
        // Sengaja kosong: modul Berita Acara tidak dipulihkan.
    }
};

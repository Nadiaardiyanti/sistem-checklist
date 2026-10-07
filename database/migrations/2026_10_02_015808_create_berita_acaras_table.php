<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up(): void
{
    Schema::create('berita_acaras', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_ba')->unique();
        $table->date('tanggal_surat');
        $table->string('nama_mitra');
        $table->decimal('luas_tenant', 10, 2);
        $table->string('posisi_tenant');
        $table->string('status')->default('draft');
        $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita_acaras');
    }
};

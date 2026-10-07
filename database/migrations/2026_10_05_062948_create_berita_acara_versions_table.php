<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berita_acara_versions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('berita_acara_id')
                ->constrained('berita_acaras')
                ->cascadeOnDelete();

            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('nomor_ba');
            $table->date('tanggal_surat');
            $table->string('nama_mitra');
            $table->decimal('luas_tenant', 10, 2);
            $table->string('posisi_tenant');

            $table->text('checklist_data')->nullable();
            $table->text('signature_data')->nullable();

            $table->string('action')->default('update');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berita_acara_versions');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();

            $table->foreignId('berita_acara_id')
                ->constrained('berita_acaras')
                ->cascadeOnDelete();

            $table->string('pihak');
            $table->string('nama')->nullable();
            $table->string('jabatan')->nullable();

            $table->enum('signature_type', ['upload', 'digital']);

            $table->string('signature_file')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};
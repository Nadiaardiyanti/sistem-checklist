<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('checklist_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('berita_acara_id')
                ->constrained('berita_acaras')
                ->cascadeOnDelete();

            $table->string('nama_checklist');

            $table->boolean('is_checked')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('checklist_items');
    }
};
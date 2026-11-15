<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * SIE - Tabel fakta (data mart) hasil proses ETL dari data inspeksi.
 * Kolom "period" (YYYY-MM) dipakai untuk tren; sengaja bukan "year_month"
 * karena itu kata cadangan di MySQL.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inspection_facts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspection_id')
                ->unique()
                ->constrained('inspections')
                ->cascadeOnDelete();

            $table->string('inspection_number')->nullable();
            $table->string('terminal', 30)->nullable();
            $table->string('brand')->nullable();
            $table->string('partner_name')->nullable();
            $table->date('inspection_date');
            $table->string('period', 7);

            $table->unsignedInteger('total_items')->default(0);
            $table->unsignedInteger('sesuai_count')->default(0);
            $table->unsignedInteger('tidak_sesuai_count')->default(0);
            $table->unsignedInteger('tidak_berlaku_count')->default(0);
            $table->unsignedInteger('belum_dinilai_count')->default(0);
            $table->unsignedInteger('findings_count')->default(0);

            // Skor kepatuhan = sesuai / (sesuai + tidak sesuai) x 100
            $table->decimal('compliance_score', 5, 2)->nullable();

            $table->string('status', 20)->default('draft');
            $table->timestamp('extracted_at')->nullable();
            $table->timestamps();

            $table->index('period');
            $table->index(['terminal', 'brand']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_facts');
    }
};

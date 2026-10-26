
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Data utama inspeksi gerai
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();

            $table->string('inspection_number')->nullable()->unique();
            $table->string('partner_name');
            $table->string('brand');
            $table->string('room_code')->nullable();
            $table->string('category')->nullable();
            $table->string('location');
            $table->string('inspection_type')->nullable();

            $table->date('inspection_date');
            $table->time('inspection_time')->nullable();

            $table->string('inspector_name');
            $table->string('inspector_position')->nullable();
            $table->string('terminal')->nullable();

            $table->string('status')->default('draft');
            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('inspection_date');
            $table->index('status');
        });

        // Checklist untuk setiap bagian inspeksi
        Schema::create('inspection_checklist_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspection_id')
                ->constrained('inspections')
                ->cascadeOnDelete();

            $table->string('section_code', 10);
            $table->string('section_name');
            $table->string('code', 20)->nullable();
            $table->text('criteria');
            $table->string('status')->nullable();
            $table->text('notes')->nullable();

            $table->boolean('is_custom')->default(false);
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['inspection_id', 'section_code']);
        });

        // Daftar temuan inspeksi
        Schema::create('inspection_findings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspection_id')
                ->constrained('inspections')
                ->cascadeOnDelete();

            $table->foreignId('checklist_item_id')
                ->nullable()
                ->constrained('inspection_checklist_items')
                ->nullOnDelete();

            $table->string('code', 20)->nullable();
            $table->text('criteria')->nullable();
            $table->text('description');

            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index('inspection_id');
        });

        // Data tanda tangan pemeriksa dan perwakilan mitra
        Schema::create('inspection_signatures', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspection_id')
                ->constrained('inspections')
                ->cascadeOnDelete();

            $table->string('party', 20);
            $table->string('company_name')->nullable();
            $table->string('name')->nullable();
            $table->string('position')->nullable();
            $table->string('signature_path')->nullable();
            $table->timestamp('signed_at')->nullable();

            $table->timestamps();

            $table->unique(['inspection_id', 'party']);
        });

        // Foto dokumentasi dan foto temuan
        Schema::create('inspection_photos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspection_id')
                ->constrained('inspections')
                ->cascadeOnDelete();

            $table->foreignId('finding_id')
                ->nullable()
                ->constrained('inspection_findings')
                ->nullOnDelete();

            $table->string('category', 30);
            $table->string('file_path');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();

            $table->index(['inspection_id', 'category']);
        });

        // Riwayat aktivitas inspeksi
        Schema::create('inspection_activity_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inspection_id')
                ->nullable()
                ->constrained('inspections')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('activity');
            $table->text('description')->nullable();

            $table->timestamps();

            $table->index('inspection_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inspection_activity_logs');
        Schema::dropIfExists('inspection_photos');
        Schema::dropIfExists('inspection_signatures');
        Schema::dropIfExists('inspection_findings');
        Schema::dropIfExists('inspection_checklist_items');
        Schema::dropIfExists('inspections');
    }
};

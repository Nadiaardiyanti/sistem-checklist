<?php

use App\Models\Inspection;
use App\Models\InspectionChecklistItem;
use App\Models\InspectionFact;
use App\Models\User;
use App\Services\EtlService;

function makeInspection(string $terminal, string $brand, array $statuses): Inspection
{
    $inspection = Inspection::create([
        'partner_name' => 'PT ' . $brand, 'brand' => $brand, 'location' => 'Lantai 1',
        'terminal' => $terminal, 'inspection_date' => now()->toDateString(),
        'inspector_name' => 'Budi',
    ]);

    foreach ($statuses as $i => $status) {
        InspectionChecklistItem::create([
            'inspection_id' => $inspection->id, 'section_code' => 'A', 'section_name' => 'Uji',
            'code' => 'A' . ($i + 1), 'criteria' => 'Kriteria ' . ($i + 1),
            'status' => $status, 'sort_order' => $i + 1,
        ]);
    }

    return $inspection;
}

test('ETL menghitung skor kepatuhan dan idempoten', function () {
    makeInspection('Domestic', ' kopi   enak ', ['sesuai', 'sesuai', 'sesuai', 'tidak_sesuai', 'tidak_berlaku', null]);

    $etl = new EtlService();
    $first = $etl->run();
    $etl->run(); // dijalankan ulang: tidak boleh menggandakan baris

    $fact = InspectionFact::first();

    expect($first['loaded'])->toBe(1);
    expect(InspectionFact::count())->toBe(1);
    expect($fact->brand)->toBe('Kopi Enak');           // transform: dibersihkan
    expect($fact->sesuai_count)->toBe(3);
    expect($fact->tidak_sesuai_count)->toBe(1);
    expect($fact->belum_dinilai_count)->toBe(1);
    expect($fact->compliance_score)->toBe(75.0);        // 3 / (3+1)
});

test('dashboard eksekutif menampilkan level drill-down', function () {
    $user = User::factory()->create();
    makeInspection('Domestic', 'Kopi Enak', ['sesuai', 'tidak_sesuai']);

    $this->actingAs($user)->get('/executive')->assertOk()->assertSee('Domestic');
    $this->actingAs($user)->get('/executive?terminal=Domestic')->assertOk()->assertSee('Kopi Enak');
    $this->actingAs($user)->get('/executive?terminal=Domestic&brand=Kopi%20Enak')->assertOk()->assertSee('Daftar Inspeksi')->assertSee('Inspeksi #');
});

test('what-if memproyeksikan skor dengan benar', function () {
    $user = User::factory()->create();
    makeInspection('Domestic', 'Kopi Enak', ['sesuai', 'sesuai', 'tidak_sesuai', 'tidak_sesuai']); // 50%

    // perbaiki 50% dari 2 item tidak sesuai -> (2+1)/4 = 75%
    $this->actingAs($user)->get('/executive/what-if?fix_percent=50&target=90')
        ->assertOk()->assertSee('75,0%');
});

test('what-if menolak input di luar rentang', function () {
    $user = User::factory()->create();

    $this->actingAs($user)->get('/executive/what-if?fix_percent=150')->assertSessionHasErrors('fix_percent');
});

test('hanya admin yang boleh menjalankan ETL manual', function () {
    $inspector = User::factory()->create();
    $admin = User::factory()->admin()->create();

    $this->actingAs($inspector)->post('/executive/etl')->assertForbidden();
    $this->actingAs($admin)->post('/executive/etl')->assertRedirect();
});

test('export CSV tersedia dan aman dari injeksi rumus', function () {
    $user = User::factory()->create();
    makeInspection('Domestic', '=HYPERLINK("x")', ['sesuai']);

    $response = $this->actingAs($user)->get('/executive/export/csv');

    $response->assertOk();
    expect($response->streamedContent())->toContain("'=Hyperlink");
});

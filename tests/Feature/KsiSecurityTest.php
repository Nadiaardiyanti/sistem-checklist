<?php

use App\Models\Inspection;
use App\Models\SecurityEvent;
use App\Models\User;

// ---------- KSI: Otorisasi (RBAC) ----------

test('guest tidak bisa membuka dashboard eksekutif', function () {
    $this->get('/executive')->assertRedirect('/login');
});

test('inspector ditolak membuka audit keamanan dan kejadiannya tercatat', function () {
    $user = User::factory()->create(['role' => 'inspector']);

    $this->actingAs($user)->get('/security/audit')->assertForbidden();

    expect(SecurityEvent::where('event', 'access_denied')->where('user_id', $user->id)->exists())->toBeTrue();
});

test('admin bisa membuka audit keamanan', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get('/security/audit')->assertOk();
});

test('role tidak bisa diubah lewat mass assignment', function () {
    $user = User::create([
        'name' => 'X', 'email' => 'x@example.com', 'password' => 'Passw0rd!Passw0rd',
        'role' => 'admin',
    ]);

    expect($user->fresh()->role)->not->toBe('admin');
});

test('inspector tidak bisa mengubah inspeksi milik orang lain', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();

    $inspection = Inspection::create([
        'partner_name' => 'PT A', 'brand' => 'Brand A', 'location' => 'L1',
        'inspection_date' => now()->toDateString(), 'inspector_name' => 'Budi',
        'created_by' => $owner->id,
    ]);

    $this->actingAs($other)->get("/inspections/{$inspection->id}/edit")->assertForbidden();
    $this->actingAs($owner)->get("/inspections/{$inspection->id}/edit")->assertOk();
});

test('admin bisa membuat akun baru dan kejadiannya tercatat', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post('/security/users', [
        'name' => 'Petugas Baru',
        'email' => 'petugas@example.com',
        'password' => 'Passw0rd!Passw0rd',
        'password_confirmation' => 'Passw0rd!Passw0rd',
        'role' => 'inspector',
    ])->assertRedirect();

    expect(User::where('email', 'petugas@example.com')->value('role'))->toBe('inspector');
    expect(SecurityEvent::where('event', 'user_created')->exists())->toBeTrue();
});

test('inspector tidak bisa membuat akun baru', function () {
    $user = User::factory()->create(['role' => 'inspector']);

    $this->actingAs($user)->post('/security/users', [
        'name' => 'X', 'email' => 'x2@example.com',
        'password' => 'Passw0rd!Passw0rd', 'password_confirmation' => 'Passw0rd!Passw0rd',
        'role' => 'admin',
    ])->assertForbidden();

    expect(User::where('email', 'x2@example.com')->exists())->toBeFalse();
});

// ---------- KSI: Accounting ----------

test('login gagal dan berhasil dicatat di security_events', function () {
    $user = User::factory()->create();

    $this->post('/login', ['email' => $user->email, 'password' => 'salah-total']);
    expect(SecurityEvent::where('event', 'login_failed')->exists())->toBeTrue();

    $this->post('/login', ['email' => $user->email, 'password' => 'password']);
    expect(SecurityEvent::where('event', 'login_success')->where('user_id', $user->id)->exists())->toBeTrue();
});

// ---------- KSI: Header keamanan ----------

test('respons memuat header keamanan', function () {
    $this->get('/login')
        ->assertHeader('X-Frame-Options', 'SAMEORIGIN')
        ->assertHeader('X-Content-Type-Options', 'nosniff')
        ->assertHeader('Content-Security-Policy');
});

test('CSP membatasi sumber konten', function () {
    $csp = \App\Http\Middleware\SecurityHeaders::csp();

    expect($csp)->toContain("default-src 'self'")
        ->toContain("object-src 'none'")
        ->toContain("frame-ancestors 'self'");
});

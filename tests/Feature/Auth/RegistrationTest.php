<?php

use App\Models\User;

test('registrasi publik dinonaktifkan', function () {
    $this->get('/register')->assertNotFound();

    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'Passw0rd!Passw0rd',
        'password_confirmation' => 'Passw0rd!Passw0rd',
    ])->assertStatus(404);

    $this->assertGuest();
    expect(User::where('email', 'test@example.com')->exists())->toBeFalse();
});

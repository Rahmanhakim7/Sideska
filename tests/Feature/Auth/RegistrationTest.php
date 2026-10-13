<?php

namespace Tests\Feature\Auth;

use App\Models\User;

test('registration screen is no longer available', function () {
    $this->get('/register')->assertNotFound();
});

test('public registration cannot create a user', function () {
    $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();

    expect(User::where('email', 'test@example.com')->exists())->toBeFalse();
});

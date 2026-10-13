<?php

use App\Models\User;
use Livewire\Volt\Volt;

it('mengizinkan pengguna aktif untuk login', function () {
    $user = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => true,
    ]);

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticatedAs($user);
});

it('menolak login pengguna yang dinonaktifkan', function () {
    $user = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => false,
    ]);

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component
        ->assertHasErrors('form.email')
        ->assertNoRedirect();

    $this->assertGuest();
});

it('memaksa logout sesi pengguna yang dinonaktifkan pada request berikutnya', function () {
    $user = User::factory()->create(['role' => 'masyarakat']);

    $this->actingAs($user);

    $user->is_active = false;
    $user->save();

    $this->get(route('dashboard'))
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('menyediakan scope query untuk pengguna aktif', function () {
    $aktif = User::factory()->create(['is_active' => true]);
    User::factory()->create(['is_active' => false]);

    $hasil = User::active()->pluck('id');

    expect($hasil)->toContain($aktif->id)
        ->and($hasil)->toHaveCount(1);
});

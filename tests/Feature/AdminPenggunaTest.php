<?php

use App\Livewire\Admin\Pengguna\Index;
use App\Models\User;
use Livewire\Livewire;

it('menampilkan halaman pengguna untuk admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna'))
        ->assertOk();
});

it('menolak akses halaman pengguna untuk role selain admin', function (string $role) {
    $user = User::factory()->create(['role' => $role]);

    $this->actingAs($user)
        ->get(route('admin.pengguna'))
        ->assertForbidden();
})->with(['perangkat_desa', 'kepala_desa', 'masyarakat']);

it('mengalihkan tamu dari halaman pengguna', function () {
    $this->get(route('admin.pengguna'))
        ->assertRedirect(route('login'));
});

it('menampilkan daftar pengguna tanpa membocorkan kredensial', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create([
        'name' => 'Nama Warga Uji',
        'email' => 'warga.uji@contoh.test',
        'role' => 'masyarakat',
    ]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->assertSee('Nama Warga Uji')
        ->assertSee('warga.uji@contoh.test')
        ->assertDontSee($warga->getAuthPassword());
});

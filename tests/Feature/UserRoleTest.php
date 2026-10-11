<?php

use App\Livewire\PerangkatDesa\Penduduk\Create;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('membuat user dengan role masyarakat saat menambah penduduk', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    Livewire::actingAs($perangkat)
        ->test(Create::class)
        ->set('email', 'warga@contoh.test')
        ->set('password', 'rahasia123')
        ->set('password_confirmation', 'rahasia123')
        ->set('nik', fake()->numerify('################'))
        ->set('no_kk', fake()->numerify('################'))
        ->set('nama', 'Nama Warga')
        ->set('tempat_lahir', 'Jember')
        ->set('tanggal_lahir', '1990-01-01')
        ->set('jenis_kelamin', 'L')
        ->set('agama', 'Islam')
        ->set('status_perkawinan', 'Kawin')
        ->set('pendidikan', 'SMA/Sederajat')
        ->set('pekerjaan', 'Petani')
        ->set('status_hubungan_keluarga', 'Kepala Keluarga')
        ->set('golongan_darah', 'O')
        ->set('alamat', 'Jl. Contoh')
        ->set('rt', '001')
        ->set('rw', '002')
        ->set('dusun', 'Dusun Sumber')
        ->set('kewarganegaraan', 'WNI')
        ->set('no_hp', '081234567890')
        ->set('status_kependudukan', 'Tetap')
        ->set('disabilitas', 'Tidak')
        ->call('simpan')
        ->assertHasNoErrors();

    expect(User::where('email', 'warga@contoh.test')->firstOrFail()->role)
        ->toBe('masyarakat');
});

it('menghasilkan role yang benar pada seeder', function () {
    $this->seed(DatabaseSeeder::class);

    $peran = [
        'admin@sideska.test' => 'admin',
        'perangkat@sideska.test' => 'perangkat_desa',
        'kepala@sideska.test' => 'kepala_desa',
        'masyarakat@sideska.test' => 'masyarakat',
    ];

    foreach ($peran as $email => $role) {
        expect(User::where('email', $email)->firstOrFail()->role)->toBe($role);
    }
});

it('tidak dapat mengubah role melalui mass assignment', function () {
    $user = User::factory()->create();

    $user->fill(['role' => 'admin']);
    $user->save();

    expect($user->fresh()->role)->toBe('masyarakat');
});

it('mengabaikan role pada mass assignment saat membuat user', function () {
    $user = User::create([
        'name' => 'Penyusup',
        'email' => 'penyusup@contoh.test',
        'password' => 'rahasia123',
        'role' => 'admin',
    ]);

    expect($user->fresh()->role)->toBe('masyarakat');
});

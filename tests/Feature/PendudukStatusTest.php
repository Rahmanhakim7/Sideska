<?php

use App\Livewire\PerangkatDesa\Penduduk\Create;
use App\Livewire\PerangkatDesa\Penduduk\Edit;
use App\Models\Penduduk;
use App\Models\User;
use Livewire\Livewire;

function isiFormPenduduk(object $component, string $status): object
{
    return $component
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
        ->set('status_kependudukan', $status)
        ->set('disabilitas', 'Tidak');
}

it('menolak pembuatan penduduk dengan status Pindah', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    isiFormPenduduk(Livewire::actingAs($perangkat)->test(Create::class), 'Pindah')
        ->call('simpan')
        ->assertHasErrors('status_kependudukan');

    expect(Penduduk::where('email', 'warga@contoh.test')->exists())->toBeFalse();
});

it('menerima pembuatan penduduk dengan status Tetap', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    isiFormPenduduk(Livewire::actingAs($perangkat)->test(Create::class), 'Tetap')
        ->call('simpan')
        ->assertHasNoErrors();

    expect(Penduduk::where('nama', 'Nama Warga')->exists())->toBeTrue();
});

it('tidak dapat mengubah status kependudukan melalui form edit', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $akun = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create([
        'user_id' => $akun->id,
        'status_kependudukan' => 'Tetap',
    ]);

    Livewire::actingAs($perangkat)
        ->test(Edit::class, ['penduduk' => $penduduk])
        ->set('pendidikan', 'SMA/Sederajat')
        ->set('rt', '001')
        ->set('rw', '002')
        ->set('status_kependudukan', 'Pindah')
        ->call('update')
        ->assertHasNoErrors();

    expect($penduduk->fresh()->status_kependudukan)->toBe('Tetap');
});

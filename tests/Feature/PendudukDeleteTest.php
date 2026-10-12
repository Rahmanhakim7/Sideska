<?php

use App\Livewire\PerangkatDesa\Penduduk\Index;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\User;
use Livewire\Livewire;

it('menghapus data penduduk beserta akun SIDESKA oleh perangkat desa', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    $akun = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $akun->id]);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    Livewire::actingAs($perangkat)
        ->test(Index::class)
        ->call('hapus', $penduduk->id);

    expect(Penduduk::find($penduduk->id))->toBeNull();
    expect(User::find($akun->id))->toBeNull();
    expect(PengajuanSurat::find($pengajuan->id))->toBeNull();
});

it('menolak penghapusan penduduk oleh role selain perangkat desa', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create();

    Livewire::actingAs($warga)
        ->test(Index::class)
        ->call('hapus', $penduduk->id)
        ->assertStatus(403);

    expect(Penduduk::find($penduduk->id))->not->toBeNull();
});

it('menolak penghapusan penduduk oleh pengguna yang belum terautentikasi', function () {
    $penduduk = Penduduk::factory()->create();

    Livewire::test(Index::class)
        ->call('hapus', $penduduk->id)
        ->assertStatus(403);

    expect(Penduduk::find($penduduk->id))->not->toBeNull();
});

it('membuka modal konfirmasi dengan data penduduk yang dipilih', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $penduduk = Penduduk::factory()->create(['nama' => 'Budi Santoso']);

    $komponen = Livewire::actingAs($perangkat)
        ->test(Index::class)
        ->call('konfirmasiHapus', $penduduk->id)
        ->assertSet('showModalHapus', true)
        ->assertSee('Hapus Data Penduduk?')
        ->assertSee('Budi Santoso')
        ->assertSee($penduduk->nik);

    expect($komponen->get('pendudukAkanDihapus')->id)->toBe($penduduk->id);
});

it('membatalkan konfirmasi tidak menghapus penduduk', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $penduduk = Penduduk::factory()->create();

    Livewire::actingAs($perangkat)
        ->test(Index::class)
        ->call('konfirmasiHapus', $penduduk->id)
        ->set('showModalHapus', false)
        ->assertSet('showModalHapus', false);

    expect(Penduduk::find($penduduk->id))->not->toBeNull();
});

it('menghapus penduduk melalui alur modal konfirmasi', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    $akun = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $akun->id]);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    Livewire::actingAs($perangkat)
        ->test(Index::class)
        ->call('konfirmasiHapus', $penduduk->id)
        ->assertSet('showModalHapus', true)
        ->call('hapusPenduduk')
        ->assertSet('showModalHapus', false)
        ->assertSet('pendudukAkanDihapus', null);

    expect(Penduduk::find($penduduk->id))->toBeNull();
    expect(User::find($akun->id))->toBeNull();
    expect(PengajuanSurat::find($pengajuan->id))->toBeNull();
});

it('menolak konfirmasi hapus penduduk oleh role selain perangkat desa', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create();

    Livewire::actingAs($warga)
        ->test(Index::class)
        ->call('konfirmasiHapus', $penduduk->id)
        ->call('hapusPenduduk')
        ->assertStatus(403);

    expect(Penduduk::find($penduduk->id))->not->toBeNull();
});

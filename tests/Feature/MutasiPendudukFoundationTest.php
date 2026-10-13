<?php

use App\Enums\JenisMutasi;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\User;
use App\Services\MutasiPendudukService;
use Illuminate\Support\Facades\Schema;

it('membuat tabel mutasi_penduduks dengan kolom yang dibutuhkan', function () {
    expect(Schema::hasTable('mutasi_penduduks'))->toBeTrue();

    foreach ([
        'id',
        'penduduk_id',
        'jenis',
        'tanggal_mutasi',
        'alamat_asal',
        'alamat_tujuan',
        'alasan',
        'catatan',
        'pengajuan_surat_id',
        'user_id',
        'created_at',
        'updated_at',
    ] as $kolom) {
        expect(Schema::hasColumn('mutasi_penduduks', $kolom))->toBeTrue();
    }
});

it('menyimpan mutasi penduduk dan meng-cast jenis ke enum', function () {
    $mutasi = MutasiPenduduk::factory()->masuk()->create([
        'tanggal_mutasi' => '2026-01-15',
    ]);

    $mutasi->refresh();

    expect($mutasi->jenis)->toBe(JenisMutasi::MASUK)
        ->and($mutasi->tanggal_mutasi->toDateString())->toBe('2026-01-15')
        ->and($mutasi->penduduk)->toBeInstanceOf(Penduduk::class);
});

it('memiliki relasi penduduk, pengajuan surat, dan user', function () {
    $user = User::factory()->create(['role' => 'perangkat_desa']);
    $penduduk = Penduduk::factory()->create();
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    $mutasi = MutasiPenduduk::factory()->create([
        'penduduk_id' => $penduduk->id,
        'pengajuan_surat_id' => $pengajuan->id,
        'user_id' => $user->id,
    ]);

    expect($mutasi->penduduk->is($penduduk))->toBeTrue()
        ->and($mutasi->pengajuanSurat->is($pengajuan))->toBeTrue()
        ->and($mutasi->user->is($user))->toBeTrue();
});

it('menautkan penduduk ke banyak mutasi', function () {
    $penduduk = Penduduk::factory()->create();
    MutasiPenduduk::factory()->count(2)->create(['penduduk_id' => $penduduk->id]);

    expect($penduduk->mutasiPenduduk)->toHaveCount(2)
        ->and($penduduk->mutasiPenduduk->first())->toBeInstanceOf(MutasiPenduduk::class);
});

it('menghapus mutasi ketika penduduk dihapus', function () {
    $mutasi = MutasiPenduduk::factory()->create();
    $id = $mutasi->id;

    $mutasi->penduduk->delete();

    expect(MutasiPenduduk::find($id))->toBeNull();
});

it('mengosongkan referensi pengajuan surat dan user saat dihapus', function () {
    $user = User::factory()->create(['role' => 'perangkat_desa']);
    $pengajuan = PengajuanSurat::factory()->create();

    $mutasi = MutasiPenduduk::factory()->create([
        'pengajuan_surat_id' => $pengajuan->id,
        'user_id' => $user->id,
    ]);

    $pengajuan->delete();
    $user->delete();

    $mutasi->refresh();

    expect($mutasi->pengajuan_surat_id)->toBeNull()
        ->and($mutasi->user_id)->toBeNull();
});

it('menyediakan values dan label untuk enum jenis mutasi', function () {
    expect(JenisMutasi::values())->toBe(['masuk', 'keluar'])
        ->and(JenisMutasi::MASUK->label())->toBe('Masuk')
        ->and(JenisMutasi::KELUAR->label())->toBe('Keluar');
});

it('menyusun snapshot alamat lengkap dari data penduduk', function () {
    $penduduk = Penduduk::factory()->create([
        'alamat' => 'Jl. Merdeka No. 10',
        'rt' => '001',
        'rw' => '002',
        'dusun' => 'Dusun Krajan',
    ]);

    $snapshot = app(MutasiPendudukService::class)->alamatSnapshot($penduduk);

    expect($snapshot)->toContain('Jl. Merdeka No. 10')
        ->and($snapshot)->toContain('RT 001 RW 002')
        ->and($snapshot)->toContain('Dusun Krajan')
        ->and($snapshot)->toContain('Desa Sekartejo');
});

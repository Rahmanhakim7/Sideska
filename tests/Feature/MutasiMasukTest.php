<?php

use App\Enums\JenisMutasi;
use App\Enums\StatusKependudukan;
use App\Livewire\PerangkatDesa\MutasiMasuk\Create;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use App\Models\User;
use App\Services\MutasiPendudukService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

function dataMutasiMasuk(array $override = []): array
{
    return array_merge([
        'nama' => 'Warga Masuk',
        'email' => 'masuk@contoh.test',
        'password' => 'rahasia123',
        'nik' => fake()->numerify('################'),
        'no_kk' => fake()->numerify('################'),
        'tempat_lahir' => 'Jember',
        'tanggal_lahir' => '1990-01-01',
        'jenis_kelamin' => 'L',
        'agama' => 'Islam',
        'status_perkawinan' => 'Kawin',
        'pendidikan' => 'SMA/Sederajat',
        'pekerjaan' => 'Petani',
        'status_hubungan_keluarga' => 'Kepala Keluarga',
        'golongan_darah' => 'O',
        'alamat' => 'Jl. Masuk No. 1',
        'rt' => '001',
        'rw' => '002',
        'dusun' => 'Dusun Sumber',
        'kewarganegaraan' => 'WNI',
        'no_hp' => '081234567890',
        'disabilitas' => 'Tidak',
        'nama_ayah' => 'Ayah Warga',
        'nama_ibu' => 'Ibu Warga',
        'tanggal_mutasi' => '2026-02-01',
        'alamat_asal' => 'Jl. Asal No. 2, Kota Asal',
        'alasan' => 'Pekerjaan',
        'catatan' => 'Catatan uji',
    ], $override);
}

function isiFormMutasiMasuk(object $component, array $override = []): object
{
    foreach (dataMutasiMasuk($override) as $field => $value) {
        $component = $component->set($field, $value);
    }

    return $component->set(
        'password_confirmation',
        $override['password'] ?? 'rahasia123'
    );
}

it('mencatat mutasi masuk secara lengkap dalam satu alur', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    $mutasi = app(MutasiPendudukService::class)->mutasiMasuk(dataMutasiMasuk());

    $user = User::where('email', 'masuk@contoh.test')->firstOrFail();
    $penduduk = $user->penduduk;

    expect($user->role)->toBe('masyarakat')
        ->and($user->is_active)->toBeTrue()
        ->and($penduduk)->not->toBeNull()
        ->and($penduduk->status_kependudukan)->toBe(StatusKependudukan::PENDATANG->value)
        ->and($mutasi->jenis)->toBe(JenisMutasi::MASUK)
        ->and($mutasi->penduduk_id)->toBe($penduduk->id)
        ->and($mutasi->user_id)->toBe($perangkat->id)
        ->and($mutasi->tanggal_mutasi->toDateString())->toBe('2026-02-01')
        ->and($mutasi->alamat_asal)->toBe('Jl. Asal No. 2, Kota Asal')
        ->and($mutasi->alasan)->toBe('Pekerjaan')
        ->and($mutasi->catatan)->toBe('Catatan uji')
        ->and($mutasi->alamat_tujuan)->toContain('Jl. Masuk No. 1')
        ->and($mutasi->alamat_tujuan)->toContain('Desa Sekartejo');
});

it('membatalkan transaksi tanpa menyisakan user, penduduk, atau mutasi', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    $existing = Penduduk::factory()->create();
    $jumlahUser = User::count();
    $jumlahPenduduk = Penduduk::count();
    $jumlahMutasi = MutasiPenduduk::count();

    expect(fn () => app(MutasiPendudukService::class)->mutasiMasuk(
        dataMutasiMasuk(['nik' => $existing->nik])
    ))->toThrow(QueryException::class);

    expect(User::count())->toBe($jumlahUser)
        ->and(Penduduk::count())->toBe($jumlahPenduduk)
        ->and(MutasiPenduduk::count())->toBe($jumlahMutasi);
});

it('mencatat actor dari pengguna yang login dan mengabaikan user_id pada data', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    $mutasi = app(MutasiPendudukService::class)->mutasiMasuk(
        dataMutasiMasuk(['user_id' => 999999])
    );

    expect($mutasi->user_id)->toBe($perangkat->id);
});

it('menyimpan password user dalam bentuk hash', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    app(MutasiPendudukService::class)->mutasiMasuk(
        dataMutasiMasuk(['password' => 'rahasia123'])
    );

    $user = User::where('email', 'masuk@contoh.test')->firstOrFail();

    expect($user->password)->not->toBe('rahasia123')
        ->and(Hash::check('rahasia123', $user->password))->toBeTrue();
});

it('menetapkan status pendatang yang tidak berasal dari input', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    $mutasi = app(MutasiPendudukService::class)->mutasiMasuk(
        dataMutasiMasuk(['status_kependudukan' => 'Tetap'])
    );

    expect($mutasi->penduduk->status_kependudukan)
        ->toBe(StatusKependudukan::PENDATANG->value)
        ->and($mutasi->penduduk->status_kependudukan)
        ->not->toBe(StatusKependudukan::TETAP->value);
});

it('mengizinkan perangkat desa menjalankan mutasi masuk melalui form', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    isiFormMutasiMasuk(
        Livewire::actingAs($perangkat)->test(Create::class)
    )
        ->call('simpan')
        ->assertHasNoErrors()
        ->assertRedirect(route('perangkat-desa.penduduk.index'));

    $user = User::where('email', 'masuk@contoh.test')->firstOrFail();

    expect($user->role)->toBe('masyarakat')
        ->and($user->penduduk->status_kependudukan)
        ->toBe(StatusKependudukan::PENDATANG->value)
        ->and($user->penduduk->mutasiPenduduk()->count())->toBe(1);
});

it('menolak mutasi masuk oleh kepala desa', function () {
    $kepala = User::factory()->create(['role' => 'kepala_desa']);

    isiFormMutasiMasuk(
        Livewire::actingAs($kepala)->test(Create::class)
    )
        ->call('simpan')
        ->assertStatus(403);

    expect(User::where('email', 'masuk@contoh.test')->exists())->toBeFalse()
        ->and(MutasiPenduduk::count())->toBe(0);
});

it('menolak mutasi masuk oleh masyarakat', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);

    isiFormMutasiMasuk(
        Livewire::actingAs($warga)->test(Create::class)
    )
        ->call('simpan')
        ->assertStatus(403);

    expect(User::where('email', 'masuk@contoh.test')->exists())->toBeFalse()
        ->and(MutasiPenduduk::count())->toBe(0);
});

it('menolak mutasi masuk ketika email sudah digunakan', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    User::factory()->create(['email' => 'masuk@contoh.test']);

    $jumlahPenduduk = Penduduk::count();

    isiFormMutasiMasuk(
        Livewire::actingAs($perangkat)->test(Create::class)
    )
        ->call('simpan')
        ->assertHasErrors('email');

    expect(Penduduk::count())->toBe($jumlahPenduduk)
        ->and(MutasiPenduduk::count())->toBe(0);
});

it('menolak tanggal mutasi yang tidak valid', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    isiFormMutasiMasuk(
        Livewire::actingAs($perangkat)->test(Create::class),
        ['tanggal_mutasi' => 'bukan-tanggal']
    )
        ->call('simpan')
        ->assertHasErrors('tanggal_mutasi');

    expect(User::where('email', 'masuk@contoh.test')->exists())->toBeFalse()
        ->and(MutasiPenduduk::count())->toBe(0);
});

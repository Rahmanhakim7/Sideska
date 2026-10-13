<?php

use App\Enums\JenisMutasi;
use App\Enums\StatusKependudukan;
use App\Livewire\PerangkatDesa\MutasiKeluar\Create;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use App\Models\User;
use App\Services\MutasiPendudukService;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Livewire;

function dataMutasiKeluar(array $override = []): array
{
    return array_merge([
        'tanggal_mutasi' => '2026-03-15',
        'alamat_tujuan' => 'Jl. Tujuan No. 10, Kota Tujuan',
        'alasan' => 'Pekerjaan',
        'catatan' => 'Catatan uji keluar',
    ], $override);
}

function isiFormMutasiKeluar(object $component, array $override = []): object
{
    foreach (dataMutasiKeluar($override) as $field => $value) {
        $component = $component->set($field, $value);
    }

    return $component;
}

it('mencatat mutasi keluar dan mengubah status tanpa menghapus data', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    $penduduk = Penduduk::factory()->create([
        'status_kependudukan' => StatusKependudukan::TETAP->value,
    ]);

    $mutasi = app(MutasiPendudukService::class)->mutasiKeluar(
        $penduduk->id,
        dataMutasiKeluar()
    );

    expect($penduduk->fresh()->status_kependudukan)
        ->toBe(StatusKependudukan::PINDAH->value)
        ->and($penduduk->user->fresh()->is_active)->toBeFalse()
        ->and($mutasi->jenis)->toBe(JenisMutasi::KELUAR)
        ->and($mutasi->penduduk_id)->toBe($penduduk->id)
        ->and($mutasi->user_id)->toBe($perangkat->id);
});

it('mencatat detail event mutasi keluar dengan benar', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    $penduduk = Penduduk::factory()->create([
        'alamat' => 'Jl. Asal No. 5',
        'rt' => '007',
        'rw' => '008',
        'dusun' => 'Asal',
    ]);

    $mutasi = app(MutasiPendudukService::class)->mutasiKeluar(
        $penduduk->id,
        dataMutasiKeluar()
    );

    expect($mutasi->jenis)->toBe(JenisMutasi::KELUAR)
        ->and($mutasi->penduduk_id)->toBe($penduduk->id)
        ->and($mutasi->tanggal_mutasi->toDateString())->toBe('2026-03-15')
        ->and($mutasi->alamat_asal)->toBe(
            'Jl. Asal No. 5, RT 007 RW 008, Dusun Asal, '
            .'Desa Sekartejo, Kecamatan Pituruh, Kabupaten Purworejo'
        )
        ->and($mutasi->alamat_tujuan)->toBe('Jl. Tujuan No. 10, Kota Tujuan')
        ->and($mutasi->alasan)->toBe('Pekerjaan')
        ->and($mutasi->catatan)->toBe('Catatan uji keluar');
});

it('memakai actor yang login dan mengabaikan user_id pada data', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    $penduduk = Penduduk::factory()->create();

    $mutasi = app(MutasiPendudukService::class)->mutasiKeluar(
        $penduduk->id,
        dataMutasiKeluar(['user_id' => 999999])
    );

    expect($mutasi->user_id)->toBe($perangkat->id);
});

it('menolak mutasi keluar untuk penduduk yang sudah berstatus pindah', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    $penduduk = Penduduk::factory()->create([
        'status_kependudukan' => StatusKependudukan::PINDAH->value,
    ]);

    expect(fn () => app(MutasiPendudukService::class)->mutasiKeluar(
        $penduduk->id,
        dataMutasiKeluar()
    ))->toThrow(ValidationException::class);

    expect(MutasiPenduduk::count())->toBe(0)
        ->and($penduduk->fresh()->status_kependudukan)
        ->toBe(StatusKependudukan::PINDAH->value)
        ->and($penduduk->user->fresh()->is_active)->toBeTrue();
});

it('menolak mutasi keluar untuk penduduk tanpa user', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    DB::statement('PRAGMA defer_foreign_keys = ON');
    $penduduk = Penduduk::factory()->create([
        'user_id' => 999999,
        'status_kependudukan' => StatusKependudukan::TETAP->value,
    ]);

    $jumlahUser = User::count();

    expect($penduduk->user)->toBeNull();

    expect(fn () => app(MutasiPendudukService::class)->mutasiKeluar(
        $penduduk->id,
        dataMutasiKeluar()
    ))->toThrow(ValidationException::class);

    expect($penduduk->fresh()->status_kependudukan)
        ->toBe(StatusKependudukan::TETAP->value)
        ->and(MutasiPenduduk::count())->toBe(0)
        ->and(User::count())->toBe($jumlahUser);
});

it('menolak mutasi keluar kedua setelah mutasi pertama berhasil', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    $penduduk = Penduduk::factory()->create([
        'status_kependudukan' => StatusKependudukan::PENDATANG->value,
    ]);

    app(MutasiPendudukService::class)->mutasiKeluar(
        $penduduk->id,
        dataMutasiKeluar()
    );

    expect(fn () => app(MutasiPendudukService::class)->mutasiKeluar(
        $penduduk->id,
        dataMutasiKeluar()
    ))->toThrow(ValidationException::class);

    expect($penduduk->mutasiPenduduk()->count())->toBe(1);
});

it('mengizinkan perangkat desa menjalankan mutasi keluar melalui form', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $penduduk = Penduduk::factory()->create([
        'status_kependudukan' => StatusKependudukan::TETAP->value,
    ]);

    isiFormMutasiKeluar(
        Livewire::actingAs($perangkat)->test(Create::class, ['penduduk' => $penduduk])
    )
        ->call('simpan')
        ->assertHasNoErrors()
        ->assertRedirect(route('perangkat-desa.penduduk.index'));

    expect($penduduk->fresh()->status_kependudukan)
        ->toBe(StatusKependudukan::PINDAH->value)
        ->and($penduduk->user->fresh()->is_active)->toBeFalse()
        ->and($penduduk->mutasiPenduduk()->count())->toBe(1);
});

it('menolak mutasi keluar untuk role selain perangkat desa', function (string $role) {
    $aktor = User::factory()->create(['role' => $role]);
    $penduduk = Penduduk::factory()->create([
        'status_kependudukan' => StatusKependudukan::TETAP->value,
    ]);

    isiFormMutasiKeluar(
        Livewire::actingAs($aktor)->test(Create::class, ['penduduk' => $penduduk])
    )
        ->call('simpan')
        ->assertStatus(403);

    expect($penduduk->fresh()->status_kependudukan)
        ->toBe(StatusKependudukan::TETAP->value)
        ->and($penduduk->user->fresh()->is_active)->toBeTrue()
        ->and(MutasiPenduduk::count())->toBe(0);
})->with(['kepala_desa', 'masyarakat', 'admin']);

it('tidak menghapus user dan penduduk setelah mutasi keluar', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    $penduduk = Penduduk::factory()->create();
    $userId = $penduduk->user_id;

    app(MutasiPendudukService::class)->mutasiKeluar(
        $penduduk->id,
        dataMutasiKeluar()
    );

    $pendudukSegar = Penduduk::find($penduduk->id);
    $userSegar = User::find($userId);

    expect($pendudukSegar)->not->toBeNull()
        ->and($userSegar)->not->toBeNull()
        ->and($pendudukSegar->user_id)->toBe($userId)
        ->and($pendudukSegar->user)->not->toBeNull()
        ->and($pendudukSegar->user->id)->toBe($userId);
});

it('mengabaikan status_kependudukan dari input dan tetap memakai pindah', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    $penduduk = Penduduk::factory()->create([
        'status_kependudukan' => StatusKependudukan::TETAP->value,
    ]);

    $mutasi = app(MutasiPendudukService::class)->mutasiKeluar(
        $penduduk->id,
        dataMutasiKeluar(['status_kependudukan' => 'Pendatang'])
    );

    expect($mutasi->penduduk->status_kependudukan)
        ->toBe(StatusKependudukan::PINDAH->value);
});

it('membatalkan transaksi dan memulihkan status ketika pencatatan mutasi gagal', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $this->actingAs($perangkat);

    $penduduk = Penduduk::factory()->create([
        'status_kependudukan' => StatusKependudukan::TETAP->value,
    ]);
    $penduduk->user->update(['is_active' => true]);

    MutasiPenduduk::creating(function (): void {
        throw new RuntimeException('kegagalan yang disimulasikan');
    });

    expect(fn () => app(MutasiPendudukService::class)->mutasiKeluar(
        $penduduk->id,
        dataMutasiKeluar()
    ))->toThrow(RuntimeException::class);

    expect($penduduk->fresh()->status_kependudukan)
        ->toBe(StatusKependudukan::TETAP->value)
        ->and($penduduk->user->fresh()->is_active)->toBeTrue()
        ->and(MutasiPenduduk::count())->toBe(0);
});

it('menolak tanggal mutasi yang tidak valid', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $penduduk = Penduduk::factory()->create([
        'status_kependudukan' => StatusKependudukan::TETAP->value,
    ]);

    isiFormMutasiKeluar(
        Livewire::actingAs($perangkat)->test(Create::class, ['penduduk' => $penduduk]),
        ['tanggal_mutasi' => 'bukan-tanggal']
    )
        ->call('simpan')
        ->assertHasErrors('tanggal_mutasi');

    expect(MutasiPenduduk::count())->toBe(0)
        ->and($penduduk->fresh()->status_kependudukan)
        ->toBe(StatusKependudukan::TETAP->value);
});

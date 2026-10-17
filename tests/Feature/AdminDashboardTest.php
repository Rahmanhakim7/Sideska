<?php

use App\Enums\PengajuanSuratStatus;
use App\Livewire\Admin\Dashboard;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\User;
use Livewire\Livewire;

it('menampilkan dashboard admin untuk admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertOk();
});

it('mengalihkan tamu dari dashboard admin', function () {
    $this->get(route('admin.dashboard'))
        ->assertRedirect(route('login'));
});

it('menolak akses dashboard admin untuk role selain admin', function (string $role) {
    $user = User::factory()->create(['role' => $role]);

    $this->actingAs($user)
        ->get(route('admin.dashboard'))
        ->assertForbidden();
})->with(['perangkat_desa', 'kepala_desa', 'masyarakat']);

it('menampilkan statistik pengguna yang benar', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'perangkat_desa', 'is_active' => true]);
    User::factory()->create(['role' => 'kepala_desa', 'is_active' => false]);
    User::factory()->count(2)->create(['role' => 'masyarakat', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(Dashboard::class)
        ->assertViewHas('totalUser', 5)
        ->assertViewHas('userAktif', 4)
        ->assertViewHas('userNonaktif', 1)
        ->assertViewHas('userAdmin', 1)
        ->assertViewHas('userPerangkatDesa', 1)
        ->assertViewHas('userKepalaDesa', 1)
        ->assertViewHas('userMasyarakat', 2);
});

it('menampilkan statistik penduduk yang benar', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Penduduk::factory()->count(3)->create(['jenis_kelamin' => 'L']);
    Penduduk::factory()->count(2)->create(['jenis_kelamin' => 'P']);

    Livewire::actingAs($admin)
        ->test(Dashboard::class)
        ->assertViewHas('totalPenduduk', 5)
        ->assertViewHas('pendudukLakiLaki', 3)
        ->assertViewHas('pendudukPerempuan', 2);
});

it('menampilkan statistik pengajuan per status', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    PengajuanSurat::factory()->count(2)->berstatus(PengajuanSuratStatus::DIAJUKAN)->create();
    PengajuanSurat::factory()->berstatus(PengajuanSuratStatus::DIPROSES)->create();
    PengajuanSurat::factory()->berstatus(PengajuanSuratStatus::SELESAI)->create();

    $component = Livewire::actingAs($admin)
        ->test(Dashboard::class)
        ->assertViewHas('totalPengajuan', 4);

    $perStatus = $component->viewData('pengajuanPerStatus');

    expect((int) ($perStatus[PengajuanSuratStatus::DIAJUKAN->value] ?? 0))->toBe(2)
        ->and((int) ($perStatus[PengajuanSuratStatus::DIPROSES->value] ?? 0))->toBe(1)
        ->and((int) ($perStatus[PengajuanSuratStatus::SELESAI->value] ?? 0))->toBe(1)
        ->and((int) ($perStatus[PengajuanSuratStatus::REVISI->value] ?? 0))->toBe(0)
        ->and((int) ($perStatus[PengajuanSuratStatus::DITOLAK->value] ?? 0))->toBe(0)
        ->and((int) ($perStatus[PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN->value] ?? 0))->toBe(0);

    $component
        ->assertSee('Diajukan')
        ->assertSee('Revisi')
        ->assertSee('Diproses')
        ->assertSee('Ditolak')
        ->assertSee('Menunggu Tanda Tangan')
        ->assertSee('Selesai');
});

it('menampilkan maksimal lima pengajuan terbaru dengan data penduduk', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $penduduk = Penduduk::factory()->create([
        'nama' => 'Warga Terbaru',
        'nik' => '3200000000000001',
    ]);

    PengajuanSurat::factory()->count(7)->create(['created_at' => now()->subDays(2)]);

    $terbaru = PengajuanSurat::factory()->create([
        'penduduk_id' => $penduduk->id,
        'jenis_surat' => 'domisili',
        'created_at' => now(),
    ]);

    $component = Livewire::actingAs($admin)->test(Dashboard::class);

    $items = $component->viewData('pengajuanTerbaru');

    expect($items)->toHaveCount(5)
        ->and($items->first()->id)->toBe($terbaru->id);

    $component
        ->assertSee('Warga Terbaru')
        ->assertSee('3200000000000001');
});

it('tetap tampil saat database kosong', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(Dashboard::class)
        ->assertViewHas('totalUser', 1)
        ->assertViewHas('totalPenduduk', 0)
        ->assertViewHas('totalPengajuan', 0)
        ->assertSee('Belum ada pengajuan');
});

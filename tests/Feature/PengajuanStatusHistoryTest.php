<?php

use App\Enums\PengajuanSuratStatus;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\User;
use App\Services\PengajuanStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('mencatat riwayat awal untuk pengajuan baru', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    app(PengajuanStatusService::class)->awali($pengajuan, $warga);

    $this->assertDatabaseHas('pengajuan_status_histories', [
        'pengajuan_surat_id' => $pengajuan->id,
        'status_sebelum' => null,
        'status_sesudah' => 'diajukan',
        'user_id' => $warga->id,
    ]);
});

it('menerapkan transisi sah dan mencatat riwayat', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $pengajuan = PengajuanSurat::factory()->create([
        'penduduk_id' => $penduduk->id,
        'status' => PengajuanSuratStatus::DIPROSES->value,
    ]);

    app(PengajuanStatusService::class)->apply(
        $pengajuan,
        PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN,
        $perangkat,
    );

    expect($pengajuan->fresh()->status)
        ->toBe(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN);

    $this->assertDatabaseHas('pengajuan_status_histories', [
        'pengajuan_surat_id' => $pengajuan->id,
        'status_sebelum' => 'diproses',
        'status_sesudah' => 'menunggu_tanda_tangan',
        'user_id' => $perangkat->id,
    ]);
});

it('menolak transisi tidak sah dan tidak mengubah status', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create([
        'penduduk_id' => $penduduk->id,
        'status' => PengajuanSuratStatus::SELESAI->value,
    ]);

    expect(fn () => app(PengajuanStatusService::class)->apply(
        $pengajuan,
        PengajuanSuratStatus::DIAJUKAN,
    ))->toThrow(InvalidArgumentException::class);

    expect($pengajuan->fresh()->status)
        ->toBe(PengajuanSuratStatus::SELESAI);

    $this->assertDatabaseCount('pengajuan_status_histories', 0);
});

it('menyimpan catatan terbaru ke dalam riwayat', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $pengajuan = PengajuanSurat::factory()->create([
        'penduduk_id' => $penduduk->id,
    ]);

    $pengajuan->catatan = 'Alamat tidak sesuai dengan KTP.';

    app(PengajuanStatusService::class)->apply(
        $pengajuan,
        PengajuanSuratStatus::REVISI,
        $perangkat,
    );

    expect($pengajuan->fresh()->catatan)
        ->toBe('Alamat tidak sesuai dengan KTP.');

    $this->assertDatabaseHas('pengajuan_status_histories', [
        'pengajuan_surat_id' => $pengajuan->id,
        'status_sesudah' => 'revisi',
        'catatan' => 'Alamat tidak sesuai dengan KTP.',
    ]);
});

it('menghapus riwayat saat pengajuan dihapus', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    app(PengajuanStatusService::class)->awali($pengajuan, $warga);

    $pengajuan->delete();

    $this->assertDatabaseCount('pengajuan_status_histories', 0);
});

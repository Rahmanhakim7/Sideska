<?php

use App\Enums\PengajuanSuratStatus;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\User;
use App\Notifications\PengajuanStatusNotification;
use App\Services\PengajuanStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;

uses(RefreshDatabase::class);

it('memberi tahu semua perangkat desa saat pengajuan baru diajukan', function () {
    $perangkatSatu = User::factory()->create(['role' => 'perangkat_desa']);
    $perangkatDua = User::factory()->create(['role' => 'perangkat_desa']);
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    Notification::fake();

    app(PengajuanStatusService::class)->kirimNotifikasi(
        $pengajuan,
        null,
        PengajuanSuratStatus::DIAJUKAN,
    );

    Notification::assertSentTo(
        [$perangkatSatu, $perangkatDua],
        PengajuanStatusNotification::class,
    );
    Notification::assertNotSentTo($warga, PengajuanStatusNotification::class);
});

it('memberi tahu perangkat desa saat pengajuan dikirim ulang', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create([
        'penduduk_id' => $penduduk->id,
        'status' => PengajuanSuratStatus::REVISI->value,
    ]);

    Notification::fake();

    app(PengajuanStatusService::class)->kirimNotifikasi(
        $pengajuan,
        PengajuanSuratStatus::REVISI,
        PengajuanSuratStatus::DIAJUKAN,
    );

    Notification::assertSentTo($perangkat, PengajuanStatusNotification::class);
});

it('memberi tahu pemohon saat pengajuan diproses', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create([
        'penduduk_id' => $penduduk->id,
        'status' => PengajuanSuratStatus::DIAJUKAN->value,
    ]);

    Notification::fake();

    app(PengajuanStatusService::class)->kirimNotifikasi(
        $pengajuan,
        PengajuanSuratStatus::DIAJUKAN,
        PengajuanSuratStatus::DIPROSES,
    );

    Notification::assertSentTo($warga, PengajuanStatusNotification::class);
});

it('memberi tahu semua kepala desa saat menunggu tanda tangan', function () {
    $kepalaSatu = User::factory()->create(['role' => 'kepala_desa']);
    $kepalaDua = User::factory()->create(['role' => 'kepala_desa']);
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create([
        'penduduk_id' => $penduduk->id,
        'status' => PengajuanSuratStatus::DIPROSES->value,
    ]);

    Notification::fake();

    app(PengajuanStatusService::class)->kirimNotifikasi(
        $pengajuan,
        PengajuanSuratStatus::DIPROSES,
        PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN,
    );

    Notification::assertSentTo(
        [$kepalaSatu, $kepalaDua],
        PengajuanStatusNotification::class,
    );
    Notification::assertNotSentTo($warga, PengajuanStatusNotification::class);
});

it('memberi tahu pemohon saat surat selesai', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create([
        'penduduk_id' => $penduduk->id,
        'status' => PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN->value,
    ]);

    Notification::fake();

    app(PengajuanStatusService::class)->kirimNotifikasi(
        $pengajuan,
        PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN,
        PengajuanSuratStatus::SELESAI,
    );

    Notification::assertSentTo($warga, PengajuanStatusNotification::class);
});

it('menyimpan notifikasi database dengan data pengajuan yang benar', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create([
        'penduduk_id' => $penduduk->id,
        'jenis_surat' => 'domisili',
    ]);

    app(PengajuanStatusService::class)->kirimNotifikasi(
        $pengajuan,
        null,
        PengajuanSuratStatus::DIAJUKAN,
    );

    $notifikasi = $perangkat->fresh()->notifications;

    expect($notifikasi)->toHaveCount(1);

    $data = $notifikasi->first()->data;

    expect($data['pengajuan_id'])->toBe($pengajuan->id)
        ->and($data['jenis_surat'])->toBe('domisili')
        ->and($data['status'])->toBe('diajukan')
        ->and($data['url'])->toBe(
            route('perangkat-desa.pelayanan-surat.detail', $pengajuan)
        );
});

it('tidak melempar error ketika pengiriman notifikasi gagal', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    Notification::shouldReceive('send')
        ->andThrow(new RuntimeException('Gagal mengirim notifikasi.'));

    app(PengajuanStatusService::class)->kirimNotifikasi(
        $pengajuan,
        null,
        PengajuanSuratStatus::DIAJUKAN,
    );

    expect(true)->toBeTrue();
});

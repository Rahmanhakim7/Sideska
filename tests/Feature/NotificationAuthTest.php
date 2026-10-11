<?php

use App\Enums\PengajuanSuratStatus;
use App\Livewire\Notifikasi;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\User;
use App\Notifications\PengajuanStatusNotification;
use App\Services\PengajuanStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function buatNotifikasi(User $penerima, PengajuanSurat $pengajuan): void
{
    $penerima->notify(new PengajuanStatusNotification(
        $pengajuan,
        'Ada pembaruan pengajuan surat.',
        route('masyarakat.surat.detail', $pengajuan),
        PengajuanSuratStatus::DIPROSES,
    ));
}

it('mengarahkan tamu ke halaman login saat membuka notifikasi', function () {
    $this->get(route('notifikasi'))
        ->assertRedirect(route('login'));
});

it('menampilkan halaman notifikasi untuk pengguna terautentikasi', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    Penduduk::factory()->create(['user_id' => $warga->id]);

    $this->actingAs($warga)
        ->get(route('notifikasi'))
        ->assertOk()
        ->assertSee('Notifikasi');
});

it('menandai semua notifikasi sebagai dibaca', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    buatNotifikasi($warga, $pengajuan);
    buatNotifikasi($warga, $pengajuan);

    Livewire::actingAs($warga)
        ->test(Notifikasi::class)
        ->call('tandaiSemuaDibaca');

    expect($warga->fresh()->unreadNotifications)->toHaveCount(0);
});

it('menandai satu notifikasi sebagai dibaca', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    buatNotifikasi($warga, $pengajuan);
    buatNotifikasi($warga, $pengajuan);

    $pertama = $warga->notifications()->oldest()->first();

    Livewire::actingAs($warga)
        ->test(Notifikasi::class)
        ->call('tandaiDibaca', $pertama->id);

    expect($warga->fresh()->unreadNotifications)->toHaveCount(1);
});

it('menghapus notifikasi milik pengguna', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    buatNotifikasi($warga, $pengajuan);

    $notifikasi = $warga->notifications()->first();

    Livewire::actingAs($warga)
        ->test(Notifikasi::class)
        ->call('hapus', $notifikasi->id);

    expect($warga->fresh()->notifications)->toHaveCount(0);
});

it('pemohon dapat membuka halaman detail pengajuan miliknya', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    $this->actingAs($warga)
        ->get(route('masyarakat.surat.detail', $pengajuan))
        ->assertOk()
        ->assertSee('Detail Pengajuan Surat');
});

it('mengembalikan 403 saat membuka detail pengajuan milik orang lain', function () {
    $wargaLain = User::factory()->create(['role' => 'masyarakat']);
    Penduduk::factory()->create(['user_id' => $wargaLain->id]);

    $pemilik = User::factory()->create(['role' => 'masyarakat']);
    $pendudukPemilik = Penduduk::factory()->create(['user_id' => $pemilik->id]);
    $pengajuan = PengajuanSurat::factory()->create([
        'penduduk_id' => $pendudukPemilik->id,
    ]);

    $this->actingAs($wargaLain)
        ->get(route('masyarakat.surat.detail', $pengajuan))
        ->assertForbidden();
});

it('menolak perangkat desa mengakses halaman detail milik pemohon', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $pengajuan = PengajuanSurat::factory()->create();

    $this->actingAs($perangkat)
        ->get(route('masyarakat.surat.detail', $pengajuan))
        ->assertForbidden();
});

it('menampilkan riwayat status pada halaman detail pemohon', function () {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    $service = app(PengajuanStatusService::class);
    $service->awali($pengajuan, $warga);
    $service->apply($pengajuan, PengajuanSuratStatus::DIPROSES, $perangkat);

    $this->actingAs($warga)
        ->get(route('masyarakat.surat.detail', $pengajuan))
        ->assertOk()
        ->assertSee('Riwayat Status')
        ->assertSee('pengajuan dibuat')
        ->assertSee('Diproses');
});

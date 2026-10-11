<?php

use App\Enums\PengajuanSuratStatus;
use App\Livewire\KepalaDesa\DetailPersetujuan;
use App\Livewire\Masyarakat\AjukanSurat\Skck;
use App\Livewire\Masyarakat\DetailPengajuan;
use App\Livewire\Masyarakat\RiwayatPengajuan;
use App\Livewire\PerangkatDesa\BuatSurat;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\User;
use App\Notifications\PengajuanStatusNotification;
use App\Services\PengajuanStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function buatWargaSkck(): array
{
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create([
        'user_id' => $warga->id,
        'rt' => '001',
        'rw' => '002',
    ]);

    return [$warga, $penduduk];
}

it('menyimpan pengajuan SKCK baru dengan jenis dan status yang benar', function () {
    [$warga, $penduduk] = buatWargaSkck();

    Livewire::actingAs($warga)
        ->test(Skck::class)
        ->set('keperluan', 'Melamar pekerjaan')
        ->call('ajukan')
        ->assertHasNoErrors();

    $pengajuan = PengajuanSurat::where('penduduk_id', $penduduk->id)->first();

    expect($pengajuan)->not->toBeNull()
        ->and($pengajuan->jenis_surat)->toBe('skck')
        ->and($pengajuan->keperluan)->toBe('Melamar pekerjaan')
        ->and($pengajuan->status)->toBe(PengajuanSuratStatus::DIAJUKAN);

    $this->assertDatabaseHas('pengajuan_status_histories', [
        'pengajuan_surat_id' => $pengajuan->id,
        'status_sebelum' => null,
        'status_sesudah' => 'diajukan',
        'user_id' => $warga->id,
    ]);
});

it('mewajibkan keperluan saat mengajukan SKCK', function () {
    [$warga] = buatWargaSkck();

    Livewire::actingAs($warga)
        ->test(Skck::class)
        ->set('keperluan', '')
        ->call('ajukan')
        ->assertHasErrors(['keperluan' => 'required']);

    expect(PengajuanSurat::count())->toBe(0);
});

it('membuka halaman pengajuan SKCK untuk warga yang login', function () {
    [$warga] = buatWargaSkck();

    $this->actingAs($warga)
        ->get(route('masyarakat.ajukan-surat.skck'))
        ->assertOk();
});

it('mengirim ulang pengajuan SKCK yang berstatus revisi', function () {
    [$warga, $penduduk] = buatWargaSkck();

    $pengajuan = PengajuanSurat::factory()
        ->jenis('skck')
        ->berstatus(PengajuanSuratStatus::REVISI)
        ->create([
            'penduduk_id' => $penduduk->id,
            'keperluan' => 'Keperluan lama',
        ]);

    Livewire::actingAs($warga)
        ->test(Skck::class, ['pengajuan' => $pengajuan])
        ->assertSet('modeRevisi', true)
        ->set('keperluan', 'Melanjutkan sekolah')
        ->call('ajukan')
        ->assertHasNoErrors();

    $pengajuan->refresh();

    expect($pengajuan->keperluan)->toBe('Melanjutkan sekolah')
        ->and($pengajuan->jenis_surat)->toBe('skck')
        ->and($pengajuan->status)->toBe(PengajuanSuratStatus::DIAJUKAN);
});

it('menolak membuka mode revisi untuk pengajuan berjenis selain SKCK', function () {
    [$warga, $penduduk] = buatWargaSkck();

    $pengajuan = PengajuanSurat::factory()
        ->jenis('domisili')
        ->berstatus(PengajuanSuratStatus::REVISI)
        ->create(['penduduk_id' => $penduduk->id]);

    Livewire::actingAs($warga)
        ->test(Skck::class, ['pengajuan' => $pengajuan])
        ->assertNotFound();
});

it('menampilkan identitas penduduk dan keperluan pada template PDF SKCK', function () {
    [$warga, $penduduk] = buatWargaSkck();

    $pengajuan = PengajuanSurat::factory()
        ->jenis('skck')
        ->berstatus(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN)
        ->create([
            'penduduk_id' => $penduduk->id,
            'keperluan' => 'Persyaratan administrasi',
            'nomor_surat' => '470/001/DS/2026',
        ]);

    $html = view('surat.skck', ['pengajuan' => $pengajuan->load('penduduk')])->render();

    expect($html)
        ->toContain('SURAT PENGANTAR SKCK')
        ->toContain('470/001/DS/2026')
        ->toContain($penduduk->nama)
        ->toContain($penduduk->nik)
        ->toContain($penduduk->pekerjaan)
        ->toContain('Persyaratan administrasi');
});

it('membuat surat SKCK, menyimpan nomor dan PDF ke disk b2, lalu mengubah status', function () {
    Storage::fake('b2');

    [, $penduduk] = buatWargaSkck();
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    $pengajuan = PengajuanSurat::factory()
        ->jenis('skck')
        ->berstatus(PengajuanSuratStatus::DIPROSES)
        ->create(['penduduk_id' => $penduduk->id]);

    Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuan])
        ->set('modePenomoran', 'otomatis')
        ->set('data.tanggal_surat', '2026-10-26')
        ->call('simpan')
        ->assertHasNoErrors();

    $pengajuan->refresh();

    expect($pengajuan->nomor_surat)->toMatch('/^470\/\d{3}\/DS\/2026$/')
        ->and($pengajuan->status)->toBe(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN)
        ->and($pengajuan->file_pdf)->toStartWith('surat/skck-');

    Storage::disk('b2')->assertExists($pengajuan->file_pdf);
});

it('menandatangani surat SKCK dan menyimpan PDF final ke disk b2', function () {
    Storage::fake('b2');

    [, $penduduk] = buatWargaSkck();
    $kepala = User::factory()->create(['role' => 'kepala_desa']);

    $draft = 'surat/skck-1.pdf';
    Storage::disk('b2')->put($draft, '%PDF-1.4 draft');

    $pengajuan = PengajuanSurat::factory()
        ->jenis('skck')
        ->berstatus(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN)
        ->create([
            'penduduk_id' => $penduduk->id,
            'keperluan' => 'Melamar pekerjaan',
            'nomor_surat' => '470/001/DS/2026',
            'tanggal_surat' => '2026-10-26',
            'file_pdf' => $draft,
        ]);

    Livewire::actingAs($kepala)
        ->test(DetailPersetujuan::class, ['pengajuan' => $pengajuan])
        ->call('tandaTangani')
        ->assertHasNoErrors();

    $pengajuan->refresh();

    expect($pengajuan->status)->toBe(PengajuanSuratStatus::SELESAI)
        ->and($pengajuan->file_pdf)->not->toBe($draft);

    Storage::disk('b2')->assertExists($pengajuan->file_pdf);
});

it('mengarahkan tombol perbaikan pada detail pengajuan SKCK ke form SKCK', function () {
    [$warga, $penduduk] = buatWargaSkck();

    $pengajuan = PengajuanSurat::factory()
        ->jenis('skck')
        ->berstatus(PengajuanSuratStatus::REVISI)
        ->create(['penduduk_id' => $penduduk->id]);

    $component = Livewire::actingAs($warga)
        ->test(DetailPengajuan::class, ['pengajuan' => $pengajuan]);

    expect($component->instance()->routePerbaikan())->toBe(
        route('masyarakat.ajukan-surat.skck', ['pengajuan' => $pengajuan->id])
    );
});

it('mengarahkan tombol perbaikan pada riwayat pengajuan SKCK ke form SKCK', function () {
    [$warga, $penduduk] = buatWargaSkck();

    $pengajuan = PengajuanSurat::factory()
        ->jenis('skck')
        ->berstatus(PengajuanSuratStatus::REVISI)
        ->create(['penduduk_id' => $penduduk->id]);

    $component = Livewire::actingAs($warga)->test(RiwayatPengajuan::class);

    expect($component->instance()->routePerbaikan($pengajuan))->toBe(
        route('masyarakat.ajukan-surat.skck', ['pengajuan' => $pengajuan->id])
    );
});

it('mengarahkan notifikasi revisi SKCK ke form SKCK, bukan halaman notifikasi', function () {
    [$warga, $penduduk] = buatWargaSkck();

    $pengajuan = PengajuanSurat::factory()
        ->jenis('skck')
        ->berstatus(PengajuanSuratStatus::DIAJUKAN)
        ->create(['penduduk_id' => $penduduk->id]);

    Notification::fake();

    app(PengajuanStatusService::class)->kirimNotifikasi(
        $pengajuan,
        PengajuanSuratStatus::DIAJUKAN,
        PengajuanSuratStatus::REVISI,
    );

    Notification::assertSentTo(
        $warga,
        PengajuanStatusNotification::class,
        function (PengajuanStatusNotification $notification): bool {
            return $notification->url === route('masyarakat.ajukan-surat.skck', [
                'pengajuan' => $notification->pengajuan->id,
            ]);
        }
    );
});

it('menerapkan aturan akses PDF yang sama untuk surat SKCK', function () {
    Storage::fake('b2');

    [$warga, $penduduk] = buatWargaSkck();

    $selesai = PengajuanSurat::factory()
        ->jenis('skck')
        ->berstatus(PengajuanSuratStatus::SELESAI)
        ->create([
            'penduduk_id' => $penduduk->id,
            'file_pdf' => 'surat/skck-selesai.pdf',
        ]);
    Storage::disk('b2')->put($selesai->file_pdf, '%PDF-1.4');

    $this->actingAs($warga)
        ->get(route('perangkat-desa.pelayanan-surat.pdf', $selesai))
        ->assertOk();

    $revisi = PengajuanSurat::factory()
        ->jenis('skck')
        ->berstatus(PengajuanSuratStatus::REVISI)
        ->create([
            'penduduk_id' => $penduduk->id,
            'file_pdf' => 'surat/skck-revisi.pdf',
        ]);
    Storage::disk('b2')->put($revisi->file_pdf, '%PDF-1.4');

    $this->actingAs($warga)
        ->get(route('perangkat-desa.pelayanan-surat.pdf', $revisi))
        ->assertForbidden();

    $wargaLain = User::factory()->create(['role' => 'masyarakat']);
    Penduduk::factory()->create(['user_id' => $wargaLain->id]);

    $this->actingAs($wargaLain)
        ->get(route('perangkat-desa.pelayanan-surat.pdf', $selesai))
        ->assertForbidden();
});

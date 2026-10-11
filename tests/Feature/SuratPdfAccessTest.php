<?php

use App\Enums\PengajuanSuratStatus;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function buatWargaDenganPengajuan(
    PengajuanSuratStatus $status,
    ?string $filePdf,
): array {
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->berstatus($status)->create([
        'penduduk_id' => $penduduk->id,
        'file_pdf' => $filePdf,
    ]);

    return [$warga, $penduduk, $pengajuan];
}

function isiFilePdf(string $path): void
{
    Storage::fake('b2');
    Storage::disk('b2')->put($path, '%PDF-1.4 dokumen fiktif');
}

it('menampilkan PDF pengajuan milik masyarakat saat status selesai', function () {
    [$warga, , $pengajuan] = buatWargaDenganPengajuan(
        PengajuanSuratStatus::SELESAI,
        'surat/domisili/1.pdf',
    );
    isiFilePdf($pengajuan->file_pdf);

    $this->actingAs($warga)
        ->get(route('perangkat-desa.pelayanan-surat.pdf', $pengajuan))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/pdf')
        ->assertHeader('Content-Disposition', 'inline; filename="1.pdf"');
});

it('mengunduh PDF pengajuan milik masyarakat saat status selesai', function () {
    [$warga, , $pengajuan] = buatWargaDenganPengajuan(
        PengajuanSuratStatus::SELESAI,
        'surat/domisili/1.pdf',
    );
    isiFilePdf($pengajuan->file_pdf);

    $this->actingAs($warga)
        ->get(route('perangkat-desa.pelayanan-surat.pdf.download', $pengajuan))
        ->assertOk()
        ->assertHeader('Content-Type', 'application/pdf')
        ->assertHeader('Content-Disposition', 'attachment; filename="1.pdf"');
});

it('menolak masyarakat melihat atau mengunduh PDF miliknya saat status belum selesai', function (PengajuanSuratStatus $status) {
    [$warga, , $pengajuan] = buatWargaDenganPengajuan($status, 'surat/domisili/1.pdf');
    isiFilePdf($pengajuan->file_pdf);

    $this->actingAs($warga)
        ->get(route('perangkat-desa.pelayanan-surat.pdf', $pengajuan))
        ->assertForbidden();

    $this->actingAs($warga)
        ->get(route('perangkat-desa.pelayanan-surat.pdf.download', $pengajuan))
        ->assertForbidden();
})->with([
    'diajukan' => PengajuanSuratStatus::DIAJUKAN,
    'revisi' => PengajuanSuratStatus::REVISI,
    'diproses' => PengajuanSuratStatus::DIPROSES,
    'ditolak' => PengajuanSuratStatus::DITOLAK,
    'menunggu tanda tangan' => PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN,
]);

it('menolak masyarakat melihat PDF saat status selesai tetapi file tidak tersedia', function (?string $filePdf) {
    [$warga, , $pengajuan] = buatWargaDenganPengajuan(
        PengajuanSuratStatus::SELESAI,
        $filePdf,
    );

    $this->actingAs($warga)
        ->get(route('perangkat-desa.pelayanan-surat.pdf', $pengajuan))
        ->assertNotFound();
})->with([
    'null' => null,
    'kosong' => '',
]);

it('menolak masyarakat melihat atau mengunduh PDF milik orang lain meski surat selesai', function () {
    $wargaPemilik = User::factory()->create(['role' => 'masyarakat']);
    $pendudukPemilik = Penduduk::factory()->create(['user_id' => $wargaPemilik->id]);
    $pengajuan = PengajuanSurat::factory()
        ->berstatus(PengajuanSuratStatus::SELESAI)
        ->create([
            'penduduk_id' => $pendudukPemilik->id,
            'file_pdf' => 'surat/sktm/2.pdf',
        ]);
    isiFilePdf($pengajuan->file_pdf);

    $wargaLain = User::factory()->create(['role' => 'masyarakat']);
    Penduduk::factory()->create(['user_id' => $wargaLain->id]);

    $this->actingAs($wargaLain)
        ->get(route('perangkat-desa.pelayanan-surat.pdf', $pengajuan))
        ->assertForbidden();

    $this->actingAs($wargaLain)
        ->get(route('perangkat-desa.pelayanan-surat.pdf.download', $pengajuan))
        ->assertForbidden();
});

it('mengarahkan tamu yang belum login ke halaman login saat membuka PDF', function () {
    $pengajuan = PengajuanSurat::factory()->create();

    $this->get(route('perangkat-desa.pelayanan-surat.pdf', $pengajuan))
        ->assertRedirect(route('login'));
});

it('tetap memberi akses PDF kepada perangkat desa sesuai perilaku sebelumnya', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $pengajuan = PengajuanSurat::factory()
        ->berstatus(PengajuanSuratStatus::DIPROSES)
        ->create(['file_pdf' => 'surat/domisili/3.pdf']);
    isiFilePdf($pengajuan->file_pdf);

    $this->actingAs($perangkat)
        ->get(route('perangkat-desa.pelayanan-surat.pdf', $pengajuan))
        ->assertOk();
});

it('tetap memberi akses PDF kepada kepala desa sesuai perilaku sebelumnya', function () {
    $kepala = User::factory()->create(['role' => 'kepala_desa']);
    $pengajuan = PengajuanSurat::factory()
        ->berstatus(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN)
        ->create(['file_pdf' => 'surat/domisili/4.pdf']);
    isiFilePdf($pengajuan->file_pdf);

    $this->actingAs($kepala)
        ->get(route('perangkat-desa.pelayanan-surat.pdf', $pengajuan))
        ->assertOk();
});

<?php

use App\Enums\PengajuanSuratStatus;
use App\Livewire\PerangkatDesa\BuatSurat;
use App\Models\Penduduk;
use App\Models\PengajuanSurat;
use App\Models\User;
use App\Services\NomorSuratService;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function buatPengajuanDiproses(): PengajuanSurat
{
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    $pengajuan = PengajuanSurat::factory()
        ->jenis('domisili')
        ->berstatus(PengajuanSuratStatus::DIPROSES)
        ->create(['penduduk_id' => $penduduk->id]);

    return $pengajuan;
}

it('meneruskan urutan nomor kanonik dan mengabaikan nomor berformat lain', function () {
    $penduduk = Penduduk::factory()->create();

    collect([
        '470/001/DS/2026',
        '470/005/DS/2026',
        '470/007/DS/2026',
        '470/003/DS/2025',
        '470/005/SSK/IX/2026',
        null,
    ])->each(function (?string $nomor) use ($penduduk) {
        PengajuanSurat::factory()->create([
            'penduduk_id' => $penduduk->id,
            'status' => PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN->value,
            'nomor_surat' => $nomor,
        ]);
    });

    $service = app(NomorSuratService::class);

    expect($service->generate(2026))->toBe('470/008/DS/2026');

    PengajuanSurat::factory()->create([
        'penduduk_id' => $penduduk->id,
        'status' => PengajuanSuratStatus::DIPROSES->value,
        'nomor_surat' => '470/008/DS/2026',
    ]);

    expect($service->generate(2026))->toBe('470/009/DS/2026');
});

it('menghasilkan nomor otomatis yang tersimpan dan tercetak di surat', function () {
    Storage::fake('b2');

    $pengajuan = buatPengajuanDiproses();
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuan])
        ->set('modePenomoran', 'otomatis')
        ->assertSet('data.nomor_surat', '')
        ->set('data.tanggal_surat', '2026-10-26')
        ->call('simpan')
        ->assertHasNoErrors();

    $pengajuan->refresh();

    expect($pengajuan->nomor_surat)->toMatch('/^470\/\d{3}\/DS\/2026$/')
        ->and($pengajuan->status)->toBe(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN)
        ->and($pengajuan->tanggal_surat->format('Y-m-d'))->toBe('2026-10-26');

    Storage::disk('b2')->assertExists($pengajuan->file_pdf);

    $dataSktm = $pengajuan->data->pluck('value', 'field');

    $html = view('surat.domisili', [
        'pengajuan' => $pengajuan,
        'dataSktm' => $dataSktm,
    ])->render();

    expect($html)->toContain($pengajuan->nomor_surat);
});

it('memberi nomor berbeda untuk dua pengajuan yang dibuat berurutan', function () {
    Storage::fake('b2');

    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $pengajuanPertama = buatPengajuanDiproses();
    $pengajuanKedua = buatPengajuanDiproses();

    Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuanPertama])
        ->set('modePenomoran', 'otomatis')
        ->set('data.tanggal_surat', '2026-10-26')
        ->call('simpan')
        ->assertHasNoErrors();

    Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuanKedua])
        ->set('modePenomoran', 'otomatis')
        ->set('data.tanggal_surat', '2026-10-26')
        ->call('simpan')
        ->assertHasNoErrors();

    $pengajuanPertama->refresh();
    $pengajuanKedua->refresh();

    expect($pengajuanPertama->nomor_surat)
        ->toBe('470/001/DS/2026')
        ->and($pengajuanKedua->nomor_surat)
        ->toBe('470/002/DS/2026')
        ->not->toBe($pengajuanPertama->nomor_surat);
});

it('tetap dapat menyimpan surat dengan nomor manual', function () {
    Storage::fake('b2');

    $pengajuan = buatPengajuanDiproses();
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuan])
        ->set('modePenomoran', 'manual')
        ->set('data.nomor_surat', '470/100/DS/2026')
        ->set('data.tanggal_surat', '2026-10-26')
        ->call('simpan')
        ->assertHasNoErrors();

    $pengajuan->refresh();

    expect($pengajuan->nomor_surat)->toBe('470/100/DS/2026')
        ->and($pengajuan->status)->toBe(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN);

    Storage::disk('b2')->assertExists($pengajuan->file_pdf);
});

it('menolak nomor manual yang sudah digunakan pengajuan lain', function () {
    Storage::fake('b2');

    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $pengajuanPertama = buatPengajuanDiproses();
    $pengajuanKedua = buatPengajuanDiproses();

    $pengajuanPertama->update([
        'nomor_surat' => '470/300/DS/2026',
    ]);

    Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuanKedua])
        ->set('modePenomoran', 'manual')
        ->set('data.nomor_surat', '470/300/DS/2026')
        ->set('data.tanggal_surat', '2026-10-26')
        ->call('simpan')
        ->assertHasErrors(['data.nomor_surat']);

    expect($pengajuanKedua->fresh()->status)
        ->toBe(PengajuanSuratStatus::DIPROSES);

    Storage::disk('b2')->assertDirectoryEmpty('surat');
});

it('menampilkan nomor pratinjau di field saat metode otomatis dipilih', function () {
    $pengajuan = buatPengajuanDiproses();
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    $component = Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuan])
        ->set('data.tanggal_surat', '2026-10-26')
        ->set('modePenomoran', 'otomatis')
        ->assertSet('data.nomor_surat', '470/001/DS/2026');

    $autoHtml = $component->html();
    preg_match('/<input[^>]*id="nomor_surat"[^>]*>/', $autoHtml, $autoInput);

    expect($autoInput[0] ?? '')->toContain('value="470/001/DS/2026"');
});

it('memperbarui nomor pratinjau mengikuti tahun pada tanggal surat', function () {
    $penduduk = Penduduk::factory()->create();

    foreach (['470/001/DS/2025', '470/002/DS/2025'] as $nomor) {
        PengajuanSurat::factory()->create([
            'penduduk_id' => $penduduk->id,
            'status' => PengajuanSuratStatus::DIPROSES->value,
            'nomor_surat' => $nomor,
        ]);
    }

    PengajuanSurat::factory()->create([
        'penduduk_id' => $penduduk->id,
        'status' => PengajuanSuratStatus::DIPROSES->value,
        'nomor_surat' => '470/001/DS/2026',
    ]);

    $pengajuan = buatPengajuanDiproses();
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuan])
        ->set('modePenomoran', 'otomatis')
        ->set('data.tanggal_surat', '2025-05-10')
        ->assertSet('data.nomor_surat', '470/003/DS/2025')
        ->set('data.tanggal_surat', '2026-05-10')
        ->assertSet('data.nomor_surat', '470/002/DS/2026');
});

it('menandai field nomor surat readonly saat otomatis dan dapat diedit saat manual', function () {
    $pengajuan = buatPengajuanDiproses();
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    $component = Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuan])
        ->set('data.tanggal_surat', '2026-10-26');

    $manualHtml = $component->html();
    preg_match('/<input[^>]*id="nomor_surat"[^>]*>/', $manualHtml, $manualInput);
    preg_match('/<input[^>]*id="tanggal_surat"[^>]*>/', $manualHtml, $tanggalInput);

    expect($manualInput[0] ?? '')->toContain('id="nomor_surat"');
    expect($manualInput[0] ?? '')->not->toContain('readonly');
    expect($tanggalInput[0] ?? '')->toContain('value="2026-10-26"');

    $component->set('modePenomoran', 'otomatis');

    $autoHtml = $component->html();
    preg_match('/<input[^>]*id="nomor_surat"[^>]*>/', $autoHtml, $autoInput);

    expect($autoInput[0] ?? '')->toContain('readonly')
        ->and($autoInput[0] ?? '')->toContain('value="470/001/DS/2026"');
});

it('mengembalikan nomor manual yang diketik setelah berpindah dari otomatis', function () {
    $pengajuan = buatPengajuanDiproses();
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuan])
        ->set('data.nomor_surat', '470/999/DS/2026')
        ->set('modePenomoran', 'otomatis')
        ->set('modePenomoran', 'manual')
        ->assertSet('data.nomor_surat', '470/999/DS/2026');
});

it('database menolak nomor kanonik duplikat', function () {
    $pengajuan = buatPengajuanDiproses();
    $pengajuan->update(['nomor_surat' => '470/001/DS/2026']);

    expect(fn () => PengajuanSurat::factory()->create([
        'status' => PengajuanSuratStatus::DIPROSES->value,
        'nomor_surat' => '470/001/DS/2026',
    ]))->toThrow(QueryException::class);
});

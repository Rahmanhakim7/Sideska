<?php

use App\Enums\PengajuanSuratStatus;
use App\Livewire\KepalaDesa\DetailPersetujuan;
use App\Livewire\Masyarakat\AjukanSurat\KeteranganPindah;
use App\Livewire\Masyarakat\DetailPengajuan;
use App\Livewire\Masyarakat\RiwayatPengajuan;
use App\Livewire\PerangkatDesa\BuatSurat;
use App\Livewire\PerangkatDesa\DetailPelayananSurat;
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

function buatWargaPindah(): array
{
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create([
        'user_id' => $warga->id,
        'rt' => '001',
        'rw' => '002',
    ]);

    return [$warga, $penduduk];
}

it('menyimpan pengajuan keterangan pindah baru dengan jenis dan status yang benar', function () {
    [$warga, $penduduk] = buatWargaPindah();

    Livewire::actingAs($warga)
        ->test(KeteranganPindah::class)
        ->set('alasanPindah', 'Pekerjaan')
        ->set('alamatTujuan', 'Jl. Merdeka No. 10')
        ->set('desaKelurahanTujuan', 'Sukamaju')
        ->set('kecamatanTujuan', 'Banyuasin')
        ->set('kabupatenTujuan', 'Purworejo')
        ->set('provinsiTujuan', 'Jawa Tengah')
        ->set('keperluan', 'Administrasi kependudukan')
        ->call('ajukan')
        ->assertHasNoErrors();

    $pengajuan = PengajuanSurat::where('penduduk_id', $penduduk->id)->first();

    expect($pengajuan)->not->toBeNull()
        ->and($pengajuan->jenis_surat)->toBe('keterangan_pindah')
        ->and($pengajuan->keperluan)->toBe('Administrasi kependudukan')
        ->and($pengajuan->status)->toBe(PengajuanSuratStatus::DIAJUKAN);

    $this->assertDatabaseHas('pengajuan_status_histories', [
        'pengajuan_surat_id' => $pengajuan->id,
        'status_sebelum' => null,
        'status_sesudah' => 'diajukan',
        'user_id' => $warga->id,
    ]);
});

it('mewajibkan data perpindahan dan keperluan saat mengajukan', function () {
    [$warga] = buatWargaPindah();

    Livewire::actingAs($warga)
        ->test(KeteranganPindah::class)
        ->set('alasanPindah', '')
        ->set('alamatTujuan', '')
        ->set('desaKelurahanTujuan', '')
        ->set('kecamatanTujuan', '')
        ->set('kabupatenTujuan', '')
        ->set('provinsiTujuan', '')
        ->set('keperluan', '')
        ->call('ajukan')
        ->assertHasErrors([
            'alasanPindah' => 'required',
            'alamatTujuan' => 'required',
            'desaKelurahanTujuan' => 'required',
            'kecamatanTujuan' => 'required',
            'kabupatenTujuan' => 'required',
            'provinsiTujuan' => 'required',
            'keperluan' => 'required',
        ]);

    expect(PengajuanSurat::count())->toBe(0);
});

it('membuka halaman pengajuan keterangan pindah untuk warga yang login', function () {
    [$warga] = buatWargaPindah();

    $this->actingAs($warga)
        ->get(route('masyarakat.ajukan-surat.keterangan-pindah'))
        ->assertOk();
});

it('menyimpan detail data perpindahan ke pengajuan_surat_data', function () {
    [$warga, $penduduk] = buatWargaPindah();

    Livewire::actingAs($warga)
        ->test(KeteranganPindah::class)
        ->set('alasanPindah', 'Pekerjaan')
        ->set('alamatTujuan', 'Jl. Merdeka No. 10')
        ->set('rtTujuan', '003')
        ->set('rwTujuan', '004')
        ->set('desaKelurahanTujuan', 'Sukamaju')
        ->set('kecamatanTujuan', 'Banyuasin')
        ->set('kabupatenTujuan', 'Purworejo')
        ->set('provinsiTujuan', 'Jawa Tengah')
        ->set('klasifikasiPindah', 'Kepala Keluarga')
        ->set('jumlahAnggotaPindah', '4')
        ->set('tanggalPindah', '2026-11-01')
        ->set('keperluan', 'Keperluan administrasi')
        ->call('ajukan')
        ->assertHasNoErrors();

    $pengajuan = PengajuanSurat::where('penduduk_id', $penduduk->id)->firstOrFail();

    $this->assertDatabaseHas('pengajuan_surat_data', [
        'pengajuan_surat_id' => $pengajuan->id,
        'field' => 'alasan_pindah',
        'value' => 'Pekerjaan',
    ]);

    $this->assertDatabaseHas('pengajuan_surat_data', [
        'pengajuan_surat_id' => $pengajuan->id,
        'field' => 'alamat_tujuan',
        'value' => 'Jl. Merdeka No. 10',
    ]);

    $this->assertDatabaseHas('pengajuan_surat_data', [
        'pengajuan_surat_id' => $pengajuan->id,
        'field' => 'desa_kelurahan_tujuan',
        'value' => 'Sukamaju',
    ]);

    $this->assertDatabaseHas('pengajuan_surat_data', [
        'pengajuan_surat_id' => $pengajuan->id,
        'field' => 'klasifikasi_pindah',
        'value' => 'Kepala Keluarga',
    ]);

    $this->assertDatabaseHas('pengajuan_surat_data', [
        'pengajuan_surat_id' => $pengajuan->id,
        'field' => 'jumlah_anggota_pindah',
        'value' => '4',
    ]);

    $this->assertDatabaseHas('pengajuan_surat_data', [
        'pengajuan_surat_id' => $pengajuan->id,
        'field' => 'tanggal_pindah',
        'value' => '2026-11-01',
    ]);
});

it('memuat data perpindahan saat membuka mode revisi', function () {
    [$warga, $penduduk] = buatWargaPindah();

    $pengajuan = PengajuanSurat::factory()
        ->jenis('keterangan_pindah')
        ->berstatus(PengajuanSuratStatus::REVISI)
        ->create([
            'penduduk_id' => $penduduk->id,
            'keperluan' => 'Keperluan lama',
        ]);

    $pengajuan->data()->createMany([
        ['field' => 'alasan_pindah', 'value' => 'Pendidikan'],
        ['field' => 'alamat_tujuan', 'value' => 'Jl. Lama No. 1'],
        ['field' => 'desa_kelurahan_tujuan', 'value' => 'DesaLama'],
        ['field' => 'kecamatan_tujuan', 'value' => 'KecLama'],
        ['field' => 'kabupaten_tujuan', 'value' => 'KabLama'],
        ['field' => 'provinsi_tujuan', 'value' => 'ProvLama'],
        ['field' => 'klasifikasi_pindah', 'value' => 'Anggota Keluarga'],
        ['field' => 'jumlah_anggota_pindah', 'value' => '2'],
        ['field' => 'tanggal_pindah', 'value' => '2026-12-01'],
    ]);

    Livewire::actingAs($warga)
        ->test(KeteranganPindah::class, ['pengajuan' => $pengajuan])
        ->assertSet('modeRevisi', true)
        ->assertSet('keperluan', 'Keperluan lama')
        ->assertSet('alasanPindah', 'Pendidikan')
        ->assertSet('alamatTujuan', 'Jl. Lama No. 1')
        ->assertSet('desaKelurahanTujuan', 'DesaLama')
        ->assertSet('kecamatanTujuan', 'KecLama')
        ->assertSet('kabupatenTujuan', 'KabLama')
        ->assertSet('provinsiTujuan', 'ProvLama')
        ->assertSet('klasifikasiPindah', 'Anggota Keluarga')
        ->assertSet('jumlahAnggotaPindah', '2')
        ->assertSet('tanggalPindah', '2026-12-01');
});

it('mengirim ulang pengajuan keterangan pindah yang berstatus revisi', function () {
    [$warga, $penduduk] = buatWargaPindah();

    $pengajuan = PengajuanSurat::factory()
        ->jenis('keterangan_pindah')
        ->berstatus(PengajuanSuratStatus::REVISI)
        ->create([
            'penduduk_id' => $penduduk->id,
            'keperluan' => 'Keperluan lama',
        ]);

    $pengajuan->data()->create([
        'field' => 'alasan_pindah',
        'value' => 'Alasan lama',
    ]);

    Livewire::actingAs($warga)
        ->test(KeteranganPindah::class, ['pengajuan' => $pengajuan])
        ->set('alasanPindah', 'Alasan baru')
        ->set('alamatTujuan', 'Jl. Baru No. 9')
        ->set('desaKelurahanTujuan', 'DesaBaru')
        ->set('kecamatanTujuan', 'KecBaru')
        ->set('kabupatenTujuan', 'KabBaru')
        ->set('provinsiTujuan', 'ProvBaru')
        ->set('keperluan', 'Keperluan baru')
        ->call('ajukan')
        ->assertHasNoErrors();

    $pengajuan->refresh();

    expect($pengajuan->keperluan)->toBe('Keperluan baru')
        ->and($pengajuan->jenis_surat)->toBe('keterangan_pindah')
        ->and($pengajuan->status)->toBe(PengajuanSuratStatus::DIAJUKAN)
        ->and(PengajuanSurat::where('penduduk_id', $penduduk->id)->count())->toBe(1);

    $this->assertDatabaseHas('pengajuan_surat_data', [
        'pengajuan_surat_id' => $pengajuan->id,
        'field' => 'alasan_pindah',
        'value' => 'Alasan baru',
    ]);

    $this->assertDatabaseHas('pengajuan_surat_data', [
        'pengajuan_surat_id' => $pengajuan->id,
        'field' => 'alamat_tujuan',
        'value' => 'Jl. Baru No. 9',
    ]);
});

it('menolak warga lain membuka revisi pengajuan keterangan pindah milik orang lain', function () {
    [, $penduduk] = buatWargaPindah();

    $pengajuan = PengajuanSurat::factory()
        ->jenis('keterangan_pindah')
        ->berstatus(PengajuanSuratStatus::REVISI)
        ->create(['penduduk_id' => $penduduk->id]);

    $wargaLain = User::factory()->create(['role' => 'masyarakat']);
    Penduduk::factory()->create(['user_id' => $wargaLain->id]);

    Livewire::actingAs($wargaLain)
        ->test(KeteranganPindah::class, ['pengajuan' => $pengajuan])
        ->assertForbidden();
});

/**
 * @return array{0: User, 1: Penduduk, 2: PengajuanSurat}
 */
function buatPengajuanPindahUntukPerangkat(PengajuanSuratStatus $status = PengajuanSuratStatus::DIPROSES): array
{
    [, $penduduk] = buatWargaPindah();
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    $pengajuan = PengajuanSurat::factory()
        ->jenis('keterangan_pindah')
        ->berstatus($status)
        ->create([
            'penduduk_id' => $penduduk->id,
            'keperluan' => 'Administrasi kependudukan',
        ]);

    return [$perangkat, $penduduk, $pengajuan];
}

it('menyediakan field nomor dan tanggal surat untuk pengajuan keterangan pindah', function () {
    [$perangkat, , $pengajuan] = buatPengajuanPindahUntukPerangkat();

    $component = Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuan]);

    $namaField = collect($component->get('fields'))->pluck('name');

    expect($namaField->contains('nomor_surat'))->toBeTrue()
        ->and($namaField->contains('tanggal_surat'))->toBeTrue();
});

it('memetakan keterangan pindah ke template surat.keterangan-pindah', function () {
    expect(view()->exists('surat.keterangan-pindah'))->toBeTrue();

    [$perangkat, , $pengajuan] = buatPengajuanPindahUntukPerangkat();

    Storage::fake('b2');

    Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuan])
        ->set('modePenomoran', 'otomatis')
        ->set('data.tanggal_surat', '2026-10-26')
        ->call('simpan')
        ->assertHasNoErrors();
});

it('membuat surat keterangan pindah, menyimpan nomor dan PDF ke disk b2, lalu mengubah status', function () {
    Storage::fake('b2');

    [$perangkat, , $pengajuan] = buatPengajuanPindahUntukPerangkat();

    $pengajuan->data()->createMany([
        ['field' => 'alasan_pindah', 'value' => 'Pekerjaan'],
        ['field' => 'alamat_tujuan', 'value' => 'Jl. Merdeka No. 10'],
        ['field' => 'rt_tujuan', 'value' => '003'],
        ['field' => 'rw_tujuan', 'value' => '004'],
        ['field' => 'desa_kelurahan_tujuan', 'value' => 'Sukamaju'],
        ['field' => 'kecamatan_tujuan', 'value' => 'Banyuasin'],
        ['field' => 'kabupaten_tujuan', 'value' => 'Purworejo'],
        ['field' => 'provinsi_tujuan', 'value' => 'Jawa Tengah'],
        ['field' => 'klasifikasi_pindah', 'value' => 'Kepala Keluarga'],
        ['field' => 'jumlah_anggota_pindah', 'value' => '4'],
        ['field' => 'tanggal_pindah', 'value' => '2026-11-01'],
    ]);

    Livewire::actingAs($perangkat)
        ->test(BuatSurat::class, ['pengajuan' => $pengajuan])
        ->set('modePenomoran', 'otomatis')
        ->set('data.tanggal_surat', '2026-10-26')
        ->call('simpan')
        ->assertHasNoErrors();

    $pengajuan->refresh();

    expect($pengajuan->nomor_surat)->toMatch('/^470\/\d{3}\/DS\/2026$/')
        ->and($pengajuan->status)->toBe(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN)
        ->and($pengajuan->file_pdf)->toStartWith('surat/keterangan_pindah-');

    Storage::disk('b2')->assertExists($pengajuan->file_pdf);
});

it('menampilkan identitas penduduk dan data perpindahan pada template PDF keterangan pindah', function () {
    [, $penduduk, $pengajuan] = buatPengajuanPindahUntukPerangkat(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN);

    $pengajuan->update(['nomor_surat' => '470/123/DS/2026']);

    $pengajuan->data()->createMany([
        ['field' => 'alasan_pindah', 'value' => 'Pekerjaan'],
        ['field' => 'alamat_tujuan', 'value' => 'Jl. Merdeka No. 10'],
        ['field' => 'rt_tujuan', 'value' => '003'],
        ['field' => 'rw_tujuan', 'value' => '004'],
        ['field' => 'desa_kelurahan_tujuan', 'value' => 'Sukamaju'],
        ['field' => 'kecamatan_tujuan', 'value' => 'Banyuasin'],
        ['field' => 'kabupaten_tujuan', 'value' => 'Purworejo'],
        ['field' => 'provinsi_tujuan', 'value' => 'Jawa Tengah'],
        ['field' => 'klasifikasi_pindah', 'value' => 'Kepala Keluarga'],
        ['field' => 'jumlah_anggota_pindah', 'value' => '4'],
        ['field' => 'tanggal_pindah', 'value' => '2026-11-01'],
    ]);

    $pengajuan->load('penduduk', 'data');

    $html = view('surat.keterangan-pindah', [
        'pengajuan' => $pengajuan,
        'dataSktm' => $pengajuan->data->pluck('value', 'field'),
    ])->render();

    expect($html)->toContain('SURAT KETERANGAN PINDAH')
        ->and($html)->toContain('470/123/DS/2026')
        ->and($html)->toContain($penduduk->nama)
        ->and($html)->toContain($penduduk->nik)
        ->and($html)->toContain('Pekerjaan')
        ->and($html)->toContain('Jl. Merdeka No. 10')
        ->and($html)->toContain('Sukamaju')
        ->and($html)->toContain('Banyuasin')
        ->and($html)->toContain('Jawa Tengah')
        ->and($html)->toContain('003/004')
        ->and($html)->toContain('Kepala Keluarga');
});

it('menampilkan data perpindahan pada detail pelayanan perangkat desa', function () {
    [$perangkat, , $pengajuan] = buatPengajuanPindahUntukPerangkat(PengajuanSuratStatus::DIPROSES);

    $pengajuan->data()->createMany([
        ['field' => 'alasan_pindah', 'value' => 'Pekerjaan'],
        ['field' => 'alamat_tujuan', 'value' => 'Jl. Merdeka No. 10'],
        ['field' => 'rt_tujuan', 'value' => '003'],
        ['field' => 'rw_tujuan', 'value' => '004'],
        ['field' => 'desa_kelurahan_tujuan', 'value' => 'Sukamaju'],
        ['field' => 'kecamatan_tujuan', 'value' => 'Banyuasin'],
        ['field' => 'kabupaten_tujuan', 'value' => 'Purworejo'],
        ['field' => 'provinsi_tujuan', 'value' => 'Jawa Tengah'],
        ['field' => 'klasifikasi_pindah', 'value' => 'Kepala Keluarga'],
        ['field' => 'jumlah_anggota_pindah', 'value' => '4'],
        ['field' => 'tanggal_pindah', 'value' => '2026-11-01'],
    ]);

    Livewire::actingAs($perangkat)
        ->test(DetailPelayananSurat::class, ['pengajuan' => $pengajuan])
        ->assertSee('Data Perpindahan')
        ->assertSee('Pekerjaan')
        ->assertSee('Jl. Merdeka No. 10')
        ->assertSee('Sukamaju')
        ->assertSee('Banyuasin')
        ->assertSee('Jawa Tengah')
        ->assertSee('Kepala Keluarga')
        ->assertSee('Jumlah Anggota Pindah')
        ->assertSee('Tanggal Pindah');
});

function buatKepalaDesa(): User
{
    return User::factory()->create(['role' => 'kepala_desa']);
}

/**
 * @return array<string, string>
 */
function dataPindahLengkap(): array
{
    return [
        'alasan_pindah' => 'Pekerjaan',
        'alamat_tujuan' => 'Jl. Merdeka No. 10',
        'rt_tujuan' => '003',
        'rw_tujuan' => '004',
        'desa_kelurahan_tujuan' => 'Sukamaju',
        'kecamatan_tujuan' => 'Banyuasin',
        'kabupaten_tujuan' => 'Purworejo',
        'provinsi_tujuan' => 'Jawa Tengah',
        'klasifikasi_pindah' => 'Kepala Keluarga',
        'jumlah_anggota_pindah' => '4',
        'tanggal_pindah' => '2026-11-01',
    ];
}

it('memetakan keterangan pindah ke template surat.keterangan-pindah saat penandatanganan', function () {
    Storage::fake('b2');

    expect(view()->exists('surat.keterangan-pindah'))->toBeTrue();

    [, $penduduk] = buatWargaPindah();
    $kepala = buatKepalaDesa();

    $draft = 'surat/keterangan_pindah-1.pdf';
    Storage::disk('b2')->put($draft, '%PDF-1.4 draft');

    $pengajuan = PengajuanSurat::factory()
        ->jenis('keterangan_pindah')
        ->berstatus(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN)
        ->create([
            'penduduk_id' => $penduduk->id,
            'nomor_surat' => '470/123/DS/2026',
            'tanggal_surat' => '2026-10-26',
            'file_pdf' => $draft,
        ]);

    Livewire::actingAs($kepala)
        ->test(DetailPersetujuan::class, ['pengajuan' => $pengajuan])
        ->call('tandaTangani')
        ->assertHasNoErrors();

    $pengajuan->refresh();

    expect($pengajuan->file_pdf)->toStartWith('surat/keterangan_pindah-')
        ->and($pengajuan->file_pdf)->toContain('-ttd-');

    Storage::disk('b2')->assertExists($pengajuan->file_pdf);
});

it('menandatangani surat keterangan pindah dan menyelesaikan pengajuan', function () {
    Storage::fake('b2');

    [, $penduduk] = buatWargaPindah();
    $kepala = buatKepalaDesa();

    $draft = 'surat/keterangan_pindah-1.pdf';
    Storage::disk('b2')->put($draft, '%PDF-1.4 draft');

    $pengajuan = PengajuanSurat::factory()
        ->jenis('keterangan_pindah')
        ->berstatus(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN)
        ->create([
            'penduduk_id' => $penduduk->id,
            'keperluan' => 'Administrasi kependudukan',
            'nomor_surat' => '470/001/DS/2026',
            'tanggal_surat' => '2026-10-26',
            'file_pdf' => $draft,
        ]);

    $pengajuan->data()->createMany(
        collect(dataPindahLengkap())
            ->map(fn (string $value, string $field): array => [
                'field' => $field,
                'value' => $value,
            ])
            ->values()
            ->all()
    );

    Livewire::actingAs($kepala)
        ->test(DetailPersetujuan::class, ['pengajuan' => $pengajuan])
        ->call('tandaTangani')
        ->assertHasNoErrors();

    $pengajuan->refresh();

    expect($pengajuan->status)->toBe(PengajuanSuratStatus::SELESAI)
        ->and($pengajuan->file_pdf)->not->toBe($draft)
        ->and($pengajuan->file_pdf)->toStartWith('surat/keterangan_pindah-')
        ->and($pengajuan->file_pdf)->toContain('-ttd-')
        ->and($pengajuan->tanggal_selesai)->not->toBeNull();

    Storage::disk('b2')->assertExists($pengajuan->file_pdf);
    Storage::disk('b2')->assertMissing($draft);
});

it('menampilkan blok tanda tangan Kepala Desa pada template keterangan pindah yang ditandatangani', function () {
    [, , $pengajuan] = buatPengajuanPindahUntukPerangkat(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN);

    $html = view('surat.keterangan-pindah', [
        'pengajuan' => $pengajuan,
        'dataSktm' => $pengajuan->data->pluck('value', 'field'),
        'sudahDitandatangani' => true,
    ])->render();

    expect($html)->toContain('data:image/png;base64,')
        ->and($html)->toContain('PUJIYANTO')
        ->and($html)->toContain('Kepala Desa Sekartejo');
});

it('menampilkan data perpindahan pada PDF final keterangan pindah yang ditandatangani', function () {
    [, $penduduk, $pengajuan] = buatPengajuanPindahUntukPerangkat(PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN);

    $pengajuan->update(['nomor_surat' => '470/123/DS/2026']);

    $pengajuan->data()->createMany(
        collect(dataPindahLengkap())
            ->map(fn (string $value, string $field): array => [
                'field' => $field,
                'value' => $value,
            ])
            ->values()
            ->all()
    );

    $html = view('surat.keterangan-pindah', [
        'pengajuan' => $pengajuan,
        'dataSktm' => $pengajuan->data->pluck('value', 'field'),
        'sudahDitandatangani' => true,
    ])->render();

    expect($html)->toContain('SURAT KETERANGAN PINDAH')
        ->and($html)->toContain('470/123/DS/2026')
        ->and($html)->toContain($penduduk->nama)
        ->and($html)->toContain($penduduk->nik)
        ->and($html)->toContain('Pekerjaan')
        ->and($html)->toContain('Jl. Merdeka No. 10')
        ->and($html)->toContain('003/004')
        ->and($html)->toContain('Sukamaju')
        ->and($html)->toContain('Banyuasin')
        ->and($html)->toContain('Jawa Tengah')
        ->and($html)->toContain('Kepala Keluarga')
        ->and($html)->toContain('data:image/png;base64,');
});

it('mengarahkan tombol perbaikan pada detail pengajuan keterangan pindah ke form keterangan pindah', function () {
    [$warga, $penduduk] = buatWargaPindah();

    $pengajuan = PengajuanSurat::factory()
        ->jenis('keterangan_pindah')
        ->berstatus(PengajuanSuratStatus::REVISI)
        ->create(['penduduk_id' => $penduduk->id]);

    $component = Livewire::actingAs($warga)
        ->test(DetailPengajuan::class, ['pengajuan' => $pengajuan]);

    expect($component->instance()->routePerbaikan())->toBe(
        route('masyarakat.ajukan-surat.keterangan-pindah', [
            'pengajuan' => $pengajuan->id,
        ])
    );
});

it('mengarahkan tombol perbaikan pada riwayat pengajuan keterangan pindah ke form keterangan pindah', function () {
    [$warga, $penduduk] = buatWargaPindah();

    $pengajuan = PengajuanSurat::factory()
        ->jenis('keterangan_pindah')
        ->berstatus(PengajuanSuratStatus::REVISI)
        ->create(['penduduk_id' => $penduduk->id]);

    $component = Livewire::actingAs($warga)->test(RiwayatPengajuan::class);

    expect($component->instance()->routePerbaikan($pengajuan))->toBe(
        route('masyarakat.ajukan-surat.keterangan-pindah', [
            'pengajuan' => $pengajuan->id,
        ])
    );
});

it('mengarahkan notifikasi revisi keterangan pindah ke form keterangan pindah', function () {
    [$warga, $penduduk] = buatWargaPindah();

    $pengajuan = PengajuanSurat::factory()
        ->jenis('keterangan_pindah')
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
            return $notification->url === route('masyarakat.ajukan-surat.keterangan-pindah', [
                'pengajuan' => $notification->pengajuan->id,
            ]);
        }
    );
});

<?php

use App\Livewire\PerangkatDesa\Penduduk\Create;
use App\Livewire\PerangkatDesa\Penduduk\Edit;
use App\Livewire\PerangkatDesa\Penduduk\Index;
use App\Models\Penduduk;
use App\Models\User;
use App\Services\PendudukFotoService;
use Cloudinary\Api\ApiResponse;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

$cloudinaryUrl = 'https://res.cloudinary.com/kxaypleu/image/upload/v1615/sideska/penduduk/abc123.png';
$publicId = 'sideska/penduduk/abc123';

beforeEach(function () {
    config(['cloudinary.cloud_name' => 'kxaypleu']);
});

afterEach(function () {
    Cloudinary::clearResolvedInstance(CloudinaryEngine::class);
    DB::clearResolvedInstance('db');
    Log::clearResolvedInstance('log');
});

function mockUploadCloudinary(string $url): void
{
    Cloudinary::shouldReceive('upload')->once()->andReturnSelf();
    Cloudinary::shouldReceive('getSecurePath')->once()->andReturn($url);
}

function buatPendudukPerangkat(array $attributes = []): array
{
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);
    $penduduk = Penduduk::factory()->create(array_merge([
        'rt' => '001',
        'rw' => '002',
        'pendidikan' => 'S1',
    ], $attributes));

    return [$penduduk, $perangkat];
}

it('menghasilkan foto_url null saat foto kosong', function () {
    $penduduk = Penduduk::factory()->create(['foto' => null]);

    expect($penduduk->foto_url)->toBeNull();
});

it('meneruskan URL Cloudinary apa adanya lewat foto_url', function () use ($cloudinaryUrl) {
    $penduduk = Penduduk::factory()->create(['foto' => $cloudinaryUrl]);

    expect($penduduk->foto_url)->toBe($cloudinaryUrl);
});

it('mengubah path legacy menjadi URL disk publik lewat foto_url', function () {
    Storage::fake('public');
    $penduduk = Penduduk::factory()->create(['foto' => 'penduduk/lama.png']);

    expect($penduduk->foto_url)
        ->toBe(Storage::disk('public')->url('penduduk/lama.png'));
});

it('menghasilkan foto_url null untuk skema yang tidak dikenal', function () {
    $penduduk = Penduduk::factory()->create(['foto' => 'javascript:alert(1)']);

    expect($penduduk->foto_url)->toBeNull();
});

it('menghasilkan public_id untuk URL Cloudinary dengan cloud name aplikasi', function () use ($cloudinaryUrl, $publicId) {
    expect(app(PendudukFotoService::class)->ambilPublicIdDariUrl($cloudinaryUrl))
        ->toBe($publicId);
});

it('menganggap URL Cloudinary dengan cloud name berbeda bukan aset aplikasi', function () {
    $fotoCloudLain = 'https://res.cloudinary.com/cloudlain/image/upload/v1234/sideska/penduduk/abc123.png';

    expect(app(PendudukFotoService::class)->ambilPublicIdDariUrl($fotoCloudLain))->toBeNull();

    Cloudinary::shouldReceive('destroy')->never();
    app(PendudukFotoService::class)->hapus($fotoCloudLain);
});

it('mengedit penduduk tanpa foto dan mempertahankan data serta foto lama', function () {
    [$penduduk, $perangkat] = buatPendudukPerangkat();
    $penduduk->update(['foto' => null]);

    Livewire::actingAs($perangkat)
        ->test(Edit::class, ['penduduk' => $penduduk])
        ->call('update')
        ->assertHasNoErrors();

    expect($penduduk->fresh()->foto)->toBeNull();
});

it('menolak berkas non-gambar sebagai foto saat mengedit penduduk', function () {
    [$penduduk, $perangkat] = buatPendudukPerangkat();

    Livewire::actingAs($perangkat)
        ->test(Edit::class, ['penduduk' => $penduduk])
        ->set('foto', UploadedFile::fake()->image('catatan.gif'))
        ->call('update')
        ->assertHasErrors(['foto']);

    expect($penduduk->fresh()->foto)->toBeNull();
});

it('mengunggah foto baru ke Cloudinary saat mengedit penduduk', function () {
    [$penduduk, $perangkat] = buatPendudukPerangkat();

    $urlBaru = 'https://res.cloudinary.com/kxaypleu/image/upload/v1616/sideska/penduduk/xyz789.png';
    mockUploadCloudinary($urlBaru);

    Livewire::actingAs($perangkat)
        ->test(Edit::class, ['penduduk' => $penduduk])
        ->set('foto', UploadedFile::fake()->image('foto.png'))
        ->call('update')
        ->assertHasNoErrors();

    expect($penduduk->fresh()->foto)->toBe($urlBaru);
});

it('menghapus foto lama Cloudinary setelah foto baru berhasil disimpan', function () use ($cloudinaryUrl, $publicId) {
    [$penduduk, $perangkat] = buatPendudukPerangkat(['foto' => $cloudinaryUrl]);

    $urlBaru = 'https://res.cloudinary.com/kxaypleu/image/upload/v1616/sideska/penduduk/xyz789.png';
    mockUploadCloudinary($urlBaru);
    Cloudinary::shouldReceive('destroy')
        ->once()
        ->with($publicId, ['resource_type' => 'image'])
        ->andReturn(['result' => 'ok']);

    Livewire::actingAs($perangkat)
        ->test(Edit::class, ['penduduk' => $penduduk])
        ->set('foto', UploadedFile::fake()->image('foto.png'))
        ->call('update')
        ->assertHasNoErrors();

    expect($penduduk->fresh()->foto)->toBe($urlBaru);
});

it('mempertahankan penyimpanan meski penghapusan foto lama Cloudinary gagal', function () use ($cloudinaryUrl, $publicId) {
    [$penduduk, $perangkat] = buatPendudukPerangkat(['foto' => $cloudinaryUrl]);

    $urlBaru = 'https://res.cloudinary.com/kxaypleu/image/upload/v1616/sideska/penduduk/xyz789.png';
    mockUploadCloudinary($urlBaru);
    Cloudinary::shouldReceive('destroy')
        ->once()
        ->with($publicId, ['resource_type' => 'image'])
        ->andReturn(['result' => 'not_found']);

    Livewire::actingAs($perangkat)
        ->test(Edit::class, ['penduduk' => $penduduk])
        ->set('foto', UploadedFile::fake()->image('foto.png'))
        ->call('update')
        ->assertHasNoErrors();

    expect($penduduk->fresh()->foto)->toBe($urlBaru);
});

it('mencatat warning saat destroy mengembalikan ApiResponse non-ok tanpa membatalkan update', function () use ($cloudinaryUrl, $publicId) {
    [$penduduk, $perangkat] = buatPendudukPerangkat(['foto' => $cloudinaryUrl]);

    $urlBaru = 'https://res.cloudinary.com/kxaypleu/image/upload/v1616/sideska/penduduk/xyz789.png';
    mockUploadCloudinary($urlBaru);
    Cloudinary::shouldReceive('destroy')
        ->once()
        ->with($publicId, ['resource_type' => 'image'])
        ->andReturn(new ApiResponse(['result' => 'not_found'], []));
    Log::shouldReceive('warning')->once();

    Livewire::actingAs($perangkat)
        ->test(Edit::class, ['penduduk' => $penduduk])
        ->set('foto', UploadedFile::fake()->image('foto.png'))
        ->call('update')
        ->assertHasNoErrors();

    expect($penduduk->fresh()->foto)->toBe($urlBaru);
});

it('mencatat warning saat destroy melempar exception tanpa membatalkan update', function () use ($cloudinaryUrl, $publicId) {
    [$penduduk, $perangkat] = buatPendudukPerangkat(['foto' => $cloudinaryUrl]);

    $urlBaru = 'https://res.cloudinary.com/kxaypleu/image/upload/v1616/sideska/penduduk/xyz789.png';
    mockUploadCloudinary($urlBaru);
    Cloudinary::shouldReceive('destroy')
        ->once()
        ->with($publicId, ['resource_type' => 'image'])
        ->andThrow(new RuntimeException('Gagal menghapus dari Cloudinary.'));
    Log::shouldReceive('warning')->once();

    Livewire::actingAs($perangkat)
        ->test(Edit::class, ['penduduk' => $penduduk])
        ->set('foto', UploadedFile::fake()->image('foto.png'))
        ->call('update')
        ->assertHasNoErrors();

    expect($penduduk->fresh()->foto)->toBe($urlBaru);
});

it('mempertahankan foto lama saat unggahan Cloudinary gagal', function () use ($cloudinaryUrl) {
    [$penduduk, $perangkat] = buatPendudukPerangkat(['foto' => $cloudinaryUrl]);

    Cloudinary::shouldReceive('upload')
        ->once()
        ->andThrow(new RuntimeException('Cloudinary sedang tidak tersedia.'));

    Livewire::actingAs($perangkat)
        ->test(Edit::class, ['penduduk' => $penduduk])
        ->set('foto', UploadedFile::fake()->image('foto.png'))
        ->call('update')
        ->assertHasNoErrors();

    expect($penduduk->fresh()->foto)->toBe($cloudinaryUrl);
});

it('mengganti foto legacy dan menghapus berkas lama dari disk publik', function () {
    Storage::fake('public');
    Storage::disk('public')->put('penduduk/lama.png', 'gambar');

    [$penduduk, $perangkat] = buatPendudukPerangkat(['foto' => 'penduduk/lama.png']);

    $urlBaru = 'https://res.cloudinary.com/kxaypleu/image/upload/v1616/sideska/penduduk/xyz789.png';
    mockUploadCloudinary($urlBaru);

    Livewire::actingAs($perangkat)
        ->test(Edit::class, ['penduduk' => $penduduk])
        ->set('foto', UploadedFile::fake()->image('foto.png'))
        ->call('update')
        ->assertHasNoErrors();

    expect($penduduk->fresh()->foto)->toBe($urlBaru);

    Storage::disk('public')->assertMissing('penduduk/lama.png');
});

it('menampilkan foto Cloudinary lewat foto_url di halaman show', function () use ($cloudinaryUrl) {
    [$penduduk, $perangkat] = buatPendudukPerangkat(['foto' => $cloudinaryUrl]);

    $this->actingAs($perangkat)
        ->get(route('perangkat-desa.penduduk.show', $penduduk))
        ->assertOk()
        ->assertSee($cloudinaryUrl, false);
});

it('menampilkan foto legacy lewat foto_url di halaman index dan edit', function () {
    Storage::fake('public');

    [$penduduk, $perangkat] = buatPendudukPerangkat(['foto' => 'penduduk/lama.png']);

    $expectedUrl = Storage::disk('public')->url('penduduk/lama.png');

    Livewire::actingAs($perangkat)
        ->test(Index::class)
        ->call('loadData')
        ->assertSee($expectedUrl, false);

    Livewire::actingAs($perangkat)
        ->test(Edit::class, ['penduduk' => $penduduk])
        ->assertSee($expectedUrl, false);
});

it('mengunggah foto Cloudinary saat membuat penduduk', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    $urlBaru = 'https://res.cloudinary.com/kxaypleu/image/upload/v1616/sideska/penduduk/buat123.png';
    mockUploadCloudinary($urlBaru);

    $nik = fake()->numerify('################');

    Livewire::actingAs($perangkat)
        ->test(Create::class)
        ->set('foto', UploadedFile::fake()->image('foto.png'))
        ->set('email', 'warga@contoh.test')
        ->set('password', 'rahasia123')
        ->set('password_confirmation', 'rahasia123')
        ->set('nik', $nik)
        ->set('no_kk', fake()->numerify('################'))
        ->set('nama', 'Nama Warga')
        ->set('tempat_lahir', 'Jember')
        ->set('tanggal_lahir', '1990-01-01')
        ->set('jenis_kelamin', 'L')
        ->set('agama', 'Islam')
        ->set('status_perkawinan', 'Kawin')
        ->set('pendidikan', 'SMA/Sederajat')
        ->set('pekerjaan', 'Petani')
        ->set('status_hubungan_keluarga', 'Kepala Keluarga')
        ->set('golongan_darah', 'O')
        ->set('alamat', 'Jl. Contoh')
        ->set('rt', '001')
        ->set('rw', '002')
        ->set('dusun', 'Dusun Sumber')
        ->set('kewarganegaraan', 'WNI')
        ->set('no_hp', '081234567890')
        ->set('status_kependudukan', 'Tetap')
        ->set('disabilitas', 'Tidak')
        ->call('simpan')
        ->assertHasNoErrors();

    expect(Penduduk::where('nik', $nik)->firstOrFail()->foto)->toBe($urlBaru);
});

it('membersihkan aset Cloudinary yatim saat database menolak penyimpanan penduduk', function () {
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    $urlBaru = 'https://res.cloudinary.com/kxaypleu/image/upload/v1616/sideska/penduduk/abaikan456.png';
    $publicIdBaru = 'sideska/penduduk/abaikan456';
    mockUploadCloudinary($urlBaru);
    Cloudinary::shouldReceive('destroy')
        ->once()
        ->with($publicIdBaru, ['resource_type' => 'image'])
        ->andReturn(['result' => 'ok']);

    DB::swap(
        tap(Mockery::mock(DB::getFacadeRoot()), function ($mock) {
            $mock->shouldReceive('transaction')->once()->andThrow(
                new RuntimeException('Database sedang tidak tersedia.')
            );
        })
    );

    $nik = fake()->numerify('################');

    Livewire::actingAs($perangkat)
        ->test(Create::class)
        ->set('foto', UploadedFile::fake()->image('foto.png'))
        ->set('email', 'warga@contoh.test')
        ->set('password', 'rahasia123')
        ->set('password_confirmation', 'rahasia123')
        ->set('nik', $nik)
        ->set('no_kk', fake()->numerify('################'))
        ->set('nama', 'Nama Warga')
        ->set('tempat_lahir', 'Jember')
        ->set('tanggal_lahir', '1990-01-01')
        ->set('jenis_kelamin', 'L')
        ->set('agama', 'Islam')
        ->set('status_perkawinan', 'Kawin')
        ->set('pendidikan', 'SMA/Sederajat')
        ->set('pekerjaan', 'Petani')
        ->set('status_hubungan_keluarga', 'Kepala Keluarga')
        ->set('golongan_darah', 'O')
        ->set('alamat', 'Jl. Contoh')
        ->set('rt', '001')
        ->set('rw', '002')
        ->set('dusun', 'Dusun Sumber')
        ->set('kewarganegaraan', 'WNI')
        ->set('no_hp', '081234567890')
        ->set('status_kependudukan', 'Tetap')
        ->set('disabilitas', 'Tidak')
        ->call('simpan')
        ->assertHasNoErrors();

    expect(Penduduk::where('nik', $nik)->exists())->toBeFalse()
        ->and(User::where('email', 'warga@contoh.test')->exists())->toBeFalse();
});

it('membatasi halaman penduduk hanya untuk perangkat desa', function () {
    [$penduduk, $perangkat] = buatPendudukPerangkat();
    $warga = User::factory()->create(['role' => 'masyarakat']);

    $this->actingAs($warga)
        ->get(route('perangkat-desa.penduduk.index'))
        ->assertForbidden();
    $this->actingAs($warga)
        ->get(route('perangkat-desa.penduduk.create'))
        ->assertForbidden();
    $this->actingAs($warga)
        ->get(route('perangkat-desa.penduduk.show', $penduduk))
        ->assertForbidden();
    $this->actingAs($warga)
        ->get(route('perangkat-desa.penduduk.edit', $penduduk))
        ->assertForbidden();
});

it('mengarahkan tamu ke halaman login saat membuka halaman penduduk', function () {
    $this->get(route('perangkat-desa.penduduk.index'))
        ->assertRedirect(route('login'));
});

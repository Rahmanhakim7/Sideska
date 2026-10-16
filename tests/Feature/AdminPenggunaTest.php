<?php

use App\Livewire\Admin\Pengguna\CreateKepalaDesa;
use App\Livewire\Admin\Pengguna\CreateMasyarakat;
use App\Livewire\Admin\Pengguna\CreatePerangkatDesa;
use App\Livewire\Admin\Pengguna\Index;
use App\Models\Penduduk;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Livewire;

beforeEach(function () {
    config(['cloudinary.cloud_name' => 'kxaypleu']);
});

afterEach(function () {
    Cloudinary::clearResolvedInstance(CloudinaryEngine::class);
    DB::clearResolvedInstance('db');
    Log::clearResolvedInstance('log');
});

/**
 * @param  array<string, mixed>  $overrides
 * @return array<string, mixed>
 */
function adminPayloadMasyarakat(array $overrides = []): array
{
    return array_merge([
        'email' => 'warga.admin@contoh.test',
        'password' => 'rahasia123',
        'password_confirmation' => 'rahasia123',
        'nik' => fake()->unique()->numerify('################'),
        'no_kk' => fake()->numerify('################'),
        'nama' => 'Nama Warga Admin',
        'tempat_lahir' => 'Jember',
        'tanggal_lahir' => '1990-01-01',
        'jenis_kelamin' => 'L',
        'agama' => 'Islam',
        'status_perkawinan' => 'Kawin',
        'pendidikan' => 'SMA/Sederajat',
        'pekerjaan' => 'Petani',
        'status_hubungan_keluarga' => 'Kepala Keluarga',
        'golongan_darah' => 'O',
        'alamat' => 'Jl. Contoh',
        'rt' => '001',
        'rw' => '002',
        'dusun' => 'Dusun Sumber',
        'kewarganegaraan' => 'WNI',
        'no_hp' => '081234567890',
        'status_kependudukan' => 'Tetap',
        'disabilitas' => 'Tidak',
    ], $overrides);
}

function mockUploadCloudinaryAdmin(string $url): void
{
    Cloudinary::shouldReceive('upload')->once()->andReturnSelf();
    Cloudinary::shouldReceive('getSecurePath')->once()->andReturn($url);
}

/**
 * @param  array<string, mixed>  $payload
 */
function adminCreateMasyarakat(User $admin, array $payload, ?UploadedFile $foto = null)
{
    $component = Livewire::actingAs($admin)->test(CreateMasyarakat::class);

    if ($foto !== null) {
        $component->set('foto', $foto);
    }

    foreach ($payload as $key => $value) {
        $component->set($key, $value);
    }

    return $component->call('simpan');
}

it('menampilkan halaman pengguna untuk admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna'))
        ->assertOk();
});

it('menolak akses halaman pengguna untuk role selain admin', function (string $role) {
    $user = User::factory()->create(['role' => $role]);

    $this->actingAs($user)
        ->get(route('admin.pengguna'))
        ->assertForbidden();
})->with(['perangkat_desa', 'kepala_desa', 'masyarakat']);

it('mengalihkan tamu dari halaman pengguna', function () {
    $this->get(route('admin.pengguna'))
        ->assertRedirect(route('login'));
});

it('menampilkan daftar pengguna tanpa membocorkan kredensial', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create([
        'name' => 'Nama Warga Uji',
        'email' => 'warga.uji@contoh.test',
        'role' => 'masyarakat',
    ]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->assertSee('Nama Warga Uji')
        ->assertSee('warga.uji@contoh.test')
        ->assertDontSee($warga->getAuthPassword());
});

it('mengizinkan admin membuka form tambah masyarakat', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.masyarakat.create'))
        ->assertOk();
});

it('mengizinkan admin membuka form tambah perangkat desa', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.perangkat-desa.create'))
        ->assertOk();
});

it('mengizinkan admin membuka form tambah kepala desa', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.kepala-desa.create'))
        ->assertOk();
});

it('menolak akses form tambah pengguna untuk role selain admin', function (string $role, string $routeName) {
    $user = User::factory()->create(['role' => $role]);

    $this->actingAs($user)
        ->get(route($routeName))
        ->assertForbidden();
})->with(['perangkat_desa', 'kepala_desa', 'masyarakat'])
    ->with([
        'admin.pengguna.masyarakat.create',
        'admin.pengguna.perangkat-desa.create',
        'admin.pengguna.kepala-desa.create',
    ]);

it('mengalihkan tamu dari form tambah pengguna', function (string $routeName) {
    $this->get(route($routeName))
        ->assertRedirect(route('login'));
})->with([
    'admin.pengguna.masyarakat.create',
    'admin.pengguna.perangkat-desa.create',
    'admin.pengguna.kepala-desa.create',
]);

it('membuat akun masyarakat beserta data penduduk oleh admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $payload = adminPayloadMasyarakat();

    adminCreateMasyarakat($admin, $payload)
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.pengguna'));

    $user = User::where('email', $payload['email'])->firstOrFail();

    expect($user->role)->toBe('masyarakat')
        ->and($user->is_active)->toBeTrue()
        ->and($user->email_verified_at)->not->toBeNull()
        ->and($user->getAuthPassword())->not->toBe('rahasia123')
        ->and(Hash::check('rahasia123', $user->password))->toBeTrue();

    $penduduk = Penduduk::where('nik', $payload['nik'])->firstOrFail();

    expect($penduduk->user_id)->toBe($user->id)
        ->and($penduduk->nama)->toBe('Nama Warga Admin');
});

it('menolak email duplikat saat admin membuat masyarakat', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['email' => 'duplikat.masyarakat@contoh.test']);

    $payload = adminPayloadMasyarakat(['email' => 'duplikat.masyarakat@contoh.test']);

    adminCreateMasyarakat($admin, $payload)->assertHasErrors(['email']);

    expect(User::where('email', 'duplikat.masyarakat@contoh.test')->count())->toBe(1);
});

it('menolak NIK duplikat saat admin membuat masyarakat', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    Penduduk::factory()->create(['nik' => '1234567890123456']);

    $payload = adminPayloadMasyarakat(['nik' => '1234567890123456']);

    adminCreateMasyarakat($admin, $payload)->assertHasErrors(['nik']);

    expect(Penduduk::where('nik', '1234567890123456')->count())->toBe(1);
});

it('memvalidasi field utama saat admin membuat masyarakat', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(CreateMasyarakat::class)
        ->set('email', '')
        ->set('password', 'pendek')
        ->set('nik', '123')
        ->set('rt', '1')
        ->call('simpan')
        ->assertHasErrors(['email', 'password', 'nik', 'no_kk', 'nama', 'rt', 'rw']);

    expect(User::where('role', 'masyarakat')->exists())->toBeFalse();
});

it('membuat akun perangkat desa dan kepala desa tanpa data penduduk', function (string $component, string $role, string $email) {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test($component)
        ->set('name', 'Nama Akun Baru')
        ->set('email', $email)
        ->set('password', 'rahasia123')
        ->set('password_confirmation', 'rahasia123')
        ->call('simpan')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.pengguna'));

    $user = User::where('email', $email)->firstOrFail();

    expect($user->role)->toBe($role)
        ->and($user->is_active)->toBeTrue()
        ->and($user->email_verified_at)->not->toBeNull()
        ->and($user->getAuthPassword())->not->toBe('rahasia123')
        ->and(Hash::check('rahasia123', $user->password))->toBeTrue()
        ->and($user->penduduk)->toBeNull();

    expect(Penduduk::count())->toBe(0);
})->with([
    'perangkat desa' => [CreatePerangkatDesa::class, 'perangkat_desa', 'perangkat.baru@contoh.test'],
    'kepala desa' => [CreateKepalaDesa::class, 'kepala_desa', 'kepala.baru@contoh.test'],
]);

it('menolak email duplikat saat admin membuat akun sederhana', function (string $component) {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['email' => 'duplikat.sederhana@contoh.test']);

    Livewire::actingAs($admin)
        ->test($component)
        ->set('name', 'Nama Akun Baru')
        ->set('email', 'duplikat.sederhana@contoh.test')
        ->set('password', 'rahasia123')
        ->set('password_confirmation', 'rahasia123')
        ->call('simpan')
        ->assertHasErrors(['email']);

    expect(User::where('email', 'duplikat.sederhana@contoh.test')->count())->toBe(1);
})->with([
    'perangkat desa' => [CreatePerangkatDesa::class],
    'kepala desa' => [CreateKepalaDesa::class],
]);

it('tidak mengekspos properti role sehingga role tidak dapat dipalsukan dari request', function (string $component) {
    expect((new ReflectionClass($component))->hasProperty('role'))->toBeFalse();
})->with([
    CreateMasyarakat::class,
    CreatePerangkatDesa::class,
    CreateKepalaDesa::class,
]);

it('membatasi role form tambah pengguna ke whitelist non-admin', function () {
    $roles = [
        CreateMasyarakat::ROLE,
        CreatePerangkatDesa::ROLE,
        CreateKepalaDesa::ROLE,
    ];

    expect($roles)->toBe(['masyarakat', 'perangkat_desa', 'kepala_desa'])
        ->and($roles)->not->toContain('admin');
});

it('tidak membocorkan kredensial pada halaman form tambah pengguna', function (string $routeName) {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route($routeName))
        ->assertOk()
        ->assertDontSee($admin->getAuthPassword())
        ->assertDontSee((string) $admin->remember_token);
})->with([
    'admin.pengguna.masyarakat.create',
    'admin.pengguna.perangkat-desa.create',
    'admin.pengguna.kepala-desa.create',
]);

it('memproses foto masyarakat lewat PendudukFotoService saat admin membuat akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $url = 'https://res.cloudinary.com/kxaypleu/image/upload/v1616/sideska/penduduk/admin123.png';
    mockUploadCloudinaryAdmin($url);

    $payload = adminPayloadMasyarakat();

    adminCreateMasyarakat($admin, $payload, UploadedFile::fake()->image('foto.png'))
        ->assertHasNoErrors();

    expect(Penduduk::where('nik', $payload['nik'])->firstOrFail()->foto)->toBe($url);
});

it('membersihkan aset Cloudinary yatim saat transaksi pembuatan masyarakat gagal', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $url = 'https://res.cloudinary.com/kxaypleu/image/upload/v1616/sideska/penduduk/orphan123.png';
    $publicId = 'sideska/penduduk/orphan123';
    mockUploadCloudinaryAdmin($url);
    Cloudinary::shouldReceive('destroy')
        ->once()
        ->with($publicId, ['resource_type' => 'image'])
        ->andReturn(['result' => 'ok']);

    DB::swap(
        tap(Mockery::mock(DB::getFacadeRoot()), function ($mock) {
            $mock->shouldReceive('transaction')->once()->andThrow(
                new RuntimeException('Database sedang tidak tersedia.')
            );
        })
    );

    $payload = adminPayloadMasyarakat();

    adminCreateMasyarakat($admin, $payload, UploadedFile::fake()->image('foto.png'))
        ->assertHasNoErrors();

    expect(Penduduk::where('nik', $payload['nik'])->exists())->toBeFalse()
        ->and(User::where('email', $payload['email'])->exists())->toBeFalse();
});

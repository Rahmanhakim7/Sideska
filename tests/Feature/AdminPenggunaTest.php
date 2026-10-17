<?php

use App\Livewire\Admin\Pengguna\CreateKepalaDesa;
use App\Livewire\Admin\Pengguna\CreateMasyarakat;
use App\Livewire\Admin\Pengguna\CreatePerangkatDesa;
use App\Livewire\Admin\Pengguna\Detail;
use App\Livewire\Admin\Pengguna\Index;
use App\Models\MutasiPenduduk;
use App\Models\Penduduk;
use App\Models\PengajuanStatusHistory;
use App\Models\PengajuanSurat;
use App\Models\User;
use CloudinaryLabs\CloudinaryLaravel\CloudinaryEngine;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
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

it('mengalihkan tamu dari halaman detail pengguna', function () {
    $target = User::factory()->create(['role' => 'masyarakat']);

    $this->get(route('admin.pengguna.show', $target))
        ->assertRedirect(route('login'));
});

it('mengizinkan admin membuka halaman detail pengguna', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $target))
        ->assertOk();
});

it('memuat komponen detail dengan relasi penduduk melalui model binding', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create(['role' => 'masyarakat']);
    Penduduk::factory()->create(['user_id' => $warga->id]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $warga])
        ->assertOk()
        ->assertSee($warga->name);
});

it('menolak akses detail pengguna untuk role selain admin', function (string $role) {
    $pelaku = User::factory()->create(['role' => $role]);
    $target = User::factory()->create(['role' => 'masyarakat']);

    $this->actingAs($pelaku)
        ->get(route('admin.pengguna.show', $target))
        ->assertForbidden();
})->with(['perangkat_desa', 'kepala_desa', 'masyarakat']);

it('menampilkan informasi akun pada halaman detail', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'name' => 'Warga Detail Akun',
        'email' => 'warga.detail@contoh.test',
        'role' => 'masyarakat',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $target))
        ->assertOk()
        ->assertSee('Warga Detail Akun')
        ->assertSee('warga.detail@contoh.test')
        ->assertSee('Masyarakat')
        ->assertSee('Aktif')
        ->assertSee('Terverifikasi');
});

it('menampilkan label role manusia pada halaman detail', function (string $role, string $label) {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => $role]);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $target))
        ->assertOk()
        ->assertSee($label);
})->with([
    ['admin', 'Admin'],
    ['perangkat_desa', 'Perangkat Desa'],
    ['kepala_desa', 'Kepala Desa'],
    ['masyarakat', 'Masyarakat'],
]);

it('menampilkan status nonaktif dan email belum terverifikasi pada halaman detail', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => false,
        'email_verified_at' => null,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $target))
        ->assertOk()
        ->assertSee('Nonaktif')
        ->assertSee('Belum Terverifikasi');
});

it('menampilkan data penduduk pada halaman detail masyarakat', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create([
        'user_id' => $warga->id,
        'nama' => 'Budi Penduduk Detail',
        'nik' => '3200000000000001',
        'no_kk' => '3200000000000002',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $warga))
        ->assertOk()
        ->assertSee('Data Penduduk')
        ->assertSee('Budi Penduduk Detail')
        ->assertSee('3200000000000001')
        ->assertSee('3200000000000002');
});

it('menampilkan foto penduduk dari accessor fotoUrl tanpa error', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create(['role' => 'masyarakat']);
    Penduduk::factory()->create([
        'user_id' => $warga->id,
        'foto' => 'https://res.cloudinary.com/kxaypleu/image/upload/v1616/sideska/penduduk/detail.png',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $warga))
        ->assertOk()
        ->assertSee('https://res.cloudinary.com/kxaypleu/image/upload/v1616/sideska/penduduk/detail.png');
});

it('tidak error menampilkan penduduk tanpa foto', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create([
        'user_id' => $warga->id,
        'nama' => 'Tanpa Foto',
        'foto' => null,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $warga))
        ->assertOk()
        ->assertSee('Tanpa Foto');
});

it('menampilkan detail perangkat desa tanpa data penduduk', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $perangkat = User::factory()->create(['role' => 'perangkat_desa']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $perangkat))
        ->assertOk()
        ->assertSee('Pengguna ini tidak memiliki data penduduk.');
});

it('menampilkan detail kepala desa tanpa data penduduk', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $kepala = User::factory()->create(['role' => 'kepala_desa']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $kepala))
        ->assertOk()
        ->assertSee('Pengguna ini tidak memiliki data penduduk.');
});

it('tidak membocorkan hash password pada halaman detail', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $target))
        ->assertOk()
        ->assertDontSee($target->getAuthPassword());
});

it('tidak membocorkan remember token pada halaman detail', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'remember_token' => 'RAHASIA-REMEMBER-TOKEN-999',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $target))
        ->assertOk()
        ->assertDontSee('RAHASIA-REMEMBER-TOKEN-999');
});

it('tidak menampilkan form reset password pada halaman detail admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $target))
        ->assertOk()
        ->assertDontSee('Reset Password')
        ->assertDontSee('type="password"', false)
        ->assertDontSee('name="password"', false);
});

it('mendaftarkan route admin.pengguna.show dengan model binding yang benar', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat']);

    expect(route('admin.pengguna.show', $target))
        ->toEndWith('/admin/pengguna/'.$target->id);

    $this->actingAs($admin)
        ->get('/admin/pengguna/'.$target->id)
        ->assertOk();
});

/*
|--------------------------------------------------------------------------
| Admin Phase 7 — Aktif / Nonaktif User
|--------------------------------------------------------------------------
*/

it('mengalihkan tamu dari halaman admin pengguna', function () {
    $this->get(route('admin.pengguna'))
        ->assertRedirect(route('login'));
});

it('menolak akses halaman admin pengguna untuk role selain admin', function (string $role) {
    $pelaku = User::factory()->create(['role' => $role]);

    $this->actingAs($pelaku)
        ->get(route('admin.pengguna'))
        ->assertForbidden();
})->with(['masyarakat', 'perangkat_desa', 'kepala_desa']);

it('menolak toggleStatus yang dipanggil langsung untuk role selain admin', function (string $role) {
    $pelaku = User::factory()->create(['role' => $role]);
    $target = User::factory()->create(['role' => 'masyarakat', 'is_active' => true]);

    Livewire::actingAs($pelaku)
        ->test(Index::class)
        ->call('toggleStatus', $target->id)
        ->assertSee('Hanya admin yang dapat mengubah status pengguna.');

    expect($target->fresh()->is_active)->toBeTrue();
})->with(['masyarakat', 'perangkat_desa', 'kepala_desa']);

it('mengizinkan admin mengakses halaman admin pengguna', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna'))
        ->assertOk();
});

it('menonaktifkan user aktif melalui toggle', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $target->id)
        ->assertSee('Pengguna berhasil dinonaktifkan.');

    expect($target->fresh()->is_active)->toBeFalse();
});

it('menampilkan pesan sukses dan tetap di halaman admin pengguna setelah toggle', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'perangkat_desa', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $target->id)
        ->assertSee('Pengguna berhasil dinonaktifkan.')
        ->assertSet('search', '');
});

it('menolak login user yang dinonaktifkan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => true,
        'password' => Hash::make('password'),
    ]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $target->id);

    expect($target->fresh()->is_active)->toBeFalse()
        ->and(Auth::attempt([
            'email' => $target->email,
            'password' => 'password',
            'is_active' => true,
        ]))->toBeFalse();
});

it('menghapus session user target ketika dinonaktifkan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat', 'is_active' => true]);

    DB::table('sessions')->insert([
        [
            'id' => 'session-target-7',
            'user_id' => $target->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode('payload'),
            'last_activity' => time(),
        ],
        [
            'id' => 'session-admin-7',
            'user_id' => $admin->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode('payload'),
            'last_activity' => time(),
        ],
    ]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $target->id);

    expect(DB::table('sessions')->where('user_id', $target->id)->count())->toBe(0)
        ->and(DB::table('sessions')->where('user_id', $admin->id)->count())->toBe(1);
});

it('menjaga user lain tetap aktif setelah menonaktifkan target', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
    $target = User::factory()->create(['role' => 'masyarakat', 'is_active' => true]);
    $lain = User::factory()->create(['role' => 'perangkat_desa', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $target->id);

    expect($target->fresh()->is_active)->toBeFalse()
        ->and($lain->fresh()->is_active)->toBeTrue()
        ->and($admin->fresh()->is_active)->toBeTrue();
});

it('mengaktifkan kembali user nonaktif', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat', 'is_active' => false]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $target->id)
        ->assertSee('Pengguna berhasil diaktifkan.');

    expect($target->fresh()->is_active)->toBeTrue();
});

it('memungkinkan user login kembali setelah diaktifkan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => false,
        'password' => Hash::make('password'),
    ]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $target->id);

    expect($target->fresh()->is_active)->toBeTrue()
        ->and(Auth::attempt([
            'email' => $target->email,
            'password' => 'password',
            'is_active' => true,
        ]))->toBeTrue();

    Auth::logout();
});

it('menolak admin menonaktifkan dirinya sendiri', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $admin->id)
        ->assertSee('Anda tidak dapat mengubah status akun Anda sendiri.');

    expect($admin->fresh()->is_active)->toBeTrue();
});

it('menolak admin menargetkan user role admin lain', function () {
    $admin = User::factory()->create(['role' => 'admin', 'is_active' => true]);
    $targetAdmin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $targetAdmin->id)
        ->assertSee('Akun dengan role admin tidak dapat diubah statusnya melalui halaman ini.');

    expect($targetAdmin->fresh()->is_active)->toBeTrue()
        ->and(User::where('role', 'admin')->where('is_active', true)->count())->toBeGreaterThanOrEqual(1);
});

it('menonaktifkan masyarakat biasa dengan penduduk tetap', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create(['role' => 'masyarakat', 'is_active' => true]);
    Penduduk::factory()->create(['user_id' => $warga->id, 'status_kependudukan' => 'Tetap']);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $warga->id);

    expect($warga->fresh()->is_active)->toBeFalse();
});

it('menolak reaktivasi masyarakat berstatus Pindah', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create(['role' => 'masyarakat', 'is_active' => false]);
    Penduduk::factory()->create(['user_id' => $warga->id, 'status_kependudukan' => 'Pindah']);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $warga->id)
        ->assertSee('berstatus Pindah');

    expect($warga->fresh()->is_active)->toBeFalse()
        ->and($warga->penduduk->status_kependudukan)->toBe('Pindah');
});

it('tidak mengubah status kependudukan saat toggle', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create(['role' => 'masyarakat', 'is_active' => true]);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id, 'status_kependudukan' => 'Tetap']);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $warga->id);

    expect($warga->fresh()->is_active)->toBeFalse()
        ->and($penduduk->fresh()->status_kependudukan)->toBe('Tetap');
});

it('tidak mengubah data pengajuan surat dan mutasi saat toggle', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create(['role' => 'masyarakat', 'is_active' => true]);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id, 'status_kependudukan' => 'Tetap']);
    PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);
    MutasiPenduduk::factory()->create(['penduduk_id' => $penduduk->id]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $warga->id);

    expect($warga->fresh()->is_active)->toBeFalse()
        ->and(PengajuanSurat::where('penduduk_id', $penduduk->id)->count())->toBe(1)
        ->and(MutasiPenduduk::where('penduduk_id', $penduduk->id)->count())->toBe(1)
        ->and($penduduk->fresh()->user_id)->toBe($warga->id);
});

it('detail user tetap dapat dibuka setelah toggle status', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $target->id);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $target))
        ->assertOk()
        ->assertSee($target->name);
});

it('pagination tetap berfungsi setelah fitur toggle ditambahkan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->count(20)->create(['role' => 'masyarakat']);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->assertSee('Berikutnya')
        ->call('nextPage')
        ->assertSet('paginators.page', 2);
});

/*
|--------------------------------------------------------------------------
| Admin Phase 8 — Reset Password User
|--------------------------------------------------------------------------
*/

it('menolak reset password yang dipanggil langsung untuk role selain admin', function (string $role) {
    $pelaku = User::factory()->create(['role' => $role]);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'password' => Hash::make('passwordlama'),
    ]);
    $hashSebelum = $target->password;

    Livewire::actingAs($pelaku)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword')
        ->assertSee('Anda tidak memiliki akses untuk melakukan tindakan ini.');

    expect($target->fresh()->password)->toBe($hashSebelum)
        ->and(Hash::check('passwordlama', $target->fresh()->password))->toBeTrue();
})->with(['masyarakat', 'perangkat_desa', 'kepala_desa']);

it('menolak reset password ketika tidak ada pengguna terautentikasi', function () {
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'password' => Hash::make('passwordlama'),
    ]);
    $hashSebelum = $target->password;

    Livewire::test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword')
        ->assertSee('Anda tidak memiliki akses untuk melakukan tindakan ini.');

    expect($target->fresh()->password)->toBe($hashSebelum);
});

it('mengizinkan admin mereset password user masyarakat', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'password' => Hash::make('passwordlama'),
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword')
        ->assertHasNoErrors()
        ->assertSee('Password pengguna berhasil direset.');

    expect(Hash::check('passwordbaru123', $target->fresh()->password))->toBeTrue()
        ->and(Hash::check('passwordlama', $target->fresh()->password))->toBeFalse();
});

it('menyimpan password baru sebagai hash dan bukan plaintext', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'perangkat_desa',
        'password' => Hash::make('passwordlama'),
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword');

    $tersimpan = $target->fresh()->password;

    expect($tersimpan)->not->toBe('passwordbaru123')
        ->and($tersimpan)->not->toBe($target->getAuthPassword())
        ->and(Hash::check('passwordbaru123', $tersimpan))->toBeTrue();
});

it('membuat password lama tidak valid dan password baru valid untuk login', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => true,
        'password' => Hash::make('passwordlama'),
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword');

    expect(Auth::attempt([
        'email' => $target->email,
        'password' => 'passwordlama',
        'is_active' => true,
    ]))->toBeFalse();

    expect(Auth::attempt([
        'email' => $target->email,
        'password' => 'passwordbaru123',
        'is_active' => true,
    ]))->toBeTrue();

    Auth::logout();
});

it('meregenerasi remember token setelah reset password', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'kepala_desa',
        'password' => Hash::make('passwordlama'),
        'remember_token' => 'TOKEN-LAMA-123',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword');

    expect($target->fresh()->remember_token)->not->toBe('TOKEN-LAMA-123')
        ->and($target->fresh()->remember_token)->not->toBeNull();
});

it('menolak reset password untuk target role admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $targetAdmin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('passwordlama'),
    ]);
    $hashSebelum = $targetAdmin->password;

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $targetAdmin])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword')
        ->assertSee('Akun dengan role admin tidak dapat direset password melalui halaman ini.');

    expect($targetAdmin->fresh()->password)->toBe($hashSebelum)
        ->and(Hash::check('passwordlama', $targetAdmin->fresh()->password))->toBeTrue();
});

it('menolak admin mereset password dirinya sendiri', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'password' => Hash::make('passwordlama'),
    ]);
    $hashSebelum = $admin->password;

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $admin])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword')
        ->assertSee('Akun dengan role admin tidak dapat direset password melalui halaman ini.');

    expect($admin->fresh()->password)->toBe($hashSebelum);
});

it('menghapus semua session user target setelah reset password', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'password' => Hash::make('passwordlama'),
    ]);

    DB::table('sessions')->insert([
        [
            'id' => 'session-target-8a',
            'user_id' => $target->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode('payload'),
            'last_activity' => time(),
        ],
        [
            'id' => 'session-target-8b',
            'user_id' => $target->id,
            'ip_address' => '127.0.0.2',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode('payload'),
            'last_activity' => time(),
        ],
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword');

    expect(DB::table('sessions')->where('user_id', $target->id)->count())->toBe(0);
});

it('tidak menghapus session admin dan user lain saat reset password', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'password' => Hash::make('passwordlama'),
    ]);
    $lain = User::factory()->create(['role' => 'perangkat_desa']);

    DB::table('sessions')->insert([
        [
            'id' => 'session-admin-8',
            'user_id' => $admin->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode('payload'),
            'last_activity' => time(),
        ],
        [
            'id' => 'session-target-8',
            'user_id' => $target->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode('payload'),
            'last_activity' => time(),
        ],
        [
            'id' => 'session-lain-8',
            'user_id' => $lain->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode('payload'),
            'last_activity' => time(),
        ],
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword');

    expect(DB::table('sessions')->where('user_id', $target->id)->count())->toBe(0)
        ->and(DB::table('sessions')->where('user_id', $admin->id)->count())->toBe(1)
        ->and(DB::table('sessions')->where('user_id', $lain->id)->count())->toBe(1);
});

it('tidak mengubah role saat reset password', function (string $role) {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => $role,
        'password' => Hash::make('passwordlama'),
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword');

    expect($target->fresh()->role)->toBe($role);
})->with(['masyarakat', 'perangkat_desa', 'kepala_desa']);

it('menjaga user aktif tetap aktif setelah reset password', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'perangkat_desa',
        'is_active' => true,
        'password' => Hash::make('passwordlama'),
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword');

    expect($target->fresh()->is_active)->toBeTrue();
});

it('menjaga user nonaktif tetap nonaktif dan tetap gagal login setelah reset', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => false,
        'password' => Hash::make('passwordlama'),
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword')
        ->assertSee('Password pengguna berhasil direset.');

    expect($target->fresh()->is_active)->toBeFalse()
        ->and(Hash::check('passwordbaru123', $target->fresh()->password))->toBeTrue()
        ->and(Auth::attempt([
            'email' => $target->email,
            'password' => 'passwordbaru123',
            'is_active' => true,
        ]))->toBeFalse();
});

it('memungkinkan login dengan password baru setelah user diaktifkan kembali', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => false,
        'password' => Hash::make('passwordlama'),
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword');

    expect($target->fresh()->is_active)->toBeFalse();

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $target->id);

    expect($target->fresh()->is_active)->toBeTrue()
        ->and(Auth::attempt([
            'email' => $target->email,
            'password' => 'passwordbaru123',
            'is_active' => true,
        ]))->toBeTrue();

    Auth::logout();
});

it('tidak mengubah data penduduk, pengajuan, dan mutasi saat reset password', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => true,
        'password' => Hash::make('passwordlama'),
    ]);
    $penduduk = Penduduk::factory()->create([
        'user_id' => $warga->id,
        'status_kependudukan' => 'Tetap',
    ]);
    PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);
    MutasiPenduduk::factory()->create(['penduduk_id' => $penduduk->id]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $warga])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword');

    expect($penduduk->fresh()->user_id)->toBe($warga->id)
        ->and($penduduk->fresh()->status_kependudukan)->toBe('Tetap')
        ->and(PengajuanSurat::where('penduduk_id', $penduduk->id)->count())->toBe(1)
        ->and(MutasiPenduduk::where('penduduk_id', $penduduk->id)->count())->toBe(1);
});

it('mengizinkan reset password masyarakat berstatus Pindah tanpa mengubah statusnya', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => false,
        'password' => Hash::make('passwordlama'),
    ]);
    $penduduk = Penduduk::factory()->create([
        'user_id' => $warga->id,
        'status_kependudukan' => 'Pindah',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $warga])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword')
        ->assertSee('Password pengguna berhasil direset.');

    expect(Hash::check('passwordbaru123', $warga->fresh()->password))->toBeTrue()
        ->and($warga->fresh()->is_active)->toBeFalse()
        ->and($penduduk->fresh()->status_kependudukan)->toBe('Pindah');
});

it('memvalidasi password dan konfirmasi saat reset password', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'password' => Hash::make('passwordlama'),
    ]);
    $hashSebelum = $target->password;

    $component = Livewire::actingAs($admin)->test(Detail::class, ['user' => $target]);

    $component
        ->set('password', 'pendek')
        ->set('password_confirmation', 'pendek')
        ->call('resetPassword')
        ->assertHasErrors(['password', 'password_confirmation']);

    $component
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'berbeda12345')
        ->call('resetPassword')
        ->assertHasErrors(['password']);

    expect($target->fresh()->password)->toBe($hashSebelum);
});

it('mengosongkan field password setelah reset berhasil', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'perangkat_desa',
        'password' => Hash::make('passwordlama'),
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword')
        ->assertSet('password', '')
        ->assertSet('password_confirmation', '');
});

it('detail user tetap dapat dibuka setelah fitur reset password ditambahkan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->assertOk()
        ->assertSee($target->name);
});

it('tidak membocorkan hash password dan remember token pada halaman detail non-admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'remember_token' => 'RAHASIA-REMEMBER-TOKEN-888',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $target))
        ->assertOk()
        ->assertSee('Reset Password')
        ->assertDontSee($target->getAuthPassword())
        ->assertDontSee('RAHASIA-REMEMBER-TOKEN-888');
});

it('pagination tetap berfungsi setelah fitur reset password ditambahkan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->count(20)->create(['role' => 'masyarakat']);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->assertSee('Berikutnya')
        ->call('nextPage')
        ->assertSet('paginators.page', 2);
});

/*
|--------------------------------------------------------------------------
| Admin Phase 9 — Edit User (Edit Akun)
|--------------------------------------------------------------------------
*/

it('menolak perbaruiAkun yang dipanggil langsung untuk role selain admin', function (string $role) {
    $pelaku = User::factory()->create(['role' => $role]);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'name' => 'Nama Asli',
        'email' => 'asli.edit@contoh.test',
    ]);

    Livewire::actingAs($pelaku)
        ->test(Detail::class, ['user' => $target])
        ->set('name', 'Nama Diubah')
        ->set('email', 'diubah.edit@contoh.test')
        ->call('perbaruiAkun')
        ->assertSee('Anda tidak memiliki akses untuk melakukan tindakan ini.');

    expect($target->fresh()->name)->toBe('Nama Asli')
        ->and($target->fresh()->email)->toBe('asli.edit@contoh.test');
})->with(['masyarakat', 'perangkat_desa', 'kepala_desa']);

it('menolak perbaruiAkun ketika tidak ada pengguna terautentikasi', function () {
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'name' => 'Nama Asli',
        'email' => 'asli.guest@contoh.test',
    ]);

    Livewire::test(Detail::class, ['user' => $target])
        ->set('name', 'Nama Diubah')
        ->set('email', 'diubah.guest@contoh.test')
        ->call('perbaruiAkun')
        ->assertSee('Anda tidak memiliki akses untuk melakukan tindakan ini.');

    expect($target->fresh()->name)->toBe('Nama Asli')
        ->and($target->fresh()->email)->toBe('asli.guest@contoh.test');
});

it('mengizinkan admin memperbarui akun target non-admin', function (string $role) {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => $role,
        'name' => 'Nama Lama',
        'email' => 'lama.'.$role.'@contoh.test',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', 'Nama Baru')
        ->set('email', 'baru.'.$role.'@contoh.test')
        ->call('perbaruiAkun')
        ->assertHasNoErrors()
        ->assertSee('Data akun pengguna berhasil diperbarui.');

    expect($target->fresh()->name)->toBe('Nama Baru')
        ->and($target->fresh()->email)->toBe('baru.'.$role.'@contoh.test');
})->with(['masyarakat', 'perangkat_desa', 'kepala_desa']);

it('menolak admin memperbarui akun admin lain', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $targetAdmin = User::factory()->create([
        'role' => 'admin',
        'name' => 'Admin Lain',
        'email' => 'admin.lain@contoh.test',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $targetAdmin])
        ->set('name', 'Diretas')
        ->set('email', 'diretas@contoh.test')
        ->call('perbaruiAkun')
        ->assertSee('Akun dengan role admin tidak dapat diedit melalui halaman ini.');

    expect($targetAdmin->fresh()->name)->toBe('Admin Lain')
        ->and($targetAdmin->fresh()->email)->toBe('admin.lain@contoh.test');
});

it('menolak admin memperbarui akun dirinya sendiri', function () {
    $admin = User::factory()->create([
        'role' => 'admin',
        'name' => 'Admin Sendiri',
        'email' => 'admin.sendiri@contoh.test',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $admin])
        ->set('name', 'Diubah Sendiri')
        ->set('email', 'diubah.sendiri@contoh.test')
        ->call('perbaruiAkun')
        ->assertSee('Akun dengan role admin tidak dapat diedit melalui halaman ini.');

    expect($admin->fresh()->name)->toBe('Admin Sendiri')
        ->and($admin->fresh()->email)->toBe('admin.sendiri@contoh.test');
});

it('tidak menampilkan form edit akun pada detail target admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $targetAdmin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $targetAdmin))
        ->assertOk()
        ->assertDontSee('Edit Akun')
        ->assertDontSee('Simpan Perubahan');
});

it('menampilkan form edit akun pada detail target non-admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $target))
        ->assertOk()
        ->assertSee('Edit Akun')
        ->assertSee('Simpan Perubahan');
});

it('memvalidasi nama wajib diisi saat edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat', 'name' => 'Nama Asli']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', '')
        ->set('email', $target->email)
        ->call('perbaruiAkun')
        ->assertHasErrors(['name']);

    expect($target->fresh()->name)->toBe('Nama Asli');
});

it('memvalidasi panjang maksimal nama saat edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat', 'name' => 'Nama Asli']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', str_repeat('a', 256))
        ->set('email', $target->email)
        ->call('perbaruiAkun')
        ->assertHasErrors(['name']);

    expect($target->fresh()->name)->toBe('Nama Asli');
});

it('memvalidasi email wajib diisi saat edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat', 'email' => 'asli.wajib@contoh.test']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', $target->name)
        ->set('email', '')
        ->call('perbaruiAkun')
        ->assertHasErrors(['email']);

    expect($target->fresh()->email)->toBe('asli.wajib@contoh.test');
});

it('memvalidasi format email saat edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat', 'email' => 'asli.format@contoh.test']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', $target->name)
        ->set('email', 'bukan-email')
        ->call('perbaruiAkun')
        ->assertHasErrors(['email']);

    expect($target->fresh()->email)->toBe('asli.format@contoh.test');
});

it('menolak email yang sudah digunakan pengguna lain saat edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat', 'email' => 'asli.duplikat@contoh.test']);
    User::factory()->create(['email' => 'dipakai.lain@contoh.test']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', $target->name)
        ->set('email', 'dipakai.lain@contoh.test')
        ->call('perbaruiAkun')
        ->assertHasErrors(['email']);

    expect($target->fresh()->email)->toBe('asli.duplikat@contoh.test')
        ->and(User::where('email', 'dipakai.lain@contoh.test')->count())->toBe(1);
});

it('menerima email yang tidak diubah saat edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'name' => 'Nama Lama',
        'email' => 'tetap.sama@contoh.test',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', 'Nama Baru')
        ->set('email', 'tetap.sama@contoh.test')
        ->call('perbaruiAkun')
        ->assertHasNoErrors();

    expect($target->fresh()->name)->toBe('Nama Baru')
        ->and($target->fresh()->email)->toBe('tetap.sama@contoh.test');
});

it('tidak mengubah role saat edit akun', function (string $role) {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => $role]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', 'Nama Baru')
        ->set('email', 'role.'.$role.'@contoh.test')
        ->call('perbaruiAkun');

    expect($target->fresh()->role)->toBe($role);
})->with(['masyarakat', 'perangkat_desa', 'kepala_desa']);

it('tidak mengubah is_active saat edit akun', function (bool $aktif) {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat', 'is_active' => $aktif]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', 'Nama Baru')
        ->set('email', 'status.'.($aktif ? 'aktif' : 'nonaktif').'@contoh.test')
        ->call('perbaruiAkun');

    expect($target->fresh()->is_active)->toBe($aktif);
})->with([true, false]);

it('tidak mengubah password hash saat edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'password' => Hash::make('passwordlama'),
    ]);
    $hashSebelum = $target->password;

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', 'Nama Baru')
        ->set('email', 'password.tetap@contoh.test')
        ->call('perbaruiAkun');

    expect($target->fresh()->password)->toBe($hashSebelum)
        ->and(Hash::check('passwordlama', $target->fresh()->password))->toBeTrue();
});

it('tidak mengubah email_verified_at saat email berubah', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'email' => 'verified.lama@contoh.test',
        'email_verified_at' => now()->subDays(3),
    ]);
    $verifiedSebelum = $target->email_verified_at;

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', $target->name)
        ->set('email', 'verified.baru@contoh.test')
        ->call('perbaruiAkun');

    expect($target->fresh()->email)->toBe('verified.baru@contoh.test')
        ->and($target->fresh()->email_verified_at?->timestamp)->toBe($verifiedSebelum?->timestamp);
});

it('tidak mengubah remember_token saat edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'perangkat_desa',
        'remember_token' => 'TOKEN-TETAP-PHASE-9',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', 'Nama Baru')
        ->set('email', 'token.tetap@contoh.test')
        ->call('perbaruiAkun');

    expect($target->fresh()->remember_token)->toBe('TOKEN-TETAP-PHASE-9');
});

it('tidak menghapus session target saat edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat']);

    DB::table('sessions')->insert([
        'id' => 'session-target-9',
        'user_id' => $target->id,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'PHPUnit',
        'payload' => base64_encode('payload'),
        'last_activity' => time(),
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', 'Nama Baru')
        ->set('email', 'session.tetap@contoh.test')
        ->call('perbaruiAkun');

    expect(DB::table('sessions')->where('user_id', $target->id)->count())->toBe(1);
});

it('tidak mengubah data penduduk saat edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create([
        'user_id' => $warga->id,
        'nik' => '3200000000009999',
        'nama' => 'Nama Kependudukan',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $warga])
        ->set('name', 'Nama Akun Baru')
        ->set('email', 'penduduk.tetap@contoh.test')
        ->call('perbaruiAkun');

    expect($penduduk->fresh()->user_id)->toBe($warga->id)
        ->and($penduduk->fresh()->nama)->toBe('Nama Kependudukan')
        ->and($penduduk->fresh()->nik)->toBe('3200000000009999');
});

it('tidak mengubah pengajuan surat dan mutasi saat edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);
    MutasiPenduduk::factory()->create(['penduduk_id' => $penduduk->id]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $warga])
        ->set('name', 'Nama Baru')
        ->set('email', 'histori.tetap@contoh.test')
        ->call('perbaruiAkun');

    expect(PengajuanSurat::where('penduduk_id', $penduduk->id)->count())->toBe(1)
        ->and(MutasiPenduduk::where('penduduk_id', $penduduk->id)->count())->toBe(1);
});

it('tidak membocorkan hash password dan remember token pada form edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'remember_token' => 'RAHASIA-REMEMBER-TOKEN-999',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.pengguna.show', $target))
        ->assertOk()
        ->assertSee('Edit Akun')
        ->assertDontSee($target->getAuthPassword())
        ->assertDontSee('RAHASIA-REMEMBER-TOKEN-999');
});

it('menampilkan data terbaru tanpa redirect setelah edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'kepala_desa',
        'name' => 'Nama Lama',
        'email' => 'lama.tampil@contoh.test',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', 'Nama Baru Tampil')
        ->set('email', 'baru.tampil@contoh.test')
        ->call('perbaruiAkun')
        ->assertSet('name', 'Nama Baru Tampil')
        ->assertSet('email', 'baru.tampil@contoh.test')
        ->assertSee('Nama Baru Tampil')
        ->assertSee('baru.tampil@contoh.test');
});

it('reset password tetap berfungsi setelah edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'password' => Hash::make('passwordlama'),
    ]);

    $component = Livewire::actingAs($admin)->test(Detail::class, ['user' => $target]);

    $component
        ->set('name', 'Nama Baru')
        ->set('email', 'edit.dan.reset@contoh.test')
        ->call('perbaruiAkun')
        ->assertHasNoErrors();

    $component
        ->set('password', 'passwordbaru123')
        ->set('password_confirmation', 'passwordbaru123')
        ->call('resetPassword')
        ->assertHasNoErrors()
        ->assertSee('Password pengguna berhasil direset.');

    expect(Hash::check('passwordbaru123', $target->fresh()->password))->toBeTrue()
        ->and($target->fresh()->email)->toBe('edit.dan.reset@contoh.test');
});

it('toggle aktif nonaktif tetap berfungsi setelah edit akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', 'Nama Baru')
        ->set('email', 'edit.dan.toggle@contoh.test')
        ->call('perbaruiAkun');

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('toggleStatus', $target->id)
        ->assertSee('Pengguna berhasil dinonaktifkan.');

    expect($target->fresh()->is_active)->toBeFalse()
        ->and($target->fresh()->email)->toBe('edit.dan.toggle@contoh.test');
});

it('detail user tetap dapat dibuka setelah fitur edit akun ditambahkan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->assertOk()
        ->assertSee($target->name);
});

/*
|--------------------------------------------------------------------------
| Admin Phase 10 — Delete User (perangkat_desa & kepala_desa)
|--------------------------------------------------------------------------
*/

it('mengizinkan admin menghapus akun perangkat desa', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $lain = User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus')
        ->assertRedirect(route('admin.pengguna'));

    expect(User::find($target->id))->toBeNull()
        ->and(User::find($lain->id))->not->toBeNull();
});

it('mengizinkan admin menghapus akun kepala desa', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $lain = User::factory()->create(['role' => 'kepala_desa']);
    $target = User::factory()->create(['role' => 'kepala_desa']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus')
        ->assertRedirect(route('admin.pengguna'));

    expect(User::find($target->id))->toBeNull()
        ->and(User::find($lain->id))->not->toBeNull();
});

it('menolak hapus yang dipanggil langsung untuk role selain admin', function (string $role) {
    $pelaku = User::factory()->create(['role' => $role]);
    $target = User::factory()->create(['role' => 'kepala_desa']);

    Livewire::actingAs($pelaku)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus')
        ->assertSee('Anda tidak memiliki akses untuk melakukan tindakan ini.');

    expect(User::find($target->id))->not->toBeNull();
})->with(['masyarakat', 'perangkat_desa', 'kepala_desa']);

it('menolak hapus ketika tidak ada pengguna terautentikasi', function () {
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    Livewire::test(Detail::class, ['user' => $target])
        ->call('hapus')
        ->assertSee('Anda tidak memiliki akses untuk melakukan tindakan ini.');

    expect(User::find($target->id))->not->toBeNull();
});

it('menolak admin menghapus akunnya sendiri', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $admin])
        ->call('hapus')
        ->assertSee('Anda tidak dapat menghapus akun Anda sendiri.');

    expect(User::find($admin->id))->not->toBeNull();
});

it('menolak admin menghapus target dengan role admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $targetAdmin = User::factory()->create(['role' => 'admin']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $targetAdmin])
        ->call('hapus')
        ->assertSee('Akun dengan role ini tidak dapat dihapus melalui halaman ini.');

    expect(User::find($targetAdmin->id))->not->toBeNull();
});

it('menolak admin menghapus target dengan role masyarakat', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $warga = User::factory()->create(['role' => 'masyarakat']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $warga])
        ->call('hapus')
        ->assertSee('Akun dengan role ini tidak dapat dihapus melalui halaman ini.');

    expect(User::find($warga->id))->not->toBeNull();
});

it('menolak menghapus akun perangkat desa terakhir', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus')
        ->assertSee('Akun Perangkat Desa terakhir tidak dapat dihapus.');

    expect(User::find($target->id))->not->toBeNull();
});

it('menolak menghapus akun kepala desa terakhir', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'kepala_desa']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus')
        ->assertSee('Akun Kepala Desa terakhir tidak dapat dihapus.');

    expect(User::find($target->id))->not->toBeNull();
});

it('mengizinkan menghapus salah satu dari dua perangkat desa', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus')
        ->assertRedirect(route('admin.pengguna'));

    expect(User::find($target->id))->toBeNull()
        ->and(User::where('role', 'perangkat_desa')->count())->toBe(1);
});

it('mengizinkan menghapus salah satu dari dua kepala desa', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'kepala_desa']);
    $target = User::factory()->create(['role' => 'kepala_desa']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus')
        ->assertRedirect(route('admin.pengguna'));

    expect(User::find($target->id))->toBeNull()
        ->and(User::where('role', 'kepala_desa')->count())->toBe(1);
});

it('menghapus semua session user target setelah hapus akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $lain = User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    DB::table('sessions')->insert([
        [
            'id' => 'session-hapus-target',
            'user_id' => $target->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode('payload'),
            'last_activity' => time(),
        ],
        [
            'id' => 'session-hapus-admin',
            'user_id' => $admin->id,
            'ip_address' => '127.0.0.1',
            'user_agent' => 'PHPUnit',
            'payload' => base64_encode('payload'),
            'last_activity' => time(),
        ],
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus');

    expect(DB::table('sessions')->where('user_id', $target->id)->count())->toBe(0)
        ->and(DB::table('sessions')->where('user_id', $admin->id)->count())->toBe(1)
        ->and(User::find($lain->id))->not->toBeNull();
});

it('menghapus password reset token user target setelah hapus akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    DB::table('password_reset_tokens')->insert([
        'email' => $target->email,
        'token' => 'token-target',
        'created_at' => now(),
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus');

    expect(DB::table('password_reset_tokens')->where('email', $target->email)->count())->toBe(0);
});

it('menghapus notification user target dan menyisakan notifikasi user lain', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $lain = User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    DB::table('notifications')->insert([
        [
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\PengajuanStatusNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $target->id,
            'data' => json_encode([]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ],
        [
            'id' => (string) Str::uuid(),
            'type' => 'App\Notifications\PengajuanStatusNotification',
            'notifiable_type' => User::class,
            'notifiable_id' => $lain->id,
            'data' => json_encode([]),
            'read_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ],
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus');

    expect(DB::table('notifications')->where('notifiable_id', $target->id)->count())->toBe(0)
        ->and(DB::table('notifications')->where('notifiable_id', $lain->id)->count())->toBe(1);
});

it('mempertahankan pengajuan status history milik target dengan user_id menjadi null', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    $history = PengajuanStatusHistory::create([
        'pengajuan_surat_id' => $pengajuan->id,
        'status_sebelum' => null,
        'status_sesudah' => 'diproses',
        'user_id' => $target->id,
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus');

    $history->refresh();

    expect(PengajuanStatusHistory::find($history->id))->not->toBeNull()
        ->and($history->user_id)->toBeNull()
        ->and(PengajuanSurat::find($pengajuan->id))->not->toBeNull();
});

it('mempertahankan mutasi penduduk milik target dengan user_id menjadi null', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $mutasi = MutasiPenduduk::factory()->create([
        'penduduk_id' => $penduduk->id,
        'user_id' => $target->id,
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus');

    $mutasi->refresh();

    expect(MutasiPenduduk::find($mutasi->id))->not->toBeNull()
        ->and($mutasi->user_id)->toBeNull()
        ->and(Penduduk::find($penduduk->id))->not->toBeNull();
});

it('tidak mengubah pengajuan surat milik masyarakat saat menghapus akun target', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $pengajuan = PengajuanSurat::factory()->create(['penduduk_id' => $penduduk->id]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus');

    expect(PengajuanSurat::find($pengajuan->id))->not->toBeNull()
        ->and(PengajuanSurat::find($pengajuan->id)->penduduk_id)->toBe($penduduk->id);
});

it('tidak mengubah penduduk milik masyarakat saat menghapus akun target', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus');

    expect(Penduduk::find($penduduk->id))->not->toBeNull()
        ->and(Penduduk::find($penduduk->id)->user_id)->toBe($warga->id);
});

it('tidak mengubah mutasi milik masyarakat saat menghapus akun target', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    $warga = User::factory()->create(['role' => 'masyarakat']);
    $penduduk = Penduduk::factory()->create(['user_id' => $warga->id]);
    $mutasi = MutasiPenduduk::factory()->create(['penduduk_id' => $penduduk->id]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus');

    expect(MutasiPenduduk::find($mutasi->id))->not->toBeNull()
        ->and(MutasiPenduduk::find($mutasi->id)->penduduk_id)->toBe($penduduk->id);
});

it('rollback dan tidak menghapus apa pun ketika delete gagal karena constraint', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create(['role' => 'perangkat_desa']);

    Penduduk::factory()->create(['user_id' => $target->id]);

    DB::table('sessions')->insert([
        'id' => 'session-rollback-target',
        'user_id' => $target->id,
        'ip_address' => '127.0.0.1',
        'user_agent' => 'PHPUnit',
        'payload' => base64_encode('payload'),
        'last_activity' => time(),
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus')
        ->assertSee('Akun pengguna gagal dihapus. Silakan coba lagi.');

    expect(User::find($target->id))->not->toBeNull()
        ->and(DB::table('sessions')->where('user_id', $target->id)->count())->toBe(1);
});

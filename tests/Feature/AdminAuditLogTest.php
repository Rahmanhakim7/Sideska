<?php

use App\Enums\AuditAction;
use App\Livewire\Admin\AuditLog\Index as AuditLogIndex;
use App\Livewire\Admin\Pengguna\Detail;
use App\Livewire\Admin\Pengguna\Index as PenggunaIndex;
use App\Models\AuditLog;
use App\Models\User;
use Livewire\Livewire;

/**
 * Ambil log audit terbaru (urut id, bukan created_at karena bisa sama).
 */
function logAuditTerbaru(): ?AuditLog
{
    return AuditLog::orderByDesc('id')->first();
}

it('mengalihkan tamu dari halaman audit log', function () {
    $this->get(route('admin.audit-log'))
        ->assertRedirect(route('login'));
});

it('menolak akses halaman audit log untuk role selain admin', function (string $role) {
    $pelaku = User::factory()->create(['role' => $role]);

    $this->actingAs($pelaku)
        ->get(route('admin.audit-log'))
        ->assertForbidden();
})->with(['perangkat_desa', 'kepala_desa', 'masyarakat']);

it('mengizinkan admin membuka halaman audit log', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.audit-log'))
        ->assertOk();
});

it('menampilkan menu audit log pada navigasi admin', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.pengguna'))
        ->assertOk()
        ->assertSee('Audit Log');
});

it('menampilkan empty state ketika belum ada aktivitas', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('admin.audit-log'))
        ->assertOk()
        ->assertSee('Belum ada aktivitas tercatat.');
});

it('mencatat audit log saat admin menonaktifkan akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => true,
        'name' => 'Warga Nonaktif',
    ]);

    Livewire::actingAs($admin)
        ->test(PenggunaIndex::class)
        ->call('toggleStatus', $target->id);

    $log = logAuditTerbaru();

    expect($log)->not->toBeNull()
        ->and($log->user_id)->toBe($admin->id)
        ->and($log->action->value)->toBe('user_deactivated')
        ->and($log->target_type)->toBe(User::class)
        ->and($log->target_id)->toBe($target->id)
        ->and($log->description)->toBe('Admin menonaktifkan akun "Warga Nonaktif"');
});

it('mencatat audit log saat admin mengaktifkan kembali akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => false,
        'name' => 'Warga Aktif',
    ]);

    Livewire::actingAs($admin)
        ->test(PenggunaIndex::class)
        ->call('toggleStatus', $target->id);

    $log = logAuditTerbaru();

    expect($log->action->value)->toBe('user_activated')
        ->and($log->description)->toBe('Admin mengaktifkan akun "Warga Aktif"');
});

it('mencatat audit log saat admin mereset password', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'name' => 'Warga Reset',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'password-baru-123')
        ->set('password_confirmation', 'password-baru-123')
        ->call('resetPassword')
        ->assertHasNoErrors();

    $log = logAuditTerbaru();

    expect($log->action->value)->toBe('user_password_reset')
        ->and($log->user_id)->toBe($admin->id)
        ->and($log->target_id)->toBe($target->id)
        ->and($log->description)->toBe('Admin mereset password akun "Warga Reset"');
});

it('mencatat audit log saat admin mengubah akun beserta ringkasan perubahan', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'name' => 'Nama Lama',
        'email' => 'lama@contoh.test',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('name', 'Nama Baru')
        ->set('email', 'baru@contoh.test')
        ->call('perbaruiAkun')
        ->assertHasNoErrors();

    $log = logAuditTerbaru();

    expect($log->action->value)->toBe('user_updated')
        ->and($log->description)->toContain('Nama Lama')
        ->and($log->description)->toContain('Nama Baru')
        ->and($log->description)->toContain('lama@contoh.test')
        ->and($log->description)->toContain('baru@contoh.test');
});

it('mencatat audit log saat admin menghapus akun', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create([
        'role' => 'perangkat_desa',
        'name' => 'Perangkat Hapus',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus');

    $log = logAuditTerbaru();

    expect($log->action->value)->toBe('user_deleted')
        ->and($log->user_id)->toBe($admin->id)
        ->and($log->target_id)->toBe($target->id)
        ->and($log->description)->toBe('Admin menghapus akun "Perangkat Hapus" dengan role perangkat_desa');
});

it('mempertahankan audit log setelah target dihapus tanpa error foreign key', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'perangkat_desa']);
    $target = User::factory()->create([
        'role' => 'perangkat_desa',
        'name' => 'Perangkat Dihapus',
    ]);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->call('hapus');

    expect(User::find($target->id))->toBeNull()
        ->and(AuditLog::where('target_id', $target->id)->count())->toBe(1);
});

it('tidak mencatat audit log ketika aksi ditolak', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $targetAdmin = User::factory()->create(['role' => 'admin', 'is_active' => true]);

    Livewire::actingAs($admin)
        ->test(PenggunaIndex::class)
        ->call('toggleStatus', $targetAdmin->id)
        ->assertSee('Akun dengan role admin tidak dapat diubah statusnya melalui halaman ini.');

    expect($targetAdmin->fresh()->is_active)->toBeTrue()
        ->and(AuditLog::count())->toBe(0);
});

it('tidak menyimpan password ke dalam audit log', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $target = User::factory()->create(['role' => 'masyarakat']);

    Livewire::actingAs($admin)
        ->test(Detail::class, ['user' => $target])
        ->set('password', 'rahasia-sangat-baru')
        ->set('password_confirmation', 'rahasia-sangat-baru')
        ->call('resetPassword');

    $deskripsi = AuditLog::pluck('description')->implode(' ');

    expect(AuditLog::count())->toBe(1)
        ->and(str_contains($deskripsi, 'rahasia-sangat-baru'))->toBeFalse()
        ->and(str_contains($deskripsi, $target->getAuthPassword()))->toBeFalse();
});

it('menampilkan deskripsi dan label aksi pada halaman audit log', function () {
    $admin = User::factory()->create(['role' => 'admin', 'name' => 'Sang Admin']);
    $target = User::factory()->create([
        'role' => 'masyarakat',
        'is_active' => true,
        'name' => 'Warga Tampil',
    ]);

    Livewire::actingAs($admin)
        ->test(PenggunaIndex::class)
        ->call('toggleStatus', $target->id);

    $this->actingAs($admin)
        ->get(route('admin.audit-log'))
        ->assertOk()
        ->assertSee('Penonaktifan Akun')
        ->assertSee('Warga Tampil')
        ->assertSee('Sang Admin');
});

it('pagination berfungsi pada halaman audit log', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    foreach (range(1, 20) as $i) {
        AuditLog::create([
            'user_id' => $admin->id,
            'action' => AuditAction::USER_UPDATED,
            'target_type' => User::class,
            'target_id' => 1,
            'description' => 'Admin mengubah akun "'.$i.'"',
        ]);
    }

    Livewire::actingAs($admin)
        ->test(AuditLogIndex::class)
        ->assertSee('Berikutnya')
        ->call('nextPage')
        ->assertSet('paginators.page', 2);
});

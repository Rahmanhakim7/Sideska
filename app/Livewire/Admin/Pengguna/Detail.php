<?php

namespace App\Livewire\Admin\Pengguna;

use App\Enums\AuditAction;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Detail extends Component
{
    public User $user;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(User $user): void
    {
        $this->user = $user->load('penduduk');
        $this->name = $user->name;
        $this->email = $user->email;
    }

    public function perbaruiAkun(): void
    {
        $pelaku = Auth::user();

        if ($pelaku === null || $pelaku->role !== 'admin') {
            session()->flash(
                'error',
                'Anda tidak memiliki akses untuk melakukan tindakan ini.'
            );

            return;
        }

        if ($this->user->role === 'admin') {
            session()->flash(
                'error',
                'Akun dengan role admin tidak dapat diedit melalui halaman ini.'
            );

            return;
        }

        $this->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($this->user->id),
            ],
        ]);

        try {
            $namaLama = $this->user->name;
            $emailLama = $this->user->email;

            DB::transaction(function () use ($pelaku, $namaLama, $emailLama) {
                $this->user->name = $this->name;
                $this->user->email = $this->email;
                $this->user->save();

                $perubahan = [];

                if ($namaLama !== $this->user->name) {
                    $perubahan[] = 'nama: "'.$namaLama.'" menjadi "'.$this->user->name.'"';
                }

                if ($emailLama !== $this->user->email) {
                    $perubahan[] = 'email: "'.$emailLama.'" menjadi "'.$this->user->email.'"';
                }

                $deskripsi = 'Admin mengubah akun "'.$this->user->name.'"';

                if ($perubahan !== []) {
                    $deskripsi .= ' ('.implode(', ', $perubahan).')';
                }

                app(AuditLogService::class)->catat(
                    $pelaku,
                    AuditAction::USER_UPDATED,
                    $deskripsi,
                    $this->user
                );
            });

            $this->user->refresh();

            $this->name = $this->user->name;
            $this->email = $this->user->email;

            session()->flash(
                'success',
                'Data akun pengguna berhasil diperbarui.'
            );
        } catch (\Throwable $e) {
            report($e);
            session()->flash(
                'error',
                'Data akun pengguna gagal diperbarui. Silakan coba lagi.'
            );
        }
    }

    public function resetPassword(): void
    {
        $pelaku = Auth::user();

        if ($pelaku === null || $pelaku->role !== 'admin') {
            session()->flash(
                'error',
                'Anda tidak memiliki akses untuk melakukan tindakan ini.'
            );

            return;
        }

        if ($this->user->role === 'admin') {
            session()->flash(
                'error',
                'Akun dengan role admin tidak dapat direset password melalui halaman ini.'
            );

            return;
        }

        $this->validate([
            'password' => [
                'required',
                'string',
                'min:8',
                'same:password_confirmation',
            ],
            'password_confirmation' => [
                'required',
                'string',
                'min:8',
            ],
        ]);

        try {
            DB::transaction(function () use ($pelaku) {
                $this->user->password = $this->password;
                $this->user->remember_token = Str::random(60);
                $this->user->save();

                DB::table('sessions')
                    ->where('user_id', $this->user->id)
                    ->delete();

                app(AuditLogService::class)->catat(
                    $pelaku,
                    AuditAction::USER_PASSWORD_RESET,
                    'Admin mereset password akun "'.$this->user->name.'"',
                    $this->user
                );
            });

            $this->password = '';
            $this->password_confirmation = '';

            session()->flash(
                'success',
                'Password pengguna berhasil direset.'
            );
        } catch (\Throwable $e) {
            report($e);
            session()->flash(
                'error',
                'Password pengguna gagal direset. Silakan coba lagi.'
            );
        }
    }

    public function hapus()
    {
        $pelaku = Auth::user();

        if ($pelaku === null || $pelaku->role !== 'admin') {
            session()->flash(
                'error',
                'Anda tidak memiliki akses untuk melakukan tindakan ini.'
            );

            return;
        }

        if (Auth::id() === $this->user->id) {
            session()->flash(
                'error',
                'Anda tidak dapat menghapus akun Anda sendiri.'
            );

            return;
        }

        if (! in_array($this->user->role, ['perangkat_desa', 'kepala_desa'], true)) {
            session()->flash(
                'error',
                'Akun dengan role ini tidak dapat dihapus melalui halaman ini.'
            );

            return;
        }

        $pesanDitolak = null;

        try {
            DB::transaction(function () use ($pelaku, &$pesanDitolak) {
                $jumlahSebelumHapus = User::where('role', $this->user->role)
                    ->lockForUpdate()
                    ->get(['id'])
                    ->count();

                if ($jumlahSebelumHapus <= 1) {
                    $pesanDitolak = $this->user->role === 'perangkat_desa'
                        ? 'Akun Perangkat Desa terakhir tidak dapat dihapus.'
                        : 'Akun Kepala Desa terakhir tidak dapat dihapus.';

                    return;
                }

                DB::table('sessions')
                    ->where('user_id', $this->user->id)
                    ->delete();

                DB::table('password_reset_tokens')
                    ->where('email', $this->user->email)
                    ->delete();

                DB::table('notifications')
                    ->where('notifiable_type', User::class)
                    ->where('notifiable_id', $this->user->id)
                    ->delete();

                app(AuditLogService::class)->catat(
                    $pelaku,
                    AuditAction::USER_DELETED,
                    'Admin menghapus akun "'.$this->user->name
                        .'" dengan role '.$this->user->role,
                    $this->user
                );

                $this->user->delete();
            });
        } catch (\Throwable $e) {
            report($e);
            session()->flash(
                'error',
                'Akun pengguna gagal dihapus. Silakan coba lagi.'
            );

            return null;
        }

        if ($pesanDitolak !== null) {
            session()->flash('error', $pesanDitolak);

            return null;
        }

        session()->flash(
            'success',
            'Akun pengguna berhasil dihapus.'
        );

        return $this->redirectRoute(
            'admin.pengguna',
            navigate: true
        );
    }

    public function render()
    {
        return view('livewire.admin.pengguna.detail');
    }
}

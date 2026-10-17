<?php

namespace App\Livewire\Admin\Pengguna;

use App\Enums\AuditAction;
use App\Enums\StatusKependudukan;
use App\Models\User;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function toggleStatus(User $user): void
    {
        $pelaku = Auth::user();

        if ($pelaku === null || $pelaku->role !== 'admin') {
            session()->flash(
                'error',
                'Hanya admin yang dapat mengubah status pengguna.'
            );

            return;
        }

        if (Auth::id() === $user->id) {
            session()->flash(
                'error',
                'Anda tidak dapat mengubah status akun Anda sendiri.'
            );

            return;
        }

        if ($user->role === 'admin') {
            session()->flash(
                'error',
                'Akun dengan role admin tidak dapat diubah statusnya melalui halaman ini.'
            );

            return;
        }

        if (! $user->is_active && $user->penduduk?->status_kependudukan === StatusKependudukan::PINDAH->value) {
            session()->flash(
                'error',
                'Pengguna ini memiliki penduduk berstatus Pindah dan harus diproses melalui workflow mutasi yang sesuai.'
            );

            return;
        }

        try {
            DB::transaction(function () use ($user, $pelaku) {
                $user->is_active = ! $user->is_active;
                $user->save();

                if (! $user->is_active) {
                    DB::table('sessions')
                        ->where('user_id', $user->id)
                        ->delete();
                }

                app(AuditLogService::class)->catat(
                    $pelaku,
                    $user->is_active
                        ? AuditAction::USER_ACTIVATED
                        : AuditAction::USER_DEACTIVATED,
                    'Admin '.($user->is_active ? 'mengaktifkan' : 'menonaktifkan')
                        .' akun "'.$user->name.'"',
                    $user
                );
            });

            session()->flash(
                'success',
                $user->is_active
                    ? 'Pengguna berhasil diaktifkan.'
                    : 'Pengguna berhasil dinonaktifkan.'
            );
        } catch (\Throwable $e) {
            report($e);
            session()->flash(
                'error',
                'Status pengguna gagal diperbarui. Silakan coba lagi.'
            );
        }
    }

    public function render()
    {
        $pengguna = User::query()
            ->with('penduduk')
            ->when($this->search !== '', function ($query) {
                $query->where(function ($query) {
                    $query->where(
                        'name',
                        'ilike',
                        '%'.$this->search.'%'
                    )->orWhere(
                        'email',
                        'ilike',
                        '%'.$this->search.'%'
                    );
                });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.admin.pengguna.index', [
            'pengguna' => $pengguna,
        ]);
    }
}

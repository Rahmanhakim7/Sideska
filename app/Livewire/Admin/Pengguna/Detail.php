<?php

namespace App\Livewire\Admin\Pengguna;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Detail extends Component
{
    public User $user;

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(User $user): void
    {
        $this->user = $user->load('penduduk');
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
            DB::transaction(function () {
                $this->user->password = $this->password;
                $this->user->remember_token = Str::random(60);
                $this->user->save();

                DB::table('sessions')
                    ->where('user_id', $this->user->id)
                    ->delete();
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

    public function render()
    {
        return view('livewire.admin.pengguna.detail');
    }
}

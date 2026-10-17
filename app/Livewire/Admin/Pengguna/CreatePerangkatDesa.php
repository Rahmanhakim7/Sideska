<?php

namespace App\Livewire\Admin\Pengguna;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class CreatePerangkatDesa extends Component
{
    public const ROLE = 'perangkat_desa';

    public $name;

    public $email;

    public $password;

    public $password_confirmation;

    protected function rules(): array
    {
        return [
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
                'unique:users,email',
            ],
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
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'email',
            'password' => 'password',
            'password_confirmation' => 'konfirmasi password',
        ];
    }

    public function simpan()
    {
        $pelaku = Auth::user();

        if ($pelaku === null || $pelaku->role !== 'admin') {
            session()->flash(
                'error',
                'Anda tidak memiliki akses untuk melakukan tindakan ini.'
            );

            return null;
        }

        $this->validate();

        try {
            DB::transaction(function () {
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => $this->password,
                ]);
                $user->role = self::ROLE;
                $user->is_active = true;
                $user->email_verified_at = now();
                $user->save();
            });

            session()->flash(
                'success',
                'Akun perangkat desa berhasil dibuat.'
            );

            return $this->redirectRoute(
                'admin.pengguna',
                navigate: true
            );
        } catch (\Throwable $e) {
            report($e);
            session()->flash(
                'error',
                'Akun perangkat desa gagal dibuat. Silakan coba lagi.'
            );

            return null;
        }
    }

    public function render()
    {
        return view(
            'livewire.admin.pengguna.create-perangkat-desa'
        );
    }
}

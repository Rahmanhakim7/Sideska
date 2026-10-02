<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;
    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $role = auth()->user()->role;
        if ($role === 'admin') {
            $this->redirectRoute('admin.dashboard', navigate: true);
            return;
        }
        if ($role === 'perangkat_desa') {
            $this->redirectRoute('perangkat-desa.dashboard', navigate: true);
            return;
        }
        if ($role === 'kepala_desa') {
            $this->redirectRoute('kepala.dashboard', navigate: true);
            return;
        }
        if ($role === 'masyarakat') {
            $this->redirectRoute('dashboard', navigate: true);
            return;
        }
        abort(403, 'Role pengguna tidak dikenali.');
    }
};

?>
<div class="flex flex-col md:flex-row bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">

    {{-- KIRI --}}
    <div class="w-full md:w-5/12 bg-slate-50 flex flex-col items-center justify-center p-10 md:p-12 text-center">

        <img src="{{ asset('images/logo-sideska.png') }}" alt="Logo SIDESKA" class="w-auto h-32 object-contain">

        <h1 class="mt-6 text-2xl font-bold text-slate-800">
            SIDESKA
        </h1>

        <p class="mt-2 text-sm text-slate-500">
            Sistem Informasi Desa
        </p>

        <p class="mt-1 text-xs text-slate-400 max-w-xs">
            Pelayanan administrasi desa yang mudah dan cepat
        </p>

    </div>

    {{-- KANAN --}}
    <div class="w-full md:w-7/12 p-8 md:p-12">

        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-slate-800">
                Selamat Datang
            </h2>

            <p class="mt-2 text-sm text-slate-500">
                Silakan masuk untuk melanjutkan
            </p>
        </div>

        <x-auth-session-status class="mb-5" :status="session('status')" />

        <form wire:submit="login" class="space-y-6">

            {{-- EMAIL --}}
            <div>
                <x-input-label for="email" :value="__('Email')"
                    class="block text-sm font-semibold text-slate-700 mb-2" />

                <input wire:model="form.email" id="email" type="email" name="email" required autofocus
                    autocomplete="username" placeholder="Masukkan email Anda"
                    class="block w-full h-11 rounded-lg
                           border border-slate-300
                           bg-white
                           px-4
                           text-sm text-slate-700
                           placeholder:text-slate-400
                           shadow-sm
                           focus:border-blue-600
                           focus:ring-2
                           focus:ring-blue-100
                           focus:outline-none
                           transition">

                <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
            </div>

            {{-- PASSWORD --}}
            <div>
                <x-input-label for="password" :value="__('Password')"
                    class="block text-sm font-semibold text-slate-700 mb-2" />

                <input wire:model="form.password" id="password" type="password" name="password" required
                    autocomplete="current-password" placeholder="Masukkan password Anda"
                    class="block w-full h-11 rounded-lg
                           border border-slate-300
                           bg-white
                           px-4
                           text-sm text-slate-700
                           placeholder:text-slate-400
                           shadow-sm
                           focus:border-blue-600
                           focus:ring-2
                           focus:ring-blue-100
                           focus:outline-none
                           transition">

                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
            </div>

            {{-- REMEMBER + LUPA PASSWORD --}}
            <div class="flex items-center justify-between pt-1">

                <label for="remember" class="flex items-center cursor-pointer">
                    <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                        class="w-4 h-4 rounded
                               border-slate-300
                               text-blue-700
                               focus:ring-2
                               focus:ring-blue-200">

                    <span class="ml-2 text-xs text-slate-600">
                        Ingat saya
                    </span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate
                        class="text-xs font-medium
                               text-blue-600
                               hover:text-blue-800
                               hover:underline
                               transition">
                        Lupa password?
                    </a>
                @endif

            </div>

            {{-- BUTTON --}}
            <div class="pt-2">

                <button type="submit" wire:loading.attr="disabled"
                    class="w-full h-11
                           flex items-center justify-center
                           gap-2
                           rounded-lg
                           bg-blue-800
                           hover:bg-blue-700
                           active:bg-blue-900
                           text-white
                           text-sm
                           font-semibold
                           shadow-md
                           focus:outline-none
                           focus:ring-2
                           focus:ring-blue-600
                           focus:ring-offset-2
                           transition-all
                           duration-200
                           disabled:opacity-60
                           disabled:cursor-not-allowed">

                    <span wire:loading.remove class="flex items-center justify-center gap-2">
                        MASUK KE SIDESKA
                    </span>

                    <span wire:loading class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4" />

                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                        </svg>

                        Memproses...
                    </span>

                </button>

            </div>

        </form>

    </div>

</div>

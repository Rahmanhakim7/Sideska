<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div x-data="{ sidebarOpen: false }">
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-40 bg-black/40 lg:hidden"
        style="display: none;">
    </div>
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200
               transform transition-transform duration-300
               lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="flex items-center h-16 px-5 border-b border-slate-200">
            <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center gap-3">
                <div class="flex items-center justify-center w-10 h-10 rounded-xl bg-white overflow-hidden">
                    <img src="{{ asset('images/logo-sideska.png') }}" alt="Logo SIDESKA"
                        class="w-full h-full object-contain">
                </div>
                <div>
                    <div class="text-lg font-bold text-slate-800">
                        SIDESKA
                    </div>

                    <div class="text-[10px] text-slate-400">
                        Sistem Informasi Desa
                    </div>
                </div>
            </a>
        </div>
        <nav class="px-3 py-4 space-y-1 overflow-y-auto h-[calc(100vh-9rem)]">
            @if (auth()->user()->role === 'admin')
                @include('livewire.navigation.admin')
            @endif

            @if (auth()->user()->role === 'perangkat_desa')
                @include('livewire.navigation.perangkat-desa')
            @endif

            @if (auth()->user()->role === 'kepala_desa')
                @include('livewire.navigation.kepala-desa')
            @endif

            @if (auth()->user()->role === 'masyarakat')
                @include('livewire.navigation.masyarakat')
            @endif
        </nav>
        <div class="absolute bottom-0 left-0 right-0 p-3 bg-white border-t border-slate-200">
            <div class="flex items-center gap-2">
                <a href="{{ route('profile') }}" wire:navigate
                    class="flex-1 flex items-center gap-2 px-3 py-2 rounded-lg
                           text-sm text-slate-600 hover:bg-slate-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 19a6 6 0 00-12 0m6-10a4 4 0 110-8 4 4 0 010 8z" />
                    </svg>
                    Profil
                </a>
                <button wire:click="logout" type="button"
                    class="flex items-center justify-center w-10 h-10 rounded-lg
                           text-slate-500 hover:bg-red-50 hover:text-red-600"
                    title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H9m4 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />
                    </svg>
                </button>
            </div>
        </div>
    </aside>
    <div class="lg:hidden fixed top-0 left-0 right-0 z-30 h-16 bg-white border-b border-slate-200">
        <div class="flex items-center justify-between h-full px-4">
            <button @click="sidebarOpen = true" type="button" class="p-2 rounded-lg text-slate-600 hover:bg-slate-100">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <span class="font-bold text-slate-800">
                SIDESKA
            </span>
            <div class="w-10"></div>
        </div>
    </div>
</div>

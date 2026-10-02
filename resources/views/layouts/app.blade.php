<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIDESKA') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link
        href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap"
        rel="stylesheet"
    />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="font-sans antialiased bg-slate-100 text-slate-800">
    <livewire:layout.navigation />
    <main class="min-h-screen lg:ml-64">
        <header class="sticky top-0 z-30 h-16 bg-white border-b border-slate-200">
            <div class="flex items-center justify-between h-full px-6">
                <div>
                    <h1 class="text-lg font-semibold text-slate-800">
                        Dashboard
                    </h1>
                    <p class="text-xs text-slate-500">
                        Sistem Informasi Desa
                    </p>
                </div>
                <div
                    x-data="{ open: false }"
                    class="relative"
                >
                    <button
                        type="button"
                        @click="open = !open"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl
                               hover:bg-slate-50 transition-colors duration-150"
                    >
                        <div
                            class="flex items-center justify-center w-9 h-9
                                   rounded-full bg-blue-100
                                   text-blue-700 font-semibold"
                        >
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden text-left sm:block">
                            <p class="text-sm font-semibold text-slate-800">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-slate-500 capitalize">
                                {{ str_replace('_', ' ', auth()->user()->role) }}
                            </p>
                        </div>
                        <svg
                            class="w-4 h-4 text-slate-400 transition-transform duration-150"
                            :class="{ 'rotate-180': open }"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </button>
                    <div
                        x-show="open"
                        @click.outside="open = false"
                        x-transition
                        style="display: none;"
                        class="absolute right-0 mt-2 w-56
                               bg-white border border-slate-200
                               rounded-xl shadow-lg overflow-hidden"
                    >
                        <div class="px-4 py-3 border-b border-slate-100">
                            <p class="text-sm font-semibold text-slate-800 truncate">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-slate-500 capitalize">
                                {{ str_replace('_', ' ', auth()->user()->role) }}
                            </p>
                        </div>
                        <a
                            href="{{ route('profile') }}"
                            wire:navigate
                            @click="open = false"
                            class="flex items-center gap-3 px-4 py-3
                                   text-sm text-slate-600
                                   hover:bg-slate-50
                                   transition-colors duration-150"
                        >
                            <svg
                                class="w-5 h-5 text-slate-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 19a6 6 0 00-12 0m6-10a4 4 0 110-8 4 4 0 010 8z"
                                />
                            </svg>
                            <span>Profil</span>
                        </a>
                    </div>
                </div>
            </div>
        </header>
        <div class="p-6 bg-white">
            {{ $slot }}
        </div>
    </main>
    @livewireScripts
</body>
</html>
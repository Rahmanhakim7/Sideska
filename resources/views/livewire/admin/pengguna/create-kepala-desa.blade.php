<div class="w-full">
    <div class="mb-6">
        <div class="flex flex-col gap-1">
            <h1 class="text-xl font-bold text-slate-800">
                Tambah Kepala Desa
            </h1>
            <p class="text-sm text-slate-500">
                Tambahkan akun SIDESKA untuk kepala desa.
                Akun ini tidak memerlukan data penduduk.
            </p>
        </div>
    </div>

    @if (session()->has('success'))
        <div
            class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200
                   bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>

            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="mb-6 flex items-start gap-3 rounded-xl border border-red-200
                   bg-red-50 px-4 py-3 text-sm text-red-700">
            <svg class="mt-0.5 h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
            </svg>

            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form wire:submit.prevent="simpan" class="space-y-6">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="p-6">

                <div class="mb-6 flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-600">
                        <span class="text-sm font-bold text-white">
                            1
                        </span>
                    </div>

                    <div>
                        <h2 class="text-base font-bold text-slate-800">
                            Akun SIDESKA
                        </h2>

                        <p class="text-xs text-slate-500">
                            Akun login untuk kepala desa
                        </p>
                    </div>
                </div>

                {{-- NAMA --}}
                <div class="mb-5">
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                        Nama
                        <span class="text-red-500">*</span>
                    </label>

                    <input id="name" type="text" wire:model="name" placeholder="Masukkan nama lengkap"
                        class="w-full rounded-lg border border-slate-300
                               bg-white px-4 py-3 text-sm text-slate-700
                               outline-none placeholder:text-slate-400
                               transition focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">

                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div class="mb-5">
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                        Email
                        <span class="text-red-500">*</span>
                    </label>

                    <input id="email" type="email" wire:model="email" autocomplete="email"
                        placeholder="contoh@email.com"
                        class="w-full rounded-lg border border-slate-300
                               bg-white px-4 py-3 text-sm text-slate-700
                               outline-none placeholder:text-slate-400
                               transition focus:border-blue-500
                               focus:ring-2 focus:ring-blue-100">

                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- PASSWORD --}}
                <div class="mb-5">
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                        Password
                        <span class="text-red-500">*</span>
                    </label>

                    <div x-data="{ showPassword: false }" class="relative">
                        <input id="password" wire:model="password" autocomplete="new-password"
                            placeholder="Minimal 8 karakter" :type="showPassword ? 'text' : 'password'"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 pr-11 text-sm
                                   text-slate-700 outline-none
                                   placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">

                        <button type="button" @click="showPassword = !showPassword"
                            class="absolute right-3 top-1/2
                                   -translate-y-1/2 text-slate-400
                                   transition hover:text-slate-600"
                            :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'">

                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M2.25 12s3.75-6 9.75-6 9.75 6 9.75 6-3.75 6-9.75 6-9.75-6-9.75-6z" />
                                <circle cx="12" cy="12" r="3" stroke-width="1.5" />
                            </svg>

                            <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3l18 18
                                       M10.58 10.58a2 2 0 002.84 2.84
                                       M9.88 5.09A9.77 9.77 0 0112 4.75
                                       c6 0 9.75 6 9.75 6a16.9 16.9 0 01-3.05 3.95
                                       M6.61 6.61C3.88 8.39 2.25 10.75 2.25 10.75s3.75 6 9.75 6
                                       c1.16 0 2.22-.2 3.17-.53" />
                            </svg>
                        </button>
                    </div>

                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- KONFIRMASI PASSWORD --}}
                <div class="mb-5">
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">
                        Konfirmasi Password
                        <span class="text-red-500">*</span>
                    </label>

                    <div x-data="{ showConfirmation: false }" class="relative">
                        <input id="password_confirmation" wire:model="password_confirmation"
                            autocomplete="new-password" placeholder="Ulangi password"
                            :type="showConfirmation ? 'text' : 'password'"
                            class="w-full rounded-lg border border-slate-300
                                   bg-white px-4 py-3 pr-11 text-sm
                                   text-slate-700 outline-none
                                   placeholder:text-slate-400
                                   transition focus:border-blue-500
                                   focus:ring-2 focus:ring-blue-100">

                        <button type="button" @click="showConfirmation = !showConfirmation"
                            class="absolute right-3 top-1/2
                                   -translate-y-1/2 text-slate-400
                                   transition hover:text-slate-600"
                            :aria-label="showConfirmation ? 'Sembunyikan password' : 'Tampilkan password'">

                            <svg x-show="!showConfirmation" class="h-5 w-5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M2.25 12s3.75-6 9.75-6 9.75 6 9.75 6-3.75 6-9.75 6-9.75-6-9.75-6z" />
                                <circle cx="12" cy="12" r="3" stroke-width="1.5" />
                            </svg>

                            <svg x-show="showConfirmation" x-cloak class="h-5 w-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3l18 18
                                       M10.58 10.58a2 2 0 002.84 2.84
                                       M9.88 5.09A9.77 9.77 0 0112 4.75
                                       c6 0 9.75 6 9.75 6a16.9 16.9 0 01-3.05 3.95
                                       M6.61 6.61C3.88 8.39 2.25 10.75 2.25 10.75s3.75 6 9.75 6
                                       c1.16 0 2.22-.2 3.17-.53" />
                            </svg>
                        </button>
                    </div>

                    @error('password_confirmation')
                        <p class="mt-1.5 text-xs text-red-600">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- INFO AKUN --}}
                <div class="flex gap-3 rounded-xl border border-blue-100 bg-blue-50 p-4">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="9" stroke-width="1.5" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M12 10v6m0-9h.01" />
                    </svg>

                    <p class="text-xs leading-5 text-slate-600">
                        Akun akan otomatis dibuat dengan role
                        <span class="font-semibold text-blue-700">
                            Kepala Desa
                        </span>
                        dan langsung aktif tanpa data penduduk.
                    </p>
                </div>

            </div>
        </div>

        {{-- ACTION --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:items-center sm:justify-between">

            {{-- BATAL --}}
            <a href="{{ route('admin.pengguna') }}" wire:navigate
                class="inline-flex items-center justify-center gap-2
                       rounded-lg border border-slate-300 bg-white
                       px-5 py-2.5 text-sm font-semibold text-slate-700
                       transition hover:bg-slate-50
                       focus:outline-none focus:ring-2
                       focus:ring-slate-300 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Batal
            </a>

            {{-- SIMPAN --}}
            <button type="submit" wire:loading.attr="disabled" wire:target="simpan"
                class="inline-flex items-center justify-center gap-2
                       rounded-lg bg-blue-600 px-6 py-2.5
                       text-sm font-semibold text-white
                       transition hover:bg-blue-700
                       focus:outline-none focus:ring-2
                       focus:ring-blue-500 focus:ring-offset-2
                       disabled:cursor-not-allowed
                       disabled:opacity-50">

                <span wire:loading.remove wire:target="simpan" class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5 12h14m-6-6l6 6-6 6" />
                    </svg>
                    Simpan
                </span>

                <span wire:loading wire:target="simpan" class="inline-flex items-center gap-2">
                    <svg class="h-4 w-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                    </svg>
                    Menyimpan...
                </span>
            </button>

        </div>
    </form>
</div>

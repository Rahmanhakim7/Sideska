@php
    $penduduk = $user->penduduk;

    $roleBadge = match ($user->role) {
        'admin' => 'bg-purple-100 text-purple-700',
        'perangkat_desa' => 'bg-blue-100 text-blue-700',
        'kepala_desa' => 'bg-emerald-100 text-emerald-700',
        'masyarakat' => 'bg-slate-100 text-slate-600',
        default => 'bg-slate-100 text-slate-600',
    };

    $roleLabel = match ($user->role) {
        'admin' => 'Admin',
        'perangkat_desa' => 'Perangkat Desa',
        'kepala_desa' => 'Kepala Desa',
        'masyarakat' => 'Masyarakat',
        default => ucwords(str_replace('_', ' ', $user->role)),
    };
@endphp

<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('admin.pengguna') }}" wire:navigate
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                    title="Kembali">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </a>

                <h1 class="text-2xl font-bold text-slate-800">
                    Detail Pengguna
                </h1>
            </div>

            <p class="mt-1 text-sm text-slate-500">
                Informasi akun SIDESKA dan data penduduk yang terhubung.
            </p>
        </div>

        <a href="{{ route('admin.pengguna') }}" wire:navigate
            class="inline-flex w-fit items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
    </div>

    {{-- FLASH --}}
    @if (session()->has('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-6 py-4 text-sm text-emerald-700">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="rounded-xl border border-red-200 bg-red-50 px-6 py-4 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    {{-- INFORMASI AKUN --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-bold text-slate-800">
                Informasi Akun
            </h2>
            <p class="mt-0.5 text-sm text-slate-500">
                Data akun pengguna SIDESKA.
            </p>
        </div>

        <div class="px-6 py-6">
            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">
                <div
                    class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-100 text-2xl font-bold text-blue-700 ring-4 ring-blue-50">
                    @if ($penduduk?->foto_url)
                        <img src="{{ $penduduk->foto_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                    @else
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    @endif
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <h2 class="text-xl font-bold text-slate-800">
                            {{ $user->name }}
                        </h2>

                        <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $roleBadge }}">
                            {{ $roleLabel }}
                        </span>

                        @if ($user->is_active)
                            <span
                                class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                Aktif
                            </span>
                        @else
                            <span
                                class="inline-flex rounded-full bg-red-100 px-2.5 py-1 text-xs font-medium text-red-700">
                                Nonaktif
                            </span>
                        @endif
                    </div>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $user->email }}
                    </p>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-x-8 gap-y-5 border-t border-slate-100 pt-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nama
                    </dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-700">
                        {{ $user->name }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Email
                    </dt>
                    <dd class="mt-1 break-all text-sm text-slate-700">
                        {{ $user->email }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Role
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $roleLabel }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Status
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Email Terverifikasi
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        @if ($user->email_verified_at)
                            Terverifikasi
                            <span class="text-slate-400">
                                ({{ $user->email_verified_at->format('d M Y H:i') }})
                            </span>
                        @else
                            Belum Terverifikasi
                        @endif
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Dibuat Pada
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $user->created_at?->format('d M Y H:i') ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Terakhir Diperbarui
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $user->updated_at?->format('d M Y H:i') ?? '-' }}
                    </dd>
                </div>
            </div>
        </div>
    </div>

    @if ($penduduk)
        {{-- PROFIL PENDUDUK --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-bold text-slate-800">
                    Data Penduduk
                </h2>
                <p class="mt-0.5 text-sm text-slate-500">
                    Data kependudukan yang terhubung dengan akun ini.
                </p>
            </div>

            <div class="px-6 py-6">
                <div class="flex flex-wrap items-center gap-2">
                    <h3 class="text-lg font-bold text-slate-800">
                        {{ $penduduk->nama }}
                    </h3>

                    @if ($penduduk->status_kependudukan === 'Tetap')
                        <span
                            class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                            Tetap
                        </span>
                    @elseif ($penduduk->status_kependudukan === 'Pendatang')
                        <span
                            class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                            Pendatang
                        </span>
                    @elseif ($penduduk->status_kependudukan === 'Pindah')
                        <span
                            class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">
                            Pindah
                        </span>
                    @else
                        <span
                            class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                            Tidak Diketahui
                        </span>
                    @endif
                </div>

                <p class="mt-1 text-sm text-slate-500">
                    NIK: {{ $penduduk->nik }} &middot; No. KK: {{ $penduduk->no_kk }}
                </p>
            </div>

            <div
                class="grid grid-cols-1 gap-x-8 gap-y-5 border-t border-slate-100 px-6 py-6 sm:grid-cols-2 lg:grid-cols-3">
                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        NIK
                    </dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-700">
                        {{ $penduduk->nik }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nomor KK
                    </dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-700">
                        {{ $penduduk->no_kk }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nama Lengkap
                    </dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-700">
                        {{ $penduduk->nama }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Tempat Lahir
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->tempat_lahir }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Tanggal Lahir
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->tanggal_lahir ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Jenis Kelamin
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Agama
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->agama }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Status Perkawinan
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->status_perkawinan }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Pendidikan
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->pendidikan }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Pekerjaan
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->pekerjaan }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Hubungan Keluarga
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->status_hubungan_keluarga }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Golongan Darah
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->golongan_darah ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Kewarganegaraan
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->kewarganegaraan }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nomor HP / WhatsApp
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->no_hp ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Status Kependudukan
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->status_kependudukan }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Disabilitas
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->disabilitas ? 'Ya' : 'Tidak' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nama Ayah
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->nama_ayah ?? '-' }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nama Ibu
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->nama_ibu ?? '-' }}
                    </dd>
                </div>
            </div>

            <div class="border-t border-slate-100 px-6 py-6">
                <dl>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Alamat Lengkap
                    </dt>
                    <dd class="mt-1 text-sm leading-6 text-slate-700">
                        {{ $penduduk->alamat }}
                    </dd>
                </dl>

                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3">
                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            RT
                        </dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-700">
                            {{ $penduduk->rt }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            RW
                        </dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-700">
                            {{ $penduduk->rw }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Dusun
                        </dt>
                        <dd class="mt-1 text-sm font-semibold text-slate-700">
                            {{ $penduduk->dusun }}
                        </dd>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- EMPTY STATE PENDUDUK --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-bold text-slate-800">
                    Data Penduduk
                </h2>
            </div>

            <div class="px-6 py-12 text-center">
                <div
                    class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM4 21a8 8 0 0 1 16 0" />
                    </svg>
                </div>

                <p class="text-sm font-medium text-slate-700">
                    Pengguna ini tidak memiliki data penduduk.
                </p>
                <p class="mt-1 text-sm text-slate-500">
                    Akun {{ $roleLabel }} tidak terhubung dengan data kependudukan.
                </p>
            </div>
        </div>
    @endif

    @if ($user->role !== 'admin')
        {{-- RESET PASSWORD --}}
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-6 py-4">
                <h2 class="text-base font-bold text-slate-800">
                    Reset Password
                </h2>
                <p class="mt-0.5 text-sm text-slate-500">
                    Atur ulang password akun ini. Sesi aktif pengguna akan dihapus dan wajib login ulang.
                </p>
            </div>

            <form wire:submit="resetPassword"
                wire:confirm="Apakah Anda yakin ingin mengatur ulang password pengguna ini?" class="px-6 py-6">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                            Password Baru
                        </label>
                        <input id="password" type="password" wire:model="password" autocomplete="new-password"
                            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        @error('password')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">
                            Konfirmasi Password Baru
                        </label>
                        <input id="password_confirmation" type="password" wire:model="password_confirmation"
                            autocomplete="new-password"
                            class="w-full rounded-lg border border-slate-300 bg-white px-4 py-2.5 text-sm text-slate-700 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                        @error('password_confirmation')
                            <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="submit" wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 rounded-lg bg-blue-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 disabled:cursor-not-allowed disabled:opacity-50">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- FOOTER ACTION --}}
    <div class="flex justify-end pb-2">
        <a href="{{ route('admin.pengguna') }}" wire:navigate
            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Daftar Pengguna
        </a>
    </div>
</div>

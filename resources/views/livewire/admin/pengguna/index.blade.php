<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Pengguna
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Daftar akun pengguna SIDESKA. Halaman ini hanya untuk melihat data.
            </p>
        </div>
        <div class="flex flex-col gap-2 sm:flex-row">
            <a href="{{ route('admin.pengguna.masyarakat.create') }}" wire:navigate
                class="inline-flex w-fit items-center rounded-lg border border-blue-700 bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 shadow-sm transition hover:bg-blue-50">
                + Masyarakat
            </a>
            <a href="{{ route('admin.pengguna.perangkat-desa.create') }}" wire:navigate
                class="inline-flex w-fit items-center rounded-lg border border-blue-700 bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 shadow-sm transition hover:bg-blue-50">
                + Perangkat Desa
            </a>
            <a href="{{ route('admin.pengguna.kepala-desa.create') }}" wire:navigate
                class="inline-flex w-fit items-center rounded-lg border border-blue-700 bg-white px-4 py-2.5 text-sm font-semibold text-blue-700 shadow-sm transition hover:bg-blue-50">
                + Kepala Desa
            </a>
        </div>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="min-w-0 flex-1">
                <label for="search" class="sr-only">
                    Cari pengguna
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.04 6.04a7.5 7.5 0 0 0 10.61 10.61Z" />
                        </svg>
                    </div>
                    <input id="search" type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari nama atau email..."
                        class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-12 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
            </div>
            <div class="shrink-0 text-sm leading-5 text-slate-500">
                <div>
                    Total:
                    <span class="font-semibold text-slate-700">
                        {{ $pengguna->total() }}
                    </span>
                </div>
                <div>
                    pengguna
                </div>
            </div>
        </div>
    </div>

    <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="border-b border-blue-100 bg-blue-50/70">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Nama
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Email
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Role
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Status
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Penduduk
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Dibuat
                        </th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wide text-blue-900">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($pengguna as $item)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                                        {{ strtoupper(substr($item->name, 0, 1)) }}
                                    </div>
                                    <div class="text-sm font-semibold text-slate-800">
                                        {{ $item->name }}
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                {{ $item->email }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @php
                                    $roleBadge = match ($item->role) {
                                        'admin' => 'bg-purple-100 text-purple-700',
                                        'perangkat_desa' => 'bg-blue-100 text-blue-700',
                                        'kepala_desa' => 'bg-emerald-100 text-emerald-700',
                                        'masyarakat' => 'bg-slate-100 text-slate-600',
                                        default => 'bg-slate-100 text-slate-600',
                                    };
                                @endphp
                                <span
                                    class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium {{ $roleBadge }}">
                                    {{ ucwords(str_replace('_', ' ', $item->role)) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if ($item->is_active)
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
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                @if ($item->penduduk)
                                    <div class="font-medium text-slate-700">
                                        {{ $item->penduduk->nama }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        NIK: {{ $item->penduduk->nik }}
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                {{ $item->created_at?->format('d M Y') ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <a href="{{ route('admin.pengguna.show', $item) }}" wire:navigate
                                    title="Detail pengguna"
                                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                                        <circle cx="12" cy="12" r="2.5" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div
                                        class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM4 21a8 8 0 0 1 16 0" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-700">
                                        Belum ada data pengguna.
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Data pengguna akan tampil di sini.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div wire:loading.flex wire:target="search,nextPage,previousPage,gotoPage"
            class="absolute inset-0 z-10 items-center justify-center bg-white">
            <div class="flex flex-col items-center justify-center">
                <svg class="h-8 w-8 animate-spin text-blue-700" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4">
                    </circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                    </path>
                </svg>
                <p class="mt-3 text-sm font-medium text-slate-600">
                    Memuat data pengguna...
                </p>
            </div>
        </div>
    </div>

    @if ($pengguna->hasPages())
        <div class="border-t border-slate-200 bg-white px-6 py-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-slate-500">
                    Menampilkan
                    <span class="font-semibold text-slate-700">
                        {{ $pengguna->firstItem() ?? 0 }}
                    </span>
                    sampai
                    <span class="font-semibold text-slate-700">
                        {{ $pengguna->lastItem() ?? 0 }}
                    </span>
                    dari
                    <span class="font-semibold text-slate-700">
                        {{ $pengguna->total() }}
                    </span>
                    pengguna
                </div>
                <div class="flex items-center gap-1">
                    @if ($pengguna->onFirstPage())
                        <span
                            class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-400">
                            Sebelumnya
                        </span>
                    @else
                        <button type="button" wire:click="previousPage" wire:loading.attr="disabled"
                            class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                            Sebelumnya
                        </button>
                    @endif
                    <div class="hidden items-center gap-1 sm:flex">
                        @foreach ($pengguna->getUrlRange(1, $pengguna->lastPage()) as $page => $url)
                            @if ($page == $pengguna->currentPage())
                                <span
                                    class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg bg-blue-800 px-3 text-sm font-semibold text-white shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }})"
                                    wire:loading.attr="disabled"
                                    class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                    @if ($pengguna->hasMorePages())
                        <button type="button" wire:click="nextPage" wire:loading.attr="disabled"
                            class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 text-sm font-medium text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 disabled:cursor-not-allowed disabled:opacity-50">
                            Berikutnya
                        </button>
                    @else
                        <span
                            class="inline-flex h-9 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 px-3 text-sm font-medium text-slate-400">
                            Berikutnya
                        </span>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

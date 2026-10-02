<div wire:init="loadData" class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Pelayanan Surat
        </h1>
        <p class="mt-1 text-sm text-slate-500">
            Kelola dan proses pengajuan surat dari masyarakat.
        </p>
    </div>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-5">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-slate-800">
                        Daftar Pengajuan
                    </h2>
                    <p class="mt-1 text-sm text-slate-500">
                        Daftar pengajuan surat dari masyarakat.
                    </p>
                </div>
                <div class="relative w-full sm:w-80">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                        <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m21 21-4.35-4.35m2.1-5.4a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" />
                        </svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama atau NIK..."
                        class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-11 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-2 focus:ring-blue-100">
                    <div wire:loading wire:target="search" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="h-4 w-4 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>

                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative">
            <div wire:loading.flex wire:target="loadData, search, previousPage, nextPage, gotoPage"
                class="absolute inset-0 z-10 items-center justify-center bg-white/70 backdrop-blur-[1px]">
                <div class="flex items-center gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-lg">
                    <svg class="h-5 w-5 animate-spin text-blue-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>

                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 01-4 4H4z"></path>
                    </svg>
                    <span class="text-sm font-medium text-slate-600">
                        Memuat data...
                    </span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px] text-left">
                    <thead class="border-b border-slate-200 bg-slate-50">
                        <tr>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Penduduk
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Jenis Surat
                            </th>

                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Tanggal Pengajuan
                            </th>
                            <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Status
                            </th>
                            <th
                                class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($pengajuan as $item)
                            <tr class="transition hover:bg-slate-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-700">
                                            {{ strtoupper(substr($item->penduduk->nama ?? '-', 0, 1)) }}
                                        </div>

                                        <div>
                                            <div class="font-medium text-slate-800">
                                                {{ $item->penduduk->nama ?? '-' }}
                                            </div>

                                            <div class="mt-0.5 text-xs text-slate-500">
                                                NIK: {{ $item->penduduk->nik ?? '-' }}
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-sm font-medium text-slate-700">
                                        {{ ucwords(str_replace('_', ' ', $item->jenis_surat)) }}
                                    </span>
                                </td>

                                <td class="px-6 py-4">
                                    <span class="text-sm text-slate-600">
                                        {{ $item->created_at?->format('d M Y') }}
                                    </span>

                                    <div class="mt-0.5 text-xs text-slate-400">
                                        {{ $item->created_at?->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">

                                    @php
                                        $status = $item->status->value;

                                        $statusClass = match ($status) {
                                            'diajukan' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200',
                                            'diverifikasi'
                                                => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200',
                                            'revisi'
                                                => 'bg-orange-50 text-orange-700 ring-1 ring-inset ring-orange-200',
                                            'disetujui'
                                                => 'bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-200',
                                            'ditolak' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-200',
                                            'menunggu_tanda_tangan'
                                                => 'bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-200',
                                            'selesai'
                                                => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200',
                                            default => 'bg-slate-50 text-slate-600 ring-1 ring-inset ring-slate-200',
                                        };

                                        $statusLabel = match ($status) {
                                            'menunggu_tanda_tangan' => 'Menunggu Tanda Tangan',
                                            default => ucwords(str_replace('_', ' ', $status)),
                                        };
                                    @endphp
                                    <span
                                        class="{{ $statusClass }} inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('perangkat-desa.pelayanan-surat.detail', $item->id) }}"
                                        wire:navigate
                                        class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                        Lihat

                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="m9 18 6-6-6-6" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div
                                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                                        <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h6l5 5v11a2 2 0 0 1-2 2Z" />
                                        </svg>
                                    </div>

                                    <h3 class="mt-4 text-sm font-semibold text-slate-700">
                                        Tidak ada pengajuan
                                    </h3>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Belum ada pengajuan surat yang ditemukan.
                                    </p>

                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
            @if ($pengajuan->hasPages())
                <div class="flex items-center justify-between border-t border-slate-200 px-6 py-4">
                    <div>
                        @if ($pengajuan->onFirstPage())
                            <span
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>

                                Sebelumnya
                            </span>
                        @else
                            <button type="button" wire:click="previousPage('page')" wire:loading.attr="disabled"
                                wire:target="previousPage"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 disabled:opacity-50">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>

                                Sebelumnya
                            </button>
                        @endif
                    </div>
                    <div class="hidden items-center gap-1 sm:flex">
                        @foreach ($pengajuan->getUrlRange(1, $pengajuan->lastPage()) as $page => $url)
                            @if ($page == $pengajuan->currentPage())
                                <span
                                    class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg bg-blue-600 px-3 text-sm font-semibold text-white">
                                    {{ $page }}
                                </span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }}, 'page')"
                                    class="inline-flex h-9 min-w-9 items-center justify-center rounded-lg px-3 text-sm font-medium text-slate-600 transition hover:bg-slate-100 hover:text-blue-700">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                    <div>
                        @if ($pengajuan->hasMorePages())
                            <button type="button" wire:click="nextPage('page')" wire:loading.attr="disabled"
                                wire:target="nextPage"
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 disabled:opacity-50">
                                Berikutnya

                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        @else
                            <span
                                class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-sm font-medium text-slate-400">
                                Berikutnya

                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        @endif
                    </div>

                </div>
            @endif
        </div>
    </div>
</div>

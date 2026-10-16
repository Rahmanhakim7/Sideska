<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Mutasi Penduduk
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Riwayat mutasi masuk dan mutasi keluar penduduk Desa Sekartejo.
            </p>
        </div>
        <a href="{{ route('perangkat-desa.mutasi-masuk.create') }}"
            class="inline-flex w-fit items-center rounded-lg bg-blue-800 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            + Mutasi Masuk
        </a>
    </div>

    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
            <div class="inline-flex flex-wrap items-center gap-2 rounded-lg border border-slate-200 bg-white p-1 shadow-sm">
                <button type="button" wire:click="$set('jenis', 'semua')" wire:loading.attr="disabled"
                    class="inline-flex items-center rounded-md px-3 py-2 text-xs font-semibold transition sm:text-sm {{ $jenis === 'semua' ? 'bg-blue-700 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    Semua
                </button>
                <button type="button" wire:click="$set('jenis', 'masuk')" wire:loading.attr="disabled"
                    class="inline-flex items-center rounded-md px-3 py-2 text-xs font-semibold transition sm:text-sm {{ $jenis === 'masuk' ? 'bg-blue-700 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    Masuk
                </button>
                <button type="button" wire:click="$set('jenis', 'keluar')" wire:loading.attr="disabled"
                    class="inline-flex items-center rounded-md px-3 py-2 text-xs font-semibold transition sm:text-sm {{ $jenis === 'keluar' ? 'bg-blue-700 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100' }}">
                    Keluar
                </button>
            </div>
            <div class="ml-auto hidden text-sm text-slate-500 sm:block">
                Total: <span class="font-semibold text-slate-700">{{ $mutasi->total() }}</span>
            </div>
        </div>
    </div>

    <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="border-b border-blue-100 bg-blue-50/70">
                    <tr>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Penduduk
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            NIK
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Jenis Mutasi
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Tanggal Mutasi
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Alamat Asal
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Alamat Tujuan
                        </th>
                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Petugas
                        </th>
                        <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wide text-blue-900">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($mutasi as $item)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                                        @if ($item->penduduk?->foto_url)
                                            <img src="{{ $item->penduduk->foto_url }}" alt="{{ $item->penduduk?->nama }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            {{ strtoupper(substr($item->penduduk?->nama ?? 'M', 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-800">
                                            {{ $item->penduduk?->nama ?? '-' }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            KK: {{ $item->penduduk?->no_kk ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                {{ $item->penduduk?->nik ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                @if ($item->jenis->value === 'masuk')
                                    <span
                                        class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                                        Masuk
                                    </span>
                                @else
                                    <span
                                        class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">
                                        Keluar
                                    </span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                {{ $item->tanggal_mutasi?->format('d M Y') ?? '-' }}
                            </td>
                            <td class="max-w-xs px-6 py-4 text-sm text-slate-600">
                                <div class="line-clamp-2 whitespace-normal">
                                    {{ $item->alamat_asal ?? '-' }}
                                </div>
                            </td>
                            <td class="max-w-xs px-6 py-4 text-sm text-slate-600">
                                <div class="line-clamp-2 whitespace-normal">
                                    {{ $item->alamat_tujuan ?? '-' }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                {{ $item->user?->name ?? '-' }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <a href="{{ route('perangkat-desa.mutasi.show', $item->id) }}"
                                    title="Detail mutasi"
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
                            <td colspan="8" class="px-6 py-16 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div
                                        class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-700">
                                        @if ($jenis === 'masuk')
                                            Belum Ada Mutasi Masuk
                                        @elseif ($jenis === 'keluar')
                                            Belum Ada Mutasi Keluar
                                        @else
                                            Belum Ada Riwayat Mutasi
                                        @endif
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Belum terdapat data mutasi penduduk yang tercatat.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($mutasi->hasPages())
        <div class="border-t border-slate-200 bg-white px-6 py-4">
            {{ $mutasi->links() }}
        </div>
    @endif
    <div wire:loading.flex wire:target="jenis"
        class="absolute inset-0 z-10 items-center justify-center bg-white/70">
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
                Memuat data...
            </p>
        </div>
    </div>
</div>

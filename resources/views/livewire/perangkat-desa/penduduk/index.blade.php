<div class="space-y-6" wire:init="loadData">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Data Penduduk
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Kelola data penduduk Desa Sekartejo.
            </p>
        </div>
        <a href="{{ route('perangkat-desa.penduduk.create') }}"
            class="inline-flex w-fit items-center rounded-lg bg-blue-800 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
            + Tambah Penduduk
        </a>
    </div>
    <div class="rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm">
        <div class="flex items-center gap-4">
            <div class="min-w-0 flex-1">
                <label for="search" class="sr-only">
                    Cari penduduk
                </label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.04 6.04a7.5 7.5 0 0 0 10.61 10.61Z" />
                        </svg>
                    </div>
                    <input id="search" type="text" wire:model.live.debounce.300ms="search"
                        placeholder="Cari NIK atau nama..."
                        class="w-full rounded-lg border border-slate-300 bg-white py-2.5 pl-12 pr-4 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                </div>
            </div>
            <div class="shrink-0 text-sm leading-5 text-slate-500">
                <div>
                    Total:
                    <span class="font-semibold text-slate-700">
                        {{ $penduduk->count() }}
                    </span>
                </div>
                <div>
                    penduduk
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
                            Penduduk
                        </th>

                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            NIK
                        </th>

                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Jenis Kelamin
                        </th>

                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Alamat
                        </th>

                        <th class="px-6 py-3.5 text-left text-xs font-bold uppercase tracking-wide text-blue-900">
                            Status
                        </th>

                        <th class="px-6 py-3.5 text-right text-xs font-bold uppercase tracking-wide text-blue-900">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse ($penduduk as $item)
                        <tr class="transition hover:bg-slate-50">
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-100 text-sm font-bold text-blue-700">
                                        @if ($item->foto)
                                            <img src="{{ $item->foto }}" alt="{{ $item->nama }}"
                                                class="h-full w-full object-cover">
                                        @else
                                            {{ strtoupper(substr($item->nama, 0, 1)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-slate-800">
                                            {{ $item->nama }}
                                        </div>
                                        <div class="text-xs text-slate-500">
                                            KK: {{ $item->no_kk }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-700">
                                {{ $item->nik }}
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                @if ($item->jenis_kelamin === 'L')
                                    Laki-laki
                                @else
                                    Perempuan
                                @endif
                            </td>
                            <td class="max-w-xs px-6 py-4 text-sm text-slate-600">
                                <div class="truncate">
                                    {{ $item->alamat }}
                                </div>
                                <div class="mt-0.5 text-xs text-slate-400">
                                    RT {{ $item->rt }} / RW {{ $item->rw }}
                                    · {{ $item->dusun }}
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-6 py-4">
                                <div class="flex flex-col items-start gap-1">
                                    @if ($item->status_kependudukan === 'Tetap')
                                        <span
                                            class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                            Tetap
                                        </span>
                                    @elseif ($item->status_kependudukan === 'Pendatang')
                                        <span
                                            class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                                            Pendatang
                                        </span>
                                    @elseif ($item->status_kependudukan === 'Pindah')
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
                            </td>
                            <td class="whitespace-nowrap px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">

                                    {{-- LIHAT DETAIL --}}
                                    <a href="{{ route('perangkat-desa.penduduk.show', $item->id) }}"
                                        title="Lihat detail"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">

                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12Z" />
                                            <circle cx="12" cy="12" r="2.5" />
                                        </svg>

                                    </a>

                                    {{-- EDIT --}}
                                    <a href="{{ route('perangkat-desa.penduduk.edit', $item->id) }}"
                                        title="Edit penduduk"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-amber-200 hover:bg-amber-50 hover:text-amber-700">

                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16.862 4.487l1.687-1.688a2.25 2.25 0 113.182 3.182l-1.688 1.688M16.862 4.487L6.832 14.517a4.5 4.5 0 00-1.09 1.77l-.91 2.73a1 1 0 001.27 1.27l2.73-.91a4.5 4.5 0 001.77-1.09L19.669 7.669" />
                                        </svg>

                                    </a>

                                    {{-- HAPUS --}}
                                    <button type="button" title="Hapus penduduk"
                                        wire:click="hapus({{ $item->id }})"
                                        wire:confirm="Apakah Anda yakin ingin menghapus data {{ $item->nama }}?"
                                        class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700">

                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 7h12M9 7V5.5A1.5 1.5 0 0110.5 4h3A1.5 1.5 0 0115 5.5V7m-7 0 .75 12.5h6.5L16 7M10 10.5v5M14 10.5v5" />
                                        </svg>

                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-16 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div
                                        class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM4 21a8 8 0 0 1 16 0" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-slate-700">
                                        Belum ada data penduduk.
                                    </p>
                                    <p class="mt-1 text-sm text-slate-500">
                                        Tambahkan data penduduk untuk mulai mengelola data.
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div wire:loading.flex wire:target="loadData,search,nextPage,previousPage,gotoPage"
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
                    Memuat data penduduk...
                </p>
            </div>
        </div>
    </div>
    {{-- PAGINATION --}}
    @if ($penduduk->hasPages())
        <div class="border-t border-slate-200 bg-white px-6 py-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="text-sm text-slate-500">
                    Menampilkan
                    <span class="font-semibold text-slate-700">
                        {{ $penduduk->firstItem() ?? 0 }}
                    </span>
                    sampai
                    <span class="font-semibold text-slate-700">
                        {{ $penduduk->lastItem() ?? 0 }}
                    </span>
                    dari
                    <span class="font-semibold text-slate-700">
                        {{ $penduduk->total() }}
                    </span>
                    penduduk
                </div>
                <div class="flex items-center gap-1">
                    @if ($penduduk->onFirstPage())
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
                        @foreach ($penduduk->getUrlRange(1, $penduduk->lastPage()) as $page => $url)
                            @if ($page == $penduduk->currentPage())
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

                    {{-- NEXT --}}
                    @if ($penduduk->hasMorePages())
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

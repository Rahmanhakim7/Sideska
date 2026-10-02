<div class="flex min-h-[calc(100vh-180px)] flex-col space-y-7">

    {{-- Judul Halaman --}}
    <div class="flex items-center gap-3">

        {{-- Icon --}}
        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50">
            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M6.5 3.5h7L18.5 8v12a1 1 0 01-1 1h-11a1 1 0 01-1-1V4.5a1 1 0 011-1z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13.5 3.5V8h5" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8.5 12h7M8.5 15.5h5" />
            </svg>
        </div>

        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                Riwayat Pengajuan Surat
            </h1>

            <p class="mt-0.5 text-sm text-slate-500">
                Pantau status dan perkembangan pengajuan surat Anda.
            </p>
        </div>

    </div>


    {{-- Area Daftar Pengajuan --}}
    <div class="relative flex-1">

        {{-- GRID CARD --}}
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">

            @forelse ($pengajuan as $item)

                {{-- Card Pengajuan --}}
                <div
                    class="w-full rounded-2xl border border-slate-200 bg-white shadow-sm transition-all duration-200 hover:border-blue-200 hover:shadow-md">
                    <div class="p-6">

                        {{-- Header Card --}}
                        <div class="flex items-start justify-between gap-4">

                            {{-- Icon + Informasi Surat --}}
                            <div class="flex items-center gap-4">

                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-xl bg-blue-50">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M6.5 3.5h7L18.5 8v12a1 1 0 01-1 1h-11a1 1 0 01-1-1V4.5a1 1 0 011-1z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M13.5 3.5V8h5" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M8.5 12h7M8.5 15.5h5" />
                                    </svg>
                                </div>

                                <div>
                                    <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                        Pengajuan Surat
                                    </p>

                                    <h2 class="mt-1 text-lg font-semibold text-slate-800">
                                        {{ ucwords(str_replace('_', ' ', $item->jenis_surat)) }}
                                    </h2>

                                    <div class="mt-1 flex items-center gap-1.5 text-xs text-slate-400">

                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M8 3.5v3M16 3.5v3" />

                                            <rect x="5" y="5.5" width="14" height="15" rx="2"
                                                stroke="currentColor" stroke-width="1.8" />

                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M5 10h14" />
                                        </svg>
                                        {{ $item->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            </div>
                            <span
                                class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                {{ ucwords(str_replace('_', ' ', $item->status->value)) }}
                            </span>
                        </div>
                        <div class="mt-6">
                            <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-400">
                                Keperluan
                            </p>
                            <p class="mt-2 text-sm leading-6 text-slate-600">
                                {{ $item->keperluan }}
                            </p>
                        </div>
                        @if (
                            $item->status === \App\Enums\PengajuanSuratStatus::REVISI ||
                                $item->status === \App\Enums\PengajuanSuratStatus::DITOLAK)
                            <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100">
                                        <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M12 9v3.5M12 16h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z" />
                                        </svg>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-semibold text-amber-800">
                                            @if ($item->status === \App\Enums\PengajuanSuratStatus::REVISI)
                                                Perlu Perbaikan
                                            @else
                                                Pengajuan Ditolak
                                            @endif
                                        </p>
                                        @if ($item->catatan)
                                            <p class="mt-1 text-sm leading-6 text-amber-700">
                                                {{ $item->catatan }}
                                            </p>
                                        @else
                                            <p class="mt-1 text-sm text-amber-700">
                                                Tidak ada catatan dari Perangkat Desa.
                                            </p>
                                        @endif

                                    </div>

                                </div>
                                @if ($item->status === \App\Enums\PengajuanSuratStatus::REVISI)
                                    <div class="mt-4 border-t border-amber-200 pt-4">
                                        <a href="{{ $this->routePerbaikan($item) }}" wire:navigate
                                            class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-4 py-2.5
                   text-sm font-semibold text-white transition
                   hover:bg-amber-700
                   focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                    d="M15.5 5.5l3 3M4 20h3.5L18.5 9a2.12 2.12 0 000-3l-.5-.5a2.12 2.12 0 00-3 0L4 16.5V20z" />
                                            </svg>
                                            Perbaiki Pengajuan
                                        </a>
                                    </div>
                                @endif
                            </div>
                        @endif
                        @if ($item->nomor_surat || $item->tanggal_selesai)
                            <div class="mt-5 border-t border-slate-100 pt-5">
                                <div class="flex items-center gap-8">
                                    @if ($item->nomor_surat)
                                        <div>
                                            <p class="text-[11px] font-medium uppercase tracking-wider text-slate-400">
                                                Nomor Surat
                                            </p>
                                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                                {{ $item->nomor_surat }}
                                            </p>
                                        </div>
                                    @endif
                                    @if ($item->tanggal_selesai)
                                        <div>
                                            <p class="text-[11px] font-medium uppercase tracking-wider text-slate-400">
                                                Tanggal Selesai
                                            </p>
                                            <p class="mt-1 text-sm font-semibold text-slate-700">
                                                {{ $item->tanggal_selesai->format('d M Y') }}
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                        @if ($item->status === \App\Enums\PengajuanSuratStatus::SELESAI && $item->file_pdf)
                            <div class="mt-5 border-t border-slate-100 pt-5">
                                <div class="flex flex-wrap items-center gap-3">

                                    {{-- Lihat Surat --}}
                                    <a href="{{ Storage::url($item->file_pdf) }}" target="_blank"
                                        class="inline-flex items-center gap-2 rounded-xl bg-blue-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" />
                                            <circle cx="12" cy="12" r="2.5" stroke="currentColor"
                                                stroke-width="1.8" />
                                        </svg>

                                        Lihat Surat
                                    </a>

                                    {{-- Download Surat --}}
                                    <a href="{{ Storage::url($item->file_pdf) }}" download
                                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M12 3.5v11M7.5 10.5L12 15l4.5-4.5M5 19.5h14" />
                                        </svg>

                                        Download Surat
                                    </a>

                                </div>
                            </div>
                        @endif
                        <div class="mt-5 border-t border-slate-100 pt-4">
                            <div class="flex items-center gap-2 text-xs text-slate-400">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M12 8v4l2.5 1.5" />
                                    <circle cx="12" cy="12" r="9" stroke="currentColor"
                                        stroke-width="1.8" />
                                </svg>
                                Diperbarui {{ $item->updated_at->format('d M Y, H:i') }}
                            </div>

                        </div>

                    </div>
                </div>

            @empty

                {{-- Belum Ada Pengajuan --}}
                <div
                    class="w-full rounded-2xl border border-slate-200 bg-white px-8 py-12 text-center shadow-sm lg:col-span-2">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50">
                        <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M6.5 3.5h7L18.5 8v12a1 1 0 01-1 1h-11a1 1 0 01-1-1V4.5a1 1 0 011-1z" />

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M13.5 3.5V8h5" />
                        </svg>
                    </div>

                    <h2 class="mt-4 text-base font-semibold text-slate-800">
                        Belum Ada Pengajuan
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Anda belum memiliki riwayat pengajuan surat.
                    </p>

                </div>

            @endforelse

        </div>
        <div wire:loading.flex wire:target="nextPage,previousPage,gotoPage"
            class="absolute inset-0 z-10 items-center justify-center rounded-2xl bg-white/90">
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
                    Memuat riwayat pengajuan...
                </p>

            </div>
        </div>

    </div>


    {{-- PAGINATION --}}
    @if ($pengajuan->hasPages())

        <div class="mt-auto w-full border-t border-slate-200 pt-4">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                {{-- Informasi Pagination --}}
                <div class="text-sm text-slate-500">

                    Menampilkan

                    <span class="font-semibold text-slate-700">
                        {{ $pengajuan->firstItem() ?? 0 }}
                    </span>

                    sampai

                    <span class="font-semibold text-slate-700">
                        {{ $pengajuan->lastItem() ?? 0 }}
                    </span>
                    dari
                    <span class="font-semibold text-slate-700">
                        {{ $pengajuan->total() }}
                    </span>
                    pengajuan
                </div>


                {{-- Tombol Pagination --}}
                <div class="flex items-center gap-1">

                    {{-- SEBELUMNYA --}}
                    @if ($pengajuan->onFirstPage())
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


                    {{-- NOMOR HALAMAN --}}
                    <div class="hidden items-center gap-1 sm:flex">

                        @foreach ($pengajuan->getUrlRange(1, $pengajuan->lastPage()) as $page => $url)
                            @if ($page == $pengajuan->currentPage())
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


                    {{-- BERIKUTNYA --}}
                    @if ($pengajuan->hasMorePages())
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

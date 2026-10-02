<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Persetujuan Surat
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Daftar surat yang menunggu tanda tangan Kepala Desa.
        </p>
    </div>


    {{-- Grid Card Pengajuan --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

        @forelse ($pengajuan as $item)
            {{-- Card Pengajuan --}}
            <div
                class="overflow-hidden rounded-2xl border border-slate-200
                       bg-white shadow-sm transition
                       hover:-translate-y-0.5 hover:shadow-md">

                {{-- Bagian Atas Card --}}
                <div class="px-6 pt-6">

                    <div class="flex items-start gap-4">

                        {{-- Icon Penduduk --}}
                        <div
                            class="flex h-14 w-14 shrink-0 items-center justify-center
                                   rounded-full bg-blue-50">
                            <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0z" />

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>


                        {{-- Nama & Status --}}
                        <div class="min-w-0 flex-1">

                            <h3 class="truncate text-lg font-bold text-slate-800">
                                {{ $item->penduduk->nama }}
                            </h3>

                            <span
                                class="mt-2 inline-flex items-center rounded-full
                                       bg-purple-100 px-3 py-1
                                       text-xs font-semibold text-purple-700">
                                <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-purple-500"></span>

                                Menunggu Tanda Tangan
                            </span>

                        </div>

                    </div>

                </div>


                {{-- Informasi Surat --}}
                <div class="px-6 pt-5">

                    <div
                        class="overflow-hidden rounded-xl border border-slate-200
                               bg-slate-50">

                        <div
                            class="grid grid-cols-1 divide-y divide-slate-200
                                    sm:grid-cols-3 sm:divide-x sm:divide-y-0">

                            {{-- Jenis Surat --}}
                            <div class="px-4 py-4">

                                <div class="flex items-start gap-3">

                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-slate-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                                    </svg>

                                    <div class="min-w-0">

                                        <p class="text-xs font-medium text-slate-500">
                                            Jenis Surat
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            {{ $item->jenis_surat }}
                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- Tanggal Pengajuan --}}
                            <div class="px-4 py-4">

                                <div class="flex items-start gap-3">

                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-slate-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>

                                    <div class="min-w-0">

                                        <p class="text-xs font-medium text-slate-500">
                                            Diajukan
                                        </p>

                                        <p class="mt-1 text-sm font-semibold text-slate-800">
                                            {{ $item->created_at?->format('d M Y H:i') }}
                                        </p>

                                    </div>

                                </div>

                            </div>


                            {{-- Nomor Surat --}}
                            <div class="px-4 py-4">

                                <div class="flex items-start gap-3">

                                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-slate-500" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h10M7 12h10M7 17h6" />
                                    </svg>

                                    <div class="min-w-0">

                                        <p class="text-xs font-medium text-slate-500">
                                            Nomor Surat
                                        </p>

                                        <p class="mt-1 truncate text-sm font-semibold text-slate-800">
                                            {{ $item->nomor_surat ?: '-' }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Tombol --}}
                <div class="px-6 py-5">

                    <a href="{{ route('kepala.persetujuan.detail', $item->id) }}" wire:navigate
                        class="inline-flex items-center gap-2 rounded-xl
                               bg-blue-600 px-4 py-2.5
                               text-sm font-semibold text-white
                               shadow-sm transition
                               hover:bg-blue-700
                               hover:shadow
                               focus:outline-none
                               focus:ring-2
                               focus:ring-blue-500
                               focus:ring-offset-2">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>

                        Lihat Surat

                    </a>

                </div>

            </div>

        @empty

            {{-- Belum Ada Surat --}}
            <div
                class="col-span-1 rounded-2xl border border-slate-200
                       bg-white px-6 py-12 text-center shadow-sm
                       lg:col-span-2">

                <div
                    class="mx-auto flex h-14 w-14 items-center justify-center
                           rounded-full bg-slate-100">

                    <svg class="h-7 w-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                    </svg>

                </div>

                <h3 class="mt-4 text-sm font-semibold text-slate-700">
                    Belum ada surat
                </h3>

                <p class="mt-1 text-sm text-slate-500">
                    Tidak ada surat yang menunggu tanda tangan saat ini.
                </p>

            </div>
        @endforelse

    </div>

    @if ($pengajuan->hasPages())

        <div class="rounded-2xl border border-slate-200
                   bg-white px-6 py-4 shadow-sm">

            <div
                class="flex flex-col gap-4
                       sm:flex-row sm:items-center
                       sm:justify-between">

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


                {{-- Navigasi --}}
                <div class="flex items-center gap-1">

                    {{-- Sebelumnya --}}
                    @if ($pengajuan->onFirstPage())
                        <span
                            class="inline-flex h-9 items-center justify-center
                                   rounded-lg border border-slate-200
                                   bg-slate-50 px-3
                                   text-sm font-medium text-slate-400">
                            Sebelumnya
                        </span>
                    @else
                        <button type="button" wire:click="previousPage" wire:loading.attr="disabled"
                            class="inline-flex h-9 items-center justify-center
                                   rounded-lg border border-slate-200
                                   bg-white px-3 text-sm font-medium
                                   text-slate-600 transition
                                   hover:border-blue-200
                                   hover:bg-blue-50
                                   hover:text-blue-700
                                   disabled:cursor-not-allowed
                                   disabled:opacity-50">
                            Sebelumnya
                        </button>
                    @endif


                    {{-- Nomor Halaman --}}
                    <div class="hidden items-center gap-1 sm:flex">

                        @foreach ($pengajuan->getUrlRange(1, $pengajuan->lastPage()) as $page => $url)
                            @if ($page == $pengajuan->currentPage())
                                <span
                                    class="inline-flex h-9 min-w-9
                                           items-center justify-center
                                           rounded-lg bg-blue-800 px-3
                                           text-sm font-semibold text-white
                                           shadow-sm">
                                    {{ $page }}
                                </span>
                            @else
                                <button type="button" wire:click="gotoPage({{ $page }})"
                                    wire:loading.attr="disabled"
                                    class="inline-flex h-9 min-w-9
                                           items-center justify-center
                                           rounded-lg border
                                           border-slate-200 bg-white
                                           px-3 text-sm font-medium
                                           text-slate-600 transition
                                           hover:border-blue-200
                                           hover:bg-blue-50
                                           hover:text-blue-700
                                           disabled:cursor-not-allowed
                                           disabled:opacity-50">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach

                    </div>


                    {{-- Berikutnya --}}
                    @if ($pengajuan->hasMorePages())
                        <button type="button" wire:click="nextPage" wire:loading.attr="disabled"
                            class="inline-flex h-9 items-center justify-center
                                   rounded-lg border border-slate-200
                                   bg-white px-3 text-sm font-medium
                                   text-slate-600 transition
                                   hover:border-blue-200
                                   hover:bg-blue-50
                                   hover:text-blue-700
                                   disabled:cursor-not-allowed
                                   disabled:opacity-50">
                            Berikutnya
                        </button>
                    @else
                        <span
                            class="inline-flex h-9 items-center justify-center
                                   rounded-lg border border-slate-200
                                   bg-slate-50 px-3
                                   text-sm font-medium text-slate-400">
                            Berikutnya
                        </span>
                    @endif

                </div>

            </div>

        </div>

    @endif

</div>

<div class="flex min-h-[calc(100vh-180px)] flex-col space-y-7">

    {{-- Judul Halaman --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-3">

            {{-- Icon --}}
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-blue-50">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>

            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">
                    Notifikasi
                </h1>

                <p class="mt-0.5 text-sm text-slate-500">
                    Pemberitahuan pembaruan status pengajuan surat Anda.
                </p>
            </div>

        </div>

        <button
            type="button"
            wire:click="tandaiSemuaDibaca"
            wire:loading.attr="disabled"
            @if ($notifikasi->getCollection()->whereNull('read_at')->isEmpty())
                disabled
            @endif
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                    d="M5 13l4 4L19 7" />
            </svg>

            Tandai Semua Dibaca
        </button>
    </div>

    {{-- Area Daftar Notifikasi --}}
    <div class="relative flex-1">

        <div class="space-y-3">

            @forelse ($notifikasi as $item)

                @php
                    $data = $item->data;
                    $status = $data['status'] ?? null;
                    $unread = $item->read_at === null;

                    $badge = match ($status) {
                        'diajukan' => ['bg-blue-50', 'text-blue-700'],
                        'revisi' => ['bg-amber-50', 'text-amber-700'],
                        'diproses' => ['bg-indigo-50', 'text-indigo-700'],
                        'ditolak' => ['bg-red-50', 'text-red-700'],
                        'menunggu_tanda_tangan' => ['bg-violet-50', 'text-violet-700'],
                        'selesai' => ['bg-green-50', 'text-green-700'],
                        default => ['bg-slate-100', 'text-slate-600'],
                    };
                @endphp

                <div
                    @class([
                        'rounded-2xl border bg-white shadow-sm transition-all duration-200 hover:shadow-md',
                        'border-blue-200' => $unread,
                        'border-slate-200' => ! $unread,
                    ])
                >
                    <div class="flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex min-w-0 items-start gap-3">
                            <div
                                class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl {{ $badge[0] }}"
                            >
                                <svg class="h-5 w-5 {{ $badge[1] }}" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M6.5 3.5h7L18.5 8v12a1 1 0 01-1 1h-11a1 1 0 01-1-1V4.5a1 1 0 011-1z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M13.5 3.5V8h5" />
                                </svg>
                            </div>

                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span
                                        class="inline-flex items-center rounded-full {{ $badge[0] }} px-2.5 py-0.5 text-xs font-semibold {{ $badge[1] }}">
                                        @if ($data['jenis_surat'] ?? null)
                                            {{ ucwords(str_replace('_', ' ', $data['jenis_surat'])) }}
                                        @else
                                            Surat
                                        @endif
                                    </span>

                                    @if ($status)
                                        <span
                                            class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-semibold text-slate-600">
                                            {{ ucwords(str_replace('_', ' ', $status)) }}
                                        </span>
                                    @endif

                                    @if ($unread)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                            Baru
                                        </span>
                                    @endif
                                </div>

                                <p class="mt-2 text-sm leading-6 text-slate-700">
                                    {{ $data['pesan'] ?? 'Pembaruan pengajuan surat.' }}
                                </p>

                                <p class="mt-1.5 flex items-center gap-1.5 text-xs text-slate-400">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M12 8v4l2.5 1.5" />
                                        <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" />
                                    </svg>
                                    {{ $item->created_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        <div class="flex shrink-0 flex-wrap items-center gap-2">

                            @if ($data['url'] ?? null)
                                <a
                                    href="{{ $data['url'] }}"
                                    wire:navigate
                                    class="inline-flex items-center gap-2 rounded-xl bg-blue-800 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                >
                                    Buka
                                </a>
                            @endif

                            @if ($unread)
                                <button
                                    type="button"
                                    wire:click="tandaiDibaca('{{ $item->id }}')"
                                    wire:loading.attr="disabled"
                                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                >
                                    Tandai Dibaca
                                </button>
                            @endif

                            <button
                                type="button"
                                wire:click="hapus('{{ $item->id }}')"
                                wire:loading.attr="disabled"
                                wire:confirm="Hapus notifikasi ini?"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-semibold text-slate-500 transition hover:border-red-200 hover:bg-red-50 hover:text-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M19 7l-.9 12a2 2 0 01-2 1.9H7.9a2 2 0 01-2-1.9L5 7m5 4v6m4-6v6M4 7h16M10 7V4h4v3" />
                                </svg>
                            </button>

                        </div>

                    </div>
                </div>

            @empty

                <div class="rounded-2xl border border-slate-200 bg-white px-8 py-12 text-center shadow-sm">

                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-50">
                        <svg class="h-7 w-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M15 17h5l-1.4-1.4A2 2 0 0118 14.2V11a6 6 0 10-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                    </div>

                    <h2 class="mt-4 text-base font-semibold text-slate-800">
                        Belum Ada Notifikasi
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Pemberitahuan pembaruan pengajuan surat akan muncul di sini.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

    {{-- PAGINATION --}}
    @if ($notifikasi->hasPages())

        <div class="mt-auto w-full border-t border-slate-200 pt-4">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div class="text-sm text-slate-500">
                    Menampilkan

                    <span class="font-semibold text-slate-700">
                        {{ $notifikasi->firstItem() ?? 0 }}
                    </span>

                    sampai

                    <span class="font-semibold text-slate-700">
                        {{ $notifikasi->lastItem() ?? 0 }}
                    </span>
                    dari
                    <span class="font-semibold text-slate-700">
                        {{ $notifikasi->total() }}
                    </span>
                    notifikasi
                </div>

                <div class="flex items-center gap-1">

                    @if ($notifikasi->onFirstPage())
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

                    @if ($notifikasi->hasMorePages())
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
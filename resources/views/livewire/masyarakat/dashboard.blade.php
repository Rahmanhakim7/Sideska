<div class="space-y-6">
    <div class="space-y-1">
        <h1 class="text-2xl font-bold tracking-tight text-slate-800">
            Dashboard Masyarakat
        </h1>

        <p class="text-sm text-slate-500">
            Selamat datang di SIDESKA — Sistem Informasi Desa Sekartejo.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <p class="text-sm font-medium text-slate-500">Total Pengajuan</p>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-slate-800 sm:text-3xl">
                {{ $totalPengajuan }}
            </p>
        </div>

        <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <p class="text-sm font-medium text-slate-500">Diajukan</p>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-50 text-amber-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-amber-600 sm:text-3xl">
                {{ $pengajuanDiajukan }}
            </p>
        </div>

        <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <p class="text-sm font-medium text-slate-500">Diproses</p>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-blue-600 sm:text-3xl">
                {{ $pengajuanDiproses }}
            </p>
        </div>

        <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <p class="text-sm font-medium text-slate-500">Revisi</p>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-orange-50 text-orange-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-orange-600 sm:text-3xl">
                {{ $pengajuanRevisi }}
            </p>
        </div>

        <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <p class="text-sm font-medium text-slate-500">Ditolak</p>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-red-600 sm:text-3xl">
                {{ $pengajuanDitolak }}
            </p>
        </div>

        <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
            <div class="flex items-start justify-between gap-3">
                <p class="text-sm font-medium text-slate-500">Selesai</p>
                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-green-50 text-green-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-green-600 sm:text-3xl">
                {{ $pengajuanSelesai }}
            </p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-5">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-semibold tracking-tight text-slate-800">
                        Pengajuan Terbaru
                    </h2>
                    <p class="mt-1.5 text-sm text-slate-500">
                        5 pengajuan surat terbaru Anda.
                    </p>
                </div>

                <a href="{{ route('masyarakat.surat.riwayat') }}" wire:navigate
                    class="inline-flex w-fit items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition duration-200 ease-out hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                    Lihat semua riwayat

                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m9 18 6-6-6-6" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($pengajuanTerbaru as $item)
                <div class="flex flex-col gap-3 px-6 py-4 transition-colors duration-150 hover:bg-blue-50/40 sm:flex-row sm:items-center sm:justify-between">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold text-slate-800">
                            {{ ucwords(str_replace('_', ' ', $item->jenis_surat)) }}
                        </p>

                        <div class="mt-1 flex items-center gap-1.5 text-xs text-slate-400">
                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M8 3.5v3M16 3.5v3" />

                                <rect x="5" y="5.5" width="14" height="15" rx="2" stroke="currentColor"
                                    stroke-width="1.8" />

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M5 10h14" />
                            </svg>

                            {{ $item->created_at?->format('d M Y, H:i') }}
                        </div>

                        @if ($item->keperluan)
                            <p class="mt-1.5 text-sm text-slate-500">
                                {{ $item->keperluan }}
                            </p>
                        @endif
                    </div>

                    @php
                        $status = $item->status->value;

                        $statusClass = match ($status) {
                            'diajukan'
                                => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200',
                            'revisi'
                                => 'bg-orange-50 text-orange-700 ring-1 ring-inset ring-orange-200',
                            'diproses'
                                => 'bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-200',
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
                        class="{{ $statusClass }} inline-flex w-fit shrink-0 items-center rounded-full px-3 py-1 text-xs font-semibold">
                        {{ $statusLabel }}
                    </span>
                </div>
            @empty
                <div class="px-6 py-12 text-center">
                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 ring-1 ring-slate-200/70">
                        <svg class="h-7 w-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <h3 class="mt-4 text-sm font-semibold text-slate-700">
                        Belum ada pengajuan
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Anda belum memiliki riwayat pengajuan surat.
                    </p>
                </div>
            @endforelse
        </div>
    </div>
</div>

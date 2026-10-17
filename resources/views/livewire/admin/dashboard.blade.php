<div class="space-y-6">
    <div class="space-y-1">
        <h1 class="text-2xl font-bold tracking-tight text-slate-800">
            Dashboard Admin
        </h1>

        <p class="text-sm text-slate-500">
            Selamat datang di SIDESKA — Sistem Informasi Desa Sekartejo.
        </p>
    </div>

    {{-- Ringkasan Pengguna --}}
    <div class="space-y-5">
        <h2 class="text-base font-semibold tracking-tight text-slate-800">
            Ringkasan Pengguna
        </h2>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">Total User</p>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 11-8 0 4 4 0 018 0zm6 2a3 3 0 10-6 0" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-slate-800 sm:text-3xl">
                    {{ $totalUser }}
                </p>
            </div>

            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">User Aktif</p>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-emerald-600 sm:text-3xl">
                    {{ $userAktif }}
                </p>
            </div>

            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">User Nonaktif</p>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-red-600 sm:text-3xl">
                    {{ $userNonaktif }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Admin</p>
                <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-slate-800">
                    {{ $userAdmin }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Perangkat Desa</p>
                <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-blue-600">
                    {{ $userPerangkatDesa }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Kepala Desa</p>
                <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-purple-600">
                    {{ $userKepalaDesa }}
                </p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-slate-500">Masyarakat</p>
                <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-amber-600">
                    {{ $userMasyarakat }}
                </p>
            </div>
        </div>
    </div>

    {{-- Ringkasan Penduduk --}}
    <div class="space-y-5">
        <h2 class="text-base font-semibold tracking-tight text-slate-800">
            Ringkasan Penduduk
        </h2>

        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">Total Penduduk</p>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-8a4 4 0 11-8 0 4 4 0 018 0zm6 2a3 3 0 10-6 0" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-slate-800 sm:text-3xl">
                    {{ $totalPenduduk }}
                </p>
            </div>

            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">Laki-laki</p>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-blue-600 sm:text-3xl">
                    {{ $pendudukLakiLaki }}
                </p>
            </div>

            <div class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition duration-200 ease-out hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <p class="text-sm font-medium text-slate-500">Perempuan</p>
                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-pink-50 text-pink-600">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight text-pink-600 sm:text-3xl">
                    {{ $pendudukPerempuan }}
                </p>
            </div>
        </div>
    </div>

    {{-- Ringkasan Pengajuan --}}
    <div class="space-y-5">
        <h2 class="text-base font-semibold tracking-tight text-slate-800">
            Ringkasan Pengajuan
        </h2>

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

            @foreach (\App\Enums\PengajuanSuratStatus::cases() as $status)
                @php
                    $jumlah = (int) ($pengajuanPerStatus[$status->value] ?? 0);

                    $warna = match ($status->value) {
                        'diajukan' => 'text-amber-600',
                        'revisi' => 'text-orange-600',
                        'diproses' => 'text-blue-600',
                        'ditolak' => 'text-red-600',
                        'menunggu_tanda_tangan' => 'text-purple-600',
                        'selesai' => 'text-emerald-600',
                        default => 'text-slate-800',
                    };

                    $label = $status === \App\Enums\PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN
                        ? 'Menunggu Tanda Tangan'
                        : ucwords(str_replace('_', ' ', $status->value));
                @endphp

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">{{ $label }}</p>
                    <p class="mt-3 text-2xl font-bold tabular-nums tracking-tight {{ $warna }}">
                        {{ $jumlah }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Pengajuan Terbaru --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-base font-semibold tracking-tight text-slate-800">
                Pengajuan Terbaru
            </h2>
            <p class="mt-1.5 text-sm text-slate-500">
                5 pengajuan surat terbaru dari masyarakat.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px] text-left">
                <thead class="border-b border-slate-200 bg-blue-50/60">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-blue-900/70">
                            Nama Pemohon
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-blue-900/70">
                            NIK
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-blue-900/70">
                            Jenis Surat
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-blue-900/70">
                            Tanggal Pengajuan
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-blue-900/70">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($pengajuanTerbaru as $item)
                        <tr class="transition-colors duration-150 hover:bg-blue-50/40">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-700">
                                        {{ strtoupper(substr($item->penduduk->nama ?? '-', 0, 1)) }}
                                    </div>

                                    <div class="font-semibold text-slate-800">
                                        {{ $item->penduduk->nama ?? '-' }}
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-4">
                                <span class="text-sm text-slate-600">
                                    {{ $item->penduduk->nik ?? '-' }}
                                </span>
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
                                    class="{{ $statusClass }} inline-flex items-center rounded-full px-3 py-1 text-xs font-semibold">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-slate-100 ring-1 ring-slate-200/70">
                                    <svg class="h-7 w-7 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                                    </svg>
                                </div>

                                <h3 class="mt-4 text-sm font-semibold text-slate-700">
                                    Belum ada pengajuan
                                </h3>

                                <p class="mt-1 text-sm text-slate-500">
                                    Belum ada pengajuan surat yang masuk.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

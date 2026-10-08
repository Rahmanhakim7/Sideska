<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Dashboard Perangkat Desa
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Selamat datang di sistem SIDESKA.
        </p>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Penduduk</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">
                {{ $totalPenduduk }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Penduduk Laki-laki</p>
            <p class="mt-2 text-2xl font-bold text-blue-600">
                {{ $pendudukLakiLaki }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Penduduk Perempuan</p>
            <p class="mt-2 text-2xl font-bold text-pink-600">
                {{ $pendudukPerempuan }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Total Pengajuan Surat</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">
                {{ $totalPengajuan }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Pengajuan Diajukan</p>
            <p class="mt-2 text-2xl font-bold text-amber-600">
                {{ $pengajuanDiajukan }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Pengajuan Diproses</p>
            <p class="mt-2 text-2xl font-bold text-blue-600">
                {{ $pengajuanDiproses }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Menunggu Tanda Tangan</p>
            <p class="mt-2 text-2xl font-bold text-purple-600">
                {{ $pengajuanMenungguTandaTangan }}
            </p>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Pengajuan Selesai</p>
            <p class="mt-2 text-2xl font-bold text-emerald-600">
                {{ $pengajuanSelesai }}
            </p>
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-base font-semibold text-slate-800">
                Pengajuan Terbaru
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                5 pengajuan surat terbaru dari masyarakat.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[900px] text-left">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Nama Pemohon
                        </th>
                        <th class="px-6 py-3.5 text-xs font-semibold uppercase tracking-wider text-slate-500">
                            NIK
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
                        <th class="px-6 py-3.5 text-right text-xs font-semibold uppercase tracking-wider text-slate-500">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($pengajuanTerbaru as $item)
                        <tr class="transition hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-sm font-semibold text-blue-700">
                                        {{ strtoupper(substr($item->penduduk->nama ?? '-', 0, 1)) }}
                                    </div>

                                    <div class="font-medium text-slate-800">
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
                                    class="{{ $statusClass }} inline-flex items-center rounded-full px-2.5 py-1 text-xs font-semibold">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('perangkat-desa.pelayanan-surat.detail', $item->id) }}"
                                    wire:navigate
                                    class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm font-medium text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                    Lihat Detail

                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="m9 18 6-6-6-6" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center">
                                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100">
                                    <svg class="h-6 w-6 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                                    </svg>
                                </div>

                                <h3 class="mt-4 text-sm font-semibold text-slate-700">
                                    Tidak ada pengajuan
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

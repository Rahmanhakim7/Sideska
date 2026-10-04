<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex flex-col gap-3">
            <h1 class="text-2xl font-bold text-slate-800">
                Detail Pengajuan Surat
            </h1>
            <p class="text-sm text-slate-500">
                Periksa informasi pengajuan sebelum melakukan proses pelayanan.
            </p>
        </div>
        @php
            $status = $pengajuan->status->value;
            $statusClass = match ($status) {
                'diajukan' => 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-200',
                'revisi' => 'bg-orange-50 text-orange-700 ring-1 ring-inset ring-orange-200',
                'disetujui' => 'bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-200',
                'ditolak' => 'bg-red-50 text-red-700 ring-1 ring-inset ring-red-200',
                'menunggu_tanda_tangan' => 'bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-200',
                'selesai' => 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-200',
                default => 'bg-slate-50 text-slate-600 ring-1 ring-inset ring-slate-200',
            };
            $statusLabel = match ($status) {
                'menunggu_tanda_tangan' => 'Menunggu Tanda Tangan',
                default => ucwords(str_replace('_', ' ', $status)),
            };
        @endphp
        <a href="{{ route('perangkat-desa.pelayanan-surat') }}" wire:navigate
            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-600 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">

            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />

            </svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">
                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-700">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 19a4 4 0 10-6 0m6 0a6 6 0 10-6 0m6 0v1a2 2 0 01-2 2H9a2 2 0 01-2-2v-1m6-10a3 3 0 11-6 0 3 3 0 016 0z" />

                        </svg>

                    </div>

                    <div>
                        <h2 class="text-base font-semibold text-slate-800">
                            Data Pemohon
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Identitas masyarakat yang mengajukan surat.
                        </p>
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-5 px-6 py-6 sm:grid-cols-2">

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nama Lengkap
                    </p>

                    <p class="mt-1.5 text-sm font-semibold text-slate-800">
                        {{ $pengajuan->penduduk->nama ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        NIK
                    </p>

                    <p class="mt-1.5 text-sm font-semibold text-slate-800">
                        {{ $pengajuan->penduduk->nik ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nomor KK
                    </p>

                    <p class="mt-1.5 text-sm font-semibold text-slate-800">
                        {{ $pengajuan->penduduk->no_kk ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nomor HP
                    </p>

                    <p class="mt-1.5 text-sm font-semibold text-slate-800">
                        {{ $pengajuan->penduduk->no_hp ?? '-' }}
                    </p>
                </div>

                <div class="sm:col-span-2">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Alamat
                    </p>

                    <p class="mt-1.5 text-sm font-semibold leading-6 text-slate-800">
                        {{ $pengajuan->penduduk->alamat ?? '-' }}
                    </p>
                </div>

            </div>

        </div>
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-5">
                <div class="flex items-center gap-3">

                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-50 text-indigo-700">

                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />

                        </svg>

                    </div>

                    <div>
                        <h2 class="text-base font-semibold text-slate-800">
                            Informasi Pengajuan
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Informasi dasar surat yang diajukan.
                        </p>
                    </div>

                </div>
            </div>

            <div class="space-y-5 px-6 py-6">

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Jenis Surat
                    </p>

                    <div class="mt-2">
                        <span
                            class="inline-flex items-center rounded-lg bg-indigo-50 px-3 py-1.5 text-sm font-semibold text-indigo-700">

                            {{ ucwords(str_replace('_', ' ', $pengajuan->jenis_surat)) }}

                        </span>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Tanggal Pengajuan
                    </p>

                    <p class="mt-1.5 text-sm font-semibold text-slate-800">
                        {{ $pengajuan->created_at?->format('d M Y, H:i') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Keperluan
                    </p>

                    <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5">
                        <p class="text-sm leading-6 text-slate-700">
                            {{ $pengajuan->keperluan ?: '-' }}
                        </p>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="px-6 py-6">
            @if ($pengajuan->jenis_surat === 'sktm')
                <div class="space-y-6">

                    {{-- INFO --}}
                    <div class="rounded-xl border border-blue-100 bg-blue-50/60 px-5 py-4">
                        <div class="flex items-start gap-3">

                            <div
                                class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M12 21a9 9 0 100-18 9 9 0 000 18z" />
                                </svg>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-blue-900">
                                    Data Orang Tua
                                </p>

                                <p class="mt-1 text-sm leading-6 text-blue-700">
                                    Data orang tua yang akan dicantumkan pada
                                    Surat Keterangan Tidak Mampu.
                                </p>
                            </div>

                        </div>
                    </div>


                    {{-- AYAH & IBU --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                        {{-- DATA AYAH --}}
                        <div class="rounded-2xl border border-slate-200 bg-white">

                            <div class="border-b border-slate-100 px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-blue-50 text-blue-700">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5.121 17.804A9 9 0 1118.88 17.8M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>

                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-800">
                                            Data Ayah
                                        </h3>

                                        <p class="mt-1 text-xs text-slate-500">
                                            Informasi ayah pemohon.
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-5 px-5 py-5">

                                {{-- Nama Ayah --}}
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Nama Ayah
                                    </label>

                                    <div
                                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ $pengajuan->penduduk->nama_ayah ?? '-' }}
                                    </div>
                                </div>

                                {{-- Pekerjaan Ayah --}}
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Pekerjaan Ayah
                                    </label>

                                    <div
                                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ $dataSktm['pekerjaan_ayah'] ?? '-' }}
                                    </div>
                                </div>

                                {{-- Penghasilan Ayah --}}
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Penghasilan Ayah
                                    </label>

                                    <div
                                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        Rp {{ $dataSktm['penghasilan_ayah'] ?? '-' }}
                                    </div>
                                </div>

                            </div>

                        </div>


                        {{-- DATA IBU --}}
                        <div class="rounded-2xl border border-slate-200 bg-white">

                            <div class="border-b border-slate-100 px-5 py-4">
                                <div class="flex items-center gap-3">

                                    <div
                                        class="flex h-9 w-9 items-center justify-center rounded-xl bg-pink-50 text-pink-600">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19a4 4 0 10-6 0m6 0a6 6 0 10-6 0m6 0v1a2 2 0 01-2 2H9a2 2 0 01-2-2v-1m6-10a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>

                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-800">
                                            Data Ibu
                                        </h3>

                                        <p class="mt-1 text-xs text-slate-500">
                                            Informasi ibu pemohon.
                                        </p>
                                    </div>

                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-5 px-5 py-5">

                                {{-- Nama Ibu --}}
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Nama Ibu
                                    </label>

                                    <div
                                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ $pengajuan->penduduk->nama_ibu ?? '-' }}
                                    </div>
                                </div>

                                {{-- Pekerjaan Ibu --}}
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Pekerjaan Ibu
                                    </label>

                                    <div
                                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        {{ $dataSktm['pekerjaan_ibu'] ?? '-' }}
                                    </div>
                                </div>

                                {{-- Penghasilan Ibu --}}
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-slate-700">
                                        Penghasilan Ibu
                                    </label>

                                    <div
                                        class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-700">
                                        Rp {{ $dataSktm['penghasilan_ibu'] ?? '-' }}
                                    </div>
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            @endif
        </div>
    </div>

    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-5">

            <div class="flex items-center gap-3">

                <div
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-700">

                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                </div>

                <div>

                    <h2 class="text-base font-semibold text-slate-800">
                        Proses Pelayanan
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Kelola pengajuan berdasarkan status dan hasil pemeriksaan.
                    </p>

                </div>

            </div>

        </div>
        <div class="px-6 py-6">
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wide text-slate-400">
                            Status Saat Ini
                        </p>

                        <p class="mt-1.5 text-base font-semibold text-slate-800">
                            {{ $statusLabel }}
                        </p>

                        <p class="mt-1 text-sm leading-6 text-slate-500">
                            Status pengajuan saat ini dalam proses pelayanan.
                        </p>

                    </div>
                    @switch($pengajuan->status->value)
                        @case('diajukan')
                            <span
                                class="inline-flex w-fit items-center gap-2 rounded-full bg-amber-50 px-3.5 py-2 text-sm font-semibold text-amber-700">

                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>

                                Diajukan

                            </span>
                        @break

                        @case('revisi')
                            <span
                                class="inline-flex w-fit items-center gap-2 rounded-full bg-orange-50 px-3.5 py-2 text-sm font-semibold text-orange-700">

                                <span class="h-2 w-2 rounded-full bg-orange-500"></span>

                                Revisi

                            </span>
                        @break

                        @case('diproses')
                            <span
                                class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-3.5 py-2 text-sm font-semibold text-blue-700">

                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>

                                Diproses

                            </span>
                        @break

                        @case('ditolak')
                            <span
                                class="inline-flex w-fit items-center gap-2 rounded-full bg-red-50 px-3.5 py-2 text-sm font-semibold text-red-700">

                                <span class="h-2 w-2 rounded-full bg-red-500"></span>

                                Ditolak

                            </span>
                        @break

                        @case('menunggu_tanda_tangan')
                            <span
                                class="inline-flex w-fit items-center gap-2 rounded-full bg-purple-50 px-3.5 py-2 text-sm font-semibold text-purple-700">

                                <span class="h-2 w-2 rounded-full bg-purple-500"></span>

                                Menunggu Tanda Tangan

                            </span>
                        @break

                        @case('selesai')
                            <span
                                class="inline-flex w-fit items-center gap-2 rounded-full bg-emerald-50 px-3.5 py-2 text-sm font-semibold text-emerald-700">

                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>

                                Selesai

                            </span>
                        @break
                    @endswitch
                </div>
            </div>
            @if ($pengajuan->status === \App\Enums\PengajuanSuratStatus::DIAJUKAN)
                <div class="mt-6 rounded-xl border border-blue-100 bg-blue-50/60 px-5 py-5">
                    <div class="flex items-start gap-3">
                        <div
                            class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M12 21a9 9 0 100-18 9 9 0 000 18z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-blue-900">
                                Pengajuan Menunggu Keputusan
                            </p>
                            <p class="mt-1 text-sm leading-6 text-blue-700">
                                Periksa data pemohon dan keperluan surat sebelum menentukan tindakan.
                            </p>
                        </div>
                    </div>
                    <div class="mt-5 flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <button type="button" wire:click="bukaModalTolak"
                            class="inline-flex items-center justify-center rounded-xl border border-red-200 bg-white px-4 py-2.5 text-sm font-semibold text-red-600 transition hover:border-red-300 hover:bg-red-50">
                            Tolak Pengajuan
                        </button>
                        <button type="button" wire:click="bukaModalRevisi"
                            class="inline-flex items-center justify-center rounded-xl border border-amber-200 bg-white px-4 py-2.5 text-sm font-semibold text-amber-600 transition hover:border-amber-300 hover:bg-amber-50">
                            Minta Revisi
                        </button>
                        <button type="button" wire:click="terima" wire:loading.attr="disabled" wire:target="terima"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800 active:bg-blue-950 disabled:cursor-not-allowed disabled:opacity-60">
                            <span wire:loading.remove wire:target="terima">
                                Diterima
                            </span>
                            <span wire:loading wire:target="terima">
                                Memproses...
                            </span>
                            <svg wire:loading.remove wire:target="terima" class="h-4 w-4" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            <svg wire:loading wire:target="terima" class="h-4 w-4 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4" />

                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z" />
                            </svg>
                        </button>
                    </div>
                </div>
            @elseif ($pengajuan->status === \App\Enums\PengajuanSuratStatus::DIPROSES)
                <div class="mt-6 rounded-xl border border-blue-200 bg-blue-50 px-5 py-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start gap-3">
                            <div
                                class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-blue-800">
                                    Pengajuan Diterima
                                </p>
                                <p class="mt-1 text-sm leading-6 text-blue-700">
                                    Pengajuan telah diterima. Silakan lanjutkan ke tahap pembuatan surat.
                                </p>
                            </div>
                        </div>
                        <a href="{{ route('perangkat-desa.pelayanan-surat.buat', $pengajuan->id) }}" wire:navigate
                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-800 active:bg-blue-950 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                            Buat Surat
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="m9 18 6-6-6-6" />
                            </svg>
                        </a>
                    </div>
                </div>
            @elseif ($pengajuan->status === \App\Enums\PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN)
                <div class="mt-6 rounded-xl border border-purple-200 bg-purple-50 px-5 py-5">
                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-start gap-3">
                            <div
                                class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-purple-100 text-purple-600">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-purple-800">
                                    Surat Menunggu Tanda Tangan
                                </p>
                                <p class="mt-1 text-sm leading-6 text-purple-700">
                                    Surat telah berhasil dibuat dan saat ini menunggu tanda tangan Kepala Desa.
                                </p>
                            </div>
                        </div>
                        <div class="flex shrink-0 flex-col gap-2 sm:flex-row">
                            @if ($pengajuan->file_pdf)
                                <a href="{{ route('perangkat-desa.pelayanan-surat.pdf', $pengajuan) }}"
                                    target="_blank" target="_blank"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-purple-200 bg-white px-4 py-2.5 text-sm font-semibold text-purple-700 transition hover:border-purple-300 hover:bg-purple-50">

                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />

                                    </svg>

                                    Lihat Surat

                                </a>

                                <a href="{{ route('perangkat-desa.pelayanan-surat.pdf.download', $pengajuan) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-purple-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-purple-800">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m5 3a9 9 0 11-18 0 9 9 0 0118 0z" />

                                    </svg>

                                    Download PDF

                                </a>
                            @endif

                        </div>

                    </div>

                </div>
            @elseif ($pengajuan->status === \App\Enums\PengajuanSuratStatus::REVISI)
                <div class="mt-6 rounded-xl border border-orange-200 bg-orange-50 px-5 py-5">

                    <div class="flex items-start gap-3">

                        <div
                            class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-orange-100 text-orange-600">

                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v4m0 4h.01M10.29 3.86l-7.4 12.82A2 2 0 004.63 19.7h14.74a2 2 0 001.74-3.02l-7.4-12.82a2 2 0 00-3.42 0z" />
                            </svg>

                        </div>

                        <div>

                            <p class="text-sm font-semibold text-orange-800">
                                Menunggu Perbaikan dari Masyarakat
                            </p>

                            <p class="mt-1 text-sm leading-6 text-orange-700">
                                Pengajuan telah dikembalikan untuk diperbaiki.
                                Perangkat Desa menunggu masyarakat mengirimkan perbaikan.
                            </p>

                        </div>

                    </div>


                    {{-- Alasan Revisi --}}
                    @if ($pengajuan->catatan)
                        <div class="mt-5 rounded-xl border border-orange-200 bg-white px-4 py-4">

                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                Catatan Revisi
                            </p>

                            <p class="mt-2 text-sm leading-6 text-slate-700">
                                {{ $pengajuan->catatan }}
                            </p>

                        </div>
                    @endif

                </div>
            @elseif ($pengajuan->status === \App\Enums\PengajuanSuratStatus::DITOLAK)
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 px-5 py-5">

                    <div class="flex items-start gap-3">

                        <div
                            class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">

                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />

                            </svg>

                        </div>


                        <div>

                            <p class="text-sm font-semibold text-red-800">
                                Pengajuan Ditolak
                            </p>

                            <p class="mt-1 text-sm leading-6 text-red-700">
                                Pengajuan ini tidak dapat dilanjutkan ke proses pelayanan berikutnya.
                            </p>

                        </div>

                    </div>

                </div>
            @elseif ($pengajuan->status === \App\Enums\PengajuanSuratStatus::SELESAI)
                <div class="mt-6 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-5">

                    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                        <div class="flex items-start gap-3">

                            <div
                                class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">

                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 000 18" />

                                </svg>

                            </div>


                            <div>

                                <p class="text-sm font-semibold text-emerald-800">
                                    Pengajuan Selesai
                                </p>

                                <p class="mt-1 text-sm leading-6 text-emerald-700">
                                    Surat telah ditandatangani Kepala Desa dan siap diberikan kepada masyarakat.
                                </p>

                            </div>

                        </div>


                        @if ($pengajuan->file_pdf)
                            <div class="flex shrink-0 flex-col gap-2 sm:flex-row">

                                <a href="{{ route('perangkat-desa.pelayanan-surat.pdf', $pengajuan) }}"
                                    target="_blank"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl border border-emerald-200 bg-white px-4 py-2.5 text-sm font-semibold text-emerald-700 transition hover:border-emerald-300 hover:bg-emerald-50">

                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />

                                    </svg>

                                    Lihat Surat

                                </a>


                                <a href="{{ route('perangkat-desa.pelayanan-surat.pdf.download', $pengajuan) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-700">

                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m5 3a9 9 0 11-18 0 9 9 0 0118 0z" />

                                    </svg>

                                    Download PDF

                                </a>

                            </div>
                        @endif

                    </div>

                </div>

            @endif
        </div>
    </div>

    @if ($showModalTolak)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4"
            wire:click.self="$set('showModalTolak', false)">
            <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">

                {{-- HEADER --}}
                <div class="border-b border-slate-200 px-6 py-5">
                    <div class="flex items-start gap-3">

                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M12 21a9 9 0 100-18 9 9 0 000 18z" />
                            </svg>
                        </div>

                        <div>
                            <h3 class="text-base font-bold text-slate-800">
                                Tolak Pengajuan
                            </h3>

                            <p class="mt-1 text-sm leading-5 text-slate-500">
                                Berikan alasan mengapa pengajuan surat ini ditolak.
                            </p>
                        </div>

                    </div>
                </div>

                {{-- FORM --}}
                <div class="px-6 py-5">

                    <label for="alasanPenolakan" class="mb-2 block text-sm font-semibold text-slate-700">
                        Alasan Penolakan
                    </label>

                    <textarea id="alasanPenolakan" wire:model="alasanPenolakan" rows="5"
                        placeholder="Masukkan alasan penolakan..."
                        class="w-full resize-none rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-red-400 focus:ring-2 focus:ring-red-100"></textarea>

                    @error('alasanPenolakan')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- INFORMASI --}}
                    <div class="mt-4 rounded-xl border border-red-100 bg-red-50 px-4 py-3">
                        <p class="text-sm leading-5 text-red-700">
                            Pengajuan yang ditolak harus diajukan kembali dari awal
                            oleh pemohon apabila masih diperlukan.
                        </p>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div
                    class="flex flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4 sm:flex-row sm:justify-end">

                    <button type="button" wire:click="$set('showModalTolak', false)"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        Batal
                    </button>

                    <button type="button" wire:click="tolak" wire:loading.attr="disabled" wire:target="tolak"
                        class="inline-flex items-center justify-center rounded-xl bg-red-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60">

                        <span wire:loading.remove wire:target="tolak">
                            Tolak Pengajuan
                        </span>

                        <span wire:loading wire:target="tolak">
                            Memproses...
                        </span>

                    </button>

                </div>

            </div>
        </div>
    @endif
    @if ($showModalRevisi)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 px-4"
            wire:click.self="$set('showModalRevisi', false)">
            <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">

                {{-- Header Modal --}}
                <div class="border-b border-slate-200 px-5 py-4">
                    <h3 class="text-base font-bold text-slate-800">
                        Minta Revisi Pengajuan
                    </h3>

                    <p class="mt-1 text-sm text-slate-500">
                        Berikan alasan atau bagian yang perlu diperbaiki oleh pemohon.
                    </p>
                </div>

                {{-- Isi Modal --}}
                <div class="px-5 py-5">

                    <label for="alasanRevisi" class="block text-sm font-semibold text-slate-700">
                        Alasan Revisi
                    </label>

                    <textarea id="alasanRevisi" wire:model="alasanRevisi" rows="5"
                        placeholder="Contoh: Mohon perbaiki alamat sesuai dengan data KTP."
                        class="mt-2 block w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-amber-500 focus:ring-amber-500"></textarea>

                    @error('alasanRevisi')
                        <p class="mt-2 text-sm text-red-600">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- Footer Modal --}}
                <div class="flex items-center justify-end gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4">

                    <button type="button" wire:click="$set('showModalRevisi', false)"
                        class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        Batal
                    </button>

                    <button type="button" wire:click="mintaRevisi" wire:loading.attr="disabled"
                        wire:target="mintaRevisi"
                        class="inline-flex items-center justify-center rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-600 disabled:cursor-not-allowed disabled:opacity-60">
                        <span wire:loading.remove wire:target="mintaRevisi">
                            Kirim Revisi
                        </span>

                        <span wire:loading wire:target="mintaRevisi">
                            Memproses...
                        </span>
                    </button>

                </div>

            </div>
        </div>
    @endif
</div>

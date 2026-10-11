<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-wrap items-start justify-between gap-4">
        <div>
            <a href="{{ route('masyarakat.surat.riwayat') }}" wire:navigate
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 transition hover:text-blue-700">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M10.5 19.5L3 12l7.5-7.5M3 12h18" />
                </svg>
                Kembali ke Riwayat
            </a>

            <h1 class="mt-2 text-2xl font-bold text-slate-800">
                Detail Pengajuan Surat
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Pantau status dan riwayat pengajuan surat Anda.
            </p>
        </div>

        <span
            class="inline-flex shrink-0 items-center gap-1.5 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
            {{ $statusLabel }}
        </span>
    </div>

    {{-- Data Pemohon --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Data Pemohon
            </h2>
        </div>

        <div class="grid gap-5 px-6 py-6 md:grid-cols-2">

            <div>
                <p class="text-sm text-slate-500">
                    Nama
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pengajuan->penduduk->nama }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">
                    NIK
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pengajuan->penduduk->nik }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">
                    Nomor KK
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pengajuan->penduduk->no_kk }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">
                    Alamat
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pengajuan->penduduk->alamat }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">
                    RT / RW
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pengajuan->penduduk->rt }} / {{ $pengajuan->penduduk->rw }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">
                    Dusun
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pengajuan->penduduk->dusun }}
                </p>
            </div>

        </div>
    </div>

    {{-- Detail Pengajuan --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Detail Pengajuan
            </h2>
        </div>

        <div class="grid gap-5 px-6 py-6 md:grid-cols-2">

            <div>
                <p class="text-sm text-slate-500">
                    Jenis Surat
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ ucwords(str_replace('_', ' ', $pengajuan->jenis_surat)) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">
                    Tanggal Pengajuan
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pengajuan->created_at->format('d M Y, H:i') }}
                </p>
            </div>

            @if ($pengajuan->nomor_surat)
                <div>
                    <p class="text-sm text-slate-500">
                        Nomor Surat
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $pengajuan->nomor_surat }}
                    </p>
                </div>
            @endif

            @if ($pengajuan->tanggal_selesai)
                <div>
                    <p class="text-sm text-slate-500">
                        Tanggal Selesai
                    </p>

                    <p class="mt-1 font-semibold text-slate-800">
                        {{ $pengajuan->tanggal_selesai->format('d M Y') }}
                    </p>
                </div>
            @endif

            <div class="md:col-span-2">
                <p class="text-sm text-slate-500">
                    Keperluan
                </p>

                <p class="mt-1 text-sm leading-6 text-slate-600">
                    {{ $pengajuan->keperluan }}
                </p>
            </div>

        </div>

        @if (
            $pengajuan->status === \App\Enums\PengajuanSuratStatus::REVISI ||
                $pengajuan->status === \App\Enums\PengajuanSuratStatus::DITOLAK)
            <div class="mx-6 mb-6 rounded-xl border border-amber-200 bg-amber-50 p-4">
                <div class="flex items-start gap-3">
                    <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100">
                        <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 9v3.5M12 16h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z" />
                        </svg>
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-semibold text-amber-800">
                            @if ($pengajuan->status === \App\Enums\PengajuanSuratStatus::REVISI)
                                Perlu Perbaikan
                            @else
                                Pengajuan Ditolak
                            @endif
                        </p>

                        @if ($pengajuan->catatan)
                            <p class="mt-1 text-sm leading-6 text-amber-700">
                                {{ $pengajuan->catatan }}
                            </p>
                        @else
                            <p class="mt-1 text-sm text-amber-700">
                                Tidak ada catatan dari Perangkat Desa.
                            </p>
                        @endif
                    </div>
                </div>

                @if ($pengajuan->status === \App\Enums\PengajuanSuratStatus::REVISI)
                    <div class="mt-4 border-t border-amber-200 pt-4">
                        <a href="{{ $this->routePerbaikan() }}" wire:navigate
                            class="inline-flex items-center gap-2 rounded-xl bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-amber-700 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                    d="M15.5 5.5l3 3M4 20h3.5L18.5 9a2.12 2.12 0 000-3l-.5-.5a2.12 2.12 0 00-3 0L4 16.5V20z" />
                            </svg>
                            Perbaiki Pengajuan
                        </a>
                    </div>
                @endif
            </div>
        @endif

        @if ($pengajuan->status === \App\Enums\PengajuanSuratStatus::SELESAI && $pengajuan->file_pdf)
            <div class="mx-6 mb-6 border-t border-slate-100 pt-5">
                <div class="flex flex-wrap items-center gap-3">

                    <a href="{{ route('perangkat-desa.pelayanan-surat.pdf', $pengajuan) }}" target="_blank"
                        class="inline-flex items-center gap-2 rounded-xl bg-blue-800 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z" />
                            <circle cx="12" cy="12" r="2.5" stroke="currentColor" stroke-width="1.8" />
                        </svg>
                        Lihat Surat
                    </a>

                    <a href="{{ route('perangkat-desa.pelayanan-surat.pdf.download', $pengajuan) }}" download
                        class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M12 3.5v11M7.5 10.5L12 15l4.5-4.5M5 19.5h14" />
                        </svg>
                        Download Surat
                    </a>

                </div>
            </div>
        @endif

    </div>

    {{-- Riwayat Status --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Riwayat Status
            </h2>
        </div>

        <div class="px-6 py-6">
            @include('livewire.components.pengajuan-timeline', [
                'histori' => $pengajuan->statusHistories,
            ])
        </div>

    </div>

</div>
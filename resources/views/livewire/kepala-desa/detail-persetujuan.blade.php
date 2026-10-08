@php
    use Illuminate\Support\Facades\Storage;
@endphp

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-slate-800">
            Detail Persetujuan Surat
        </h1>

        <p class="mt-1 text-sm text-slate-500">
            Periksa surat sebelum melakukan tanda tangan.
        </p>
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
                    Jenis Surat
                </p>

                <p class="mt-1 font-semibold text-slate-800">
                    {{ $pengajuan->jenis_surat }}
                </p>
            </div>

        </div>
    </div>

    {{-- Informasi Pengajuan --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Informasi Pengajuan
            </h2>
        </div>

        <div class="space-y-5 px-6 py-6">

            <div>
                <p class="text-sm text-slate-500">
                    Keperluan
                </p>

                <p class="mt-1 text-slate-700">
                    {{ $pengajuan->keperluan }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">
                    Tanggal Pengajuan
                </p>

                <p class="mt-1 font-medium text-slate-700">
                    {{ $pengajuan->created_at?->format('d F Y H:i') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-slate-500">
                    Status
                </p>

                <span
                    class="mt-1 inline-flex items-center rounded-full
                             bg-purple-100 px-3 py-1
                             text-xs font-semibold text-purple-700">
                    Menunggu Tanda Tangan
                </span>
            </div>

        </div>
    </div>

    {{-- Surat PDF --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Surat
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Surat yang telah dibuat oleh Perangkat Desa.
            </p>
        </div>

        <div class="px-6 py-6">

            @if ($pengajuan->file_pdf)
                <div class="overflow-hidden rounded-xl border border-slate-200">
                    <iframe src="{{ route('perangkat-desa.pelayanan-surat.pdf', $pengajuan) }}" class="h-[700px] w-full">
                    </iframe>
                </div>
            @else
                <div class="rounded-xl border border-amber-200 bg-amber-50 px-5 py-5">
                    <p class="text-sm font-medium text-amber-800">
                        File surat belum tersedia.
                    </p>
                </div>
            @endif

        </div>
    </div>

    {{-- Persetujuan Kepala Desa --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-5">
            <h2 class="text-lg font-semibold text-slate-800">
                Persetujuan Kepala Desa
            </h2>

            <p class="mt-1 text-sm text-slate-500">
                Pastikan data dan isi surat sudah benar sebelum melakukan tanda tangan.
            </p>
        </div>

        <div class="px-6 py-6">

            @if ($pengajuan->status === \App\Enums\PengajuanSuratStatus::MENUNGGU_TANDA_TANGAN)
                <div class="rounded-xl border border-purple-200 bg-purple-50 px-5 py-5">

                    <div class="flex items-start gap-3">

                        <div
                            class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-purple-100">
                            <svg class="h-5 w-5 text-purple-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.5-9.5a2.121 2.121 0 013 3L12 16l-4 1 1-4 7.5-7.5z" />
                            </svg>
                        </div>

                        <div>
                            <p class="font-semibold text-purple-800">
                                Surat Menunggu Tanda Tangan
                            </p>

                            <p class="mt-1 text-sm leading-6 text-purple-700">
                                Periksa kembali surat di atas. Jika seluruh data sudah benar,
                                Anda dapat melakukan tanda tangan untuk menyelesaikan pengajuan.
                            </p>
                        </div>

                    </div>

                    <div class="mt-5 flex justify-end">
                        <button type="button" wire:click="bukaModalTandaTangan" wire:loading.attr="disabled"
                            wire:target="tandaTangani"
                            class="inline-flex items-center gap-2 rounded-xl !bg-purple-600 px-5 py-2.5 text-sm font-semibold !text-white transition hover:!bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60">

                            <svg wire:loading.remove wire:target="tandaTangani" class="h-5 w-5" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536M9 11l-1 1 3 3 1-1m-8 4h16" />
                            </svg>

                            <svg wire:loading wire:target="tandaTangani" class="h-5 w-5 animate-spin" fill="none"
                                viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>

                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>

                            <span wire:loading.remove wire:target="tandaTangani">
                                Tanda Tangani Surat
                            </span>

                            <span wire:loading wire:target="tandaTangani">
                                Memproses...
                            </span>

                        </button>
                    </div>

                </div>
            @elseif ($pengajuan->status === \App\Enums\PengajuanSuratStatus::SELESAI)
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-5">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100">
                            <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </div>

                        <div>
                            <p class="font-semibold text-emerald-800">
                                Surat Berhasil Ditandatangani
                            </p>

                            <p class="mt-1 text-sm text-emerald-700">
                                Pengajuan surat telah selesai diproses.
                            </p>
                        </div>

                    </div>

                </div>
            @endif

        </div>
    </div>
    {{-- Modal Konfirmasi Tanda Tangan --}}
    @if ($showModalTandaTangan)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
            wire:click.self="$set('showModalTandaTangan', false)">
            <div class="w-full max-w-md overflow-hidden rounded-2xl bg-white shadow-2xl">

                {{-- Header --}}
                <div class="border-b border-slate-200 px-6 py-5">
                    <h2 class="text-lg font-bold text-slate-800">
                        Konfirmasi Tanda Tangan
                    </h2>

                    <p class="mt-1 text-sm text-slate-500">
                        Pastikan Anda sudah memeriksa seluruh isi surat sebelum
                        melanjutkan.
                    </p>
                </div>

                {{-- Content --}}
                <div class="px-6 py-6">

                    <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-4">
                        <div class="flex items-start gap-3">

                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-600" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01M10.29 3.86l-7.82 14a2 2 0 001.74 3h15.58a2 2 0 001.74-3l-7.82-14a2 2 0 00-3.42 0z" />
                            </svg>

                            <p class="text-sm leading-6 text-amber-800">
                                Setelah surat ditandatangani, pengajuan akan
                                berubah menjadi <strong>Selesai</strong>.
                                Pastikan data dan isi surat sudah benar.
                            </p>

                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="flex justify-end gap-3 border-t border-slate-200 bg-slate-50 px-6 py-4">

                    <button type="button" wire:click="$set('showModalTandaTangan', false)"
                        class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">
                        Batal
                    </button>

                    <button type="button" wire:click="tandaTangani" wire:loading.attr="disabled"
                        wire:target="tandaTangani"
                        class="inline-flex items-center gap-2 rounded-xl !bg-purple-600 px-5 py-2.5 text-sm font-semibold !text-white transition hover:!bg-purple-700 disabled:cursor-not-allowed disabled:opacity-60">
                        <span wire:loading.remove wire:target="tandaTangani">
                            Ya, Tanda Tangani
                        </span>

                        <span wire:loading wire:target="tandaTangani">
                            Memproses...
                        </span>
                    </button>

                </div>

            </div>
        </div>
    @endif

</div>

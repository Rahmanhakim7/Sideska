<div class="min-h-screen bg-slate-50">
    <div class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-6 py-5">
            <div class="flex items-center gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center
                           rounded-xl bg-blue-900 shadow-sm">
                    <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                            d="M3 10.5L12 3l9 7.5M5 9.5V20a1 1 0 001 1h12a1 1 0 001-1V9.5M9 21v-6h6v6" />
                    </svg>
                </div>

                <div>
                    <h1 class="text-xl font-bold text-slate-800">
                        Ajukan Surat
                    </h1>

                    <p class="mt-0.5 text-sm text-slate-500">
                        Pilih jenis surat yang ingin Anda ajukan
                    </p>
                </div>

            </div>

        </div>
    </div>
    <main class="mx-auto max-w-7xl px-6 py-8">
        <div class="mb-7 rounded-xl border border-blue-100 bg-blue-50 px-5 py-4">
            <div class="flex gap-3">
                <div class="mt-0.5 shrink-0">
                    <svg class="h-5 w-5 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M12 22a10 10 0 100-20 10 10 0 000 20z" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-blue-900">
                        Pengajuan Surat Desa
                    </p>

                    <p class="mt-1 text-sm leading-6 text-blue-800">
                        Silakan pilih jenis surat yang Anda butuhkan.
                        Pastikan data yang digunakan sudah sesuai sebelum
                        mengajukan permohonan.
                    </p>
                </div>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            <div
                class="flex min-h-[260px] flex-col rounded-2xl border
                       border-slate-200 bg-white shadow-sm
                       transition hover:-translate-y-0.5 hover:shadow-md">
                <div class="flex-1 p-6">
                    <div
                        class="flex h-12 w-12 items-center justify-center
                               rounded-xl bg-blue-50 text-blue-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <h2 class="mt-5 text-base font-bold text-slate-800">
                        Surat Keterangan Domisili
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Surat keterangan yang menerangkan tempat tinggal
                        penduduk.
                    </p>

                </div>

                <div class="border-t border-slate-100 p-5">
                    <a href="{{ route('masyarakat.ajukan-surat.domisili') }}" wire:navigate
                        class="flex w-full items-center justify-center gap-2
               rounded-xl bg-blue-900 px-4 py-2.5
               text-sm font-semibold text-white
               transition hover:bg-blue-800
               active:bg-blue-950
               focus:outline-none focus:ring-2
               focus:ring-blue-600 focus:ring-offset-2">
                        Ajukan Surat

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <div
                class="flex min-h-[260px] flex-col rounded-2xl border
                       border-slate-200 bg-white shadow-sm
                       transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex-1 p-6">

                    <div
                        class="flex h-12 w-12 items-center justify-center
                               rounded-xl bg-blue-50 text-blue-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <h2 class="mt-5 text-base font-bold text-slate-800">
                        Surat Keterangan Tidak Mampu
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Surat keterangan yang digunakan sebagai dokumen
                        pendukung untuk keperluan tertentu.
                    </p>

                </div>
                <div class="border-t border-slate-100 p-5">

                    <a href="{{ route('masyarakat.ajukan-surat.sktm') }}" wire:navigate
                        class="flex w-full items-center justify-center gap-2
               rounded-xl bg-blue-900 px-4 py-2.5
               text-sm font-semibold text-white
               transition hover:bg-blue-800
               active:bg-blue-950
               focus:outline-none focus:ring-2
               focus:ring-blue-600 focus:ring-offset-2">

                        Ajukan Surat

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            </div>

            <div
                class="flex min-h-[260px] flex-col rounded-2xl border
                       border-slate-200 bg-white shadow-sm
                       transition hover:-translate-y-0.5 hover:shadow-md">

                <div class="flex-1 p-6">

                    <div
                        class="flex h-12 w-12 items-center justify-center
                               rounded-xl bg-blue-50 text-blue-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6l5 5v11a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <h2 class="mt-5 text-base font-bold text-slate-800">
                        Surat Keterangan Usaha
                    </h2>

                    <p class="mt-2 text-sm leading-6 text-slate-500">
                        Surat keterangan yang menerangkan bahwa penduduk
                        memiliki kegiatan atau usaha.
                    </p>

                </div>

                <div class="border-t border-slate-100 p-5">
                    <a href="{{ route('masyarakat.ajukan-surat.usaha') }}" wire:navigate
                        class="flex w-full items-center justify-center gap-2
                               rounded-xl bg-blue-900 px-4 py-2.5
                               text-sm font-semibold text-white
                               transition hover:bg-blue-800
                               active:bg-blue-950
                               focus:outline-none focus:ring-2
                               focus:ring-blue-600 focus:ring-offset-2">
                        Ajukan Surat

                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

            </div>

        </div>
    </main>
</div>

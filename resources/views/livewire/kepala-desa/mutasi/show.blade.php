<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="flex items-center gap-2">
            <a href="{{ route('kepala.mutasi.index') }}"
                class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                title="Kembali">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-slate-800">
                    Detail Mutasi Penduduk
                </h1>
                <p class="mt-1 text-sm text-slate-500">
                    Informasi lengkap event mutasi penduduk (read-only).
                </p>
            </div>
        </div>
        <a href="{{ route('kepala.mutasi.index') }}"
            class="inline-flex w-fit items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-bold text-slate-800">
                Informasi Penduduk
            </h2>
            <p class="mt-0.5 text-sm text-slate-500">
                Data penduduk terkait mutasi.
            </p>
        </div>
        <div class="grid grid-cols-1 gap-x-8 gap-y-5 px-6 py-6 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Nama
                </dt>
                <dd class="mt-1 text-sm font-semibold text-slate-700">
                    {{ $mutasiPenduduk->penduduk?->nama ?? '-' }}
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    NIK
                </dt>
                <dd class="mt-1 text-sm font-semibold text-slate-700">
                    {{ $mutasiPenduduk->penduduk?->nik ?? '-' }}
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Nomor KK
                </dt>
                <dd class="mt-1 text-sm font-semibold text-slate-700">
                    {{ $mutasiPenduduk->penduduk?->no_kk ?? '-' }}
                </dd>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-bold text-slate-800">
                Informasi Mutasi
            </h2>
            <p class="mt-0.5 text-sm text-slate-500">
                Detail event mutasi.
            </p>
        </div>
        <div class="grid grid-cols-1 gap-x-8 gap-y-5 px-6 py-6 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Jenis Mutasi
                </dt>
                <dd class="mt-1 text-sm font-semibold text-slate-700">
                    {{ $mutasiPenduduk->jenis->label() }}
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Tanggal Mutasi
                </dt>
                <dd class="mt-1 text-sm font-semibold text-slate-700">
                    {{ $mutasiPenduduk->tanggal_mutasi?->translatedFormat('d F Y') ?? '-' }}
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Alasan
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $mutasiPenduduk->alasan ?? '-' }}
                </dd>
            </div>
            <div class="sm:col-span-2 lg:col-span-3">
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Catatan
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $mutasiPenduduk->catatan ?? '-' }}
                </dd>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-bold text-slate-800">
                Alamat
            </h2>
            <p class="mt-0.5 text-sm text-slate-500">
                Alamat asal dan alamat tujuan mutasi.
            </p>
        </div>
        <div class="grid grid-cols-1 gap-x-8 gap-y-5 px-6 py-6 lg:grid-cols-2">
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Alamat Asal
                </dt>
                <dd class="mt-1 text-sm leading-6 text-slate-700">
                    {{ $mutasiPenduduk->alamat_asal ?? '-' }}
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Alamat Tujuan
                </dt>
                <dd class="mt-1 text-sm leading-6 text-slate-700">
                    {{ $mutasiPenduduk->alamat_tujuan ?? '-' }}
                </dd>
            </div>
        </div>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-bold text-slate-800">
                Administrasi
            </h2>
            <p class="mt-0.5 text-sm text-slate-500">
                Petugas dan waktu pencatatan mutasi.
            </p>
        </div>
        <div class="grid grid-cols-1 gap-x-8 gap-y-5 px-6 py-6 sm:grid-cols-2 lg:grid-cols-3">
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Petugas yang Mencatat
                </dt>
                <dd class="mt-1 text-sm font-semibold text-slate-700">
                    {{ $mutasiPenduduk->user?->name ?? '-' }}
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Waktu Pencatatan
                </dt>
                <dd class="mt-1 text-sm font-semibold text-slate-700">
                    {{ $mutasiPenduduk->created_at?->translatedFormat('d F Y H:i') ?? '-' }}
                </dd>
            </div>
        </div>
    </div>
</div>

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

        <div>
            <div class="flex items-center gap-2">
                <a href="{{ route('perangkat-desa.penduduk.index') }}"
                    class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                    title="Kembali">

                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>

                </a>

                <h1 class="text-2xl font-bold text-slate-800">
                    Detail Penduduk
                </h1>
            </div>

            <p class="mt-1 text-sm text-slate-500">
                Informasi lengkap data penduduk Desa Sekartejo.
            </p>
        </div>

        <a href="{{ route('perangkat-desa.penduduk.index') }}"
            class="inline-flex w-fit items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">

            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>

            Kembali
        </a>

    </div>


    {{-- PROFIL UTAMA --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="px-6 py-6">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center">

                {{-- FOTO --}}
                <div
                    class="flex h-24 w-24 shrink-0 items-center justify-center overflow-hidden rounded-full bg-blue-100 text-3xl font-bold text-blue-700 ring-4 ring-blue-50">

                    @if ($penduduk->foto)
                        <img src="{{ Storage::url($penduduk->foto) }}" alt="{{ $penduduk->nama }}"
                            class="h-full w-full object-cover">
                    @else
                        {{ strtoupper(substr($penduduk->nama, 0, 1)) }}
                    @endif

                </div>


                {{-- NAMA --}}
                <div class="min-w-0 flex-1">

                    <div class="flex flex-wrap items-center gap-2">

                        <h2 class="text-xl font-bold text-slate-800">
                            {{ $penduduk->nama }}
                        </h2>

                        @if ($penduduk->status_kependudukan === 'Tetap')
                            <span
                                class="inline-flex rounded-full bg-green-100 px-2.5 py-1 text-xs font-medium text-green-700">
                                Tetap
                            </span>
                        @elseif ($penduduk->status_kependudukan === 'Pendatang')
                            <span
                                class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                                Pendatang
                            </span>
                        @elseif ($penduduk->status_kependudukan === 'Pindah')
                            <span
                                class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-medium text-amber-700">
                                Pindah
                            </span>
                        @else
                            <span
                                class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-600">
                                Tidak Diketahui
                            </span>
                        @endif

                    </div>

                    <p class="mt-1 text-sm text-slate-500">
                        NIK: {{ $penduduk->nik }}
                    </p>

                    <p class="mt-0.5 text-sm text-slate-500">
                        No. KK: {{ $penduduk->no_kk }}
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- IDENTITAS --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">
            <h2 class="text-base font-bold text-slate-800">
                Identitas Penduduk
            </h2>

            <p class="mt-0.5 text-sm text-slate-500">
                Informasi dasar identitas penduduk.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-5 px-6 py-6 sm:grid-cols-2 lg:grid-cols-3">

            {{-- NIK --}}
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    NIK
                </dt>
                <dd class="mt-1 text-sm font-semibold text-slate-700">
                    {{ $penduduk->nik }}
                </dd>
            </div>

            {{-- NO KK --}}
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Nomor KK
                </dt>
                <dd class="mt-1 text-sm font-semibold text-slate-700">
                    {{ $penduduk->no_kk }}
                </dd>
            </div>

            {{-- NAMA --}}
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Nama Lengkap
                </dt>
                <dd class="mt-1 text-sm font-semibold text-slate-700">
                    {{ $penduduk->nama }}
                </dd>
            </div>

            {{-- TEMPAT LAHIR --}}
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Tempat Lahir
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->tempat_lahir }}
                </dd>
            </div>

            {{-- TANGGAL LAHIR --}}
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Tanggal Lahir
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->tanggal_lahir ? \Carbon\Carbon::parse($penduduk->tanggal_lahir)->translatedFormat('d F Y') : '-' }}
                </dd>
            </div>

            {{-- JENIS KELAMIN --}}
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Jenis Kelamin
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                </dd>
            </div>

            {{-- AGAMA --}}
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Agama
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->agama }}
                </dd>
            </div>

            {{-- GOLONGAN DARAH --}}
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Golongan Darah
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->golongan_darah ?? '-' }}
                </dd>
            </div>

            {{-- KEWARGANEGARAAN --}}
            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Kewarganegaraan
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->kewarganegaraan }}
                </dd>
            </div>

        </div>

    </div>


    {{-- KELUARGA & PERKAWINAN --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">

            <h2 class="text-base font-bold text-slate-800">
                Keluarga & Perkawinan
            </h2>

            <p class="mt-0.5 text-sm text-slate-500">
                Informasi hubungan keluarga dan status perkawinan.
            </p>

        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-5 px-6 py-6 sm:grid-cols-2 lg:grid-cols-3">

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Hubungan Dalam Keluarga
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->status_hubungan_keluarga }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Status Perkawinan
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->status_perkawinan }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Nama Ayah
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->nama_ayah ?? '-' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Nama Ibu
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->nama_ibu ?? '-' }}
                </dd>
            </div>

        </div>

    </div>


    {{-- PENDIDIKAN & PEKERJAAN --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">

            <h2 class="text-base font-bold text-slate-800">
                Pendidikan & Pekerjaan
            </h2>

            <p class="mt-0.5 text-sm text-slate-500">
                Informasi pendidikan dan pekerjaan penduduk.
            </p>

        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-5 px-6 py-6 sm:grid-cols-2">

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Pendidikan Terakhir
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->pendidikan }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Pekerjaan
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->pekerjaan }}
                </dd>
            </div>

        </div>

    </div>


    {{-- ALAMAT --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">

            <h2 class="text-base font-bold text-slate-800">
                Alamat
            </h2>

            <p class="mt-0.5 text-sm text-slate-500">
                Informasi tempat tinggal penduduk.
            </p>

        </div>

        <div class="px-6 py-6">

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Alamat Lengkap
                </dt>

                <dd class="mt-1 text-sm leading-6 text-slate-700">
                    {{ $penduduk->alamat }}
                </dd>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3">

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        RT
                    </dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-700">
                        {{ $penduduk->rt }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        RW
                    </dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-700">
                        {{ $penduduk->rw }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Dusun
                    </dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-700">
                        {{ $penduduk->dusun }}
                    </dd>
                </div>

            </div>

        </div>

    </div>


    {{-- DATA TAMBAHAN --}}
    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 px-6 py-4">

            <h2 class="text-base font-bold text-slate-800">
                Data Tambahan
            </h2>

            <p class="mt-0.5 text-sm text-slate-500">
                Informasi tambahan penduduk.
            </p>

        </div>

        <div class="grid grid-cols-1 gap-x-8 gap-y-5 px-6 py-6 sm:grid-cols-2 lg:grid-cols-3">

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Nomor HP / WhatsApp
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->no_hp ?? '-' }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Status Kependudukan
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->status_kependudukan }}
                </dd>
            </div>

            <div>
                <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                    Disabilitas
                </dt>
                <dd class="mt-1 text-sm text-slate-700">
                    {{ $penduduk->disabilitas ? 'Ya' : 'Tidak' }}
                </dd>
            </div>

        </div>

    </div>


    {{-- AKUN SIDESKA --}}
    @if ($penduduk->user)
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 px-6 py-4">

                <h2 class="text-base font-bold text-slate-800">
                    Akun SIDESKA
                </h2>

                <p class="mt-0.5 text-sm text-slate-500">
                    Informasi akun pengguna yang terhubung dengan penduduk.
                </p>

            </div>

            <div class="grid grid-cols-1 gap-x-8 gap-y-5 px-6 py-6 sm:grid-cols-2 lg:grid-cols-3">

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Nama Akun
                    </dt>
                    <dd class="mt-1 text-sm font-semibold text-slate-700">
                        {{ $penduduk->user->name }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Email
                    </dt>
                    <dd class="mt-1 text-sm text-slate-700">
                        {{ $penduduk->user->email }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs font-medium uppercase tracking-wide text-slate-400">
                        Role
                    </dt>
                    <dd class="mt-1">
                        <span
                            class="inline-flex rounded-full bg-blue-100 px-2.5 py-1 text-xs font-medium text-blue-700">
                            {{ $penduduk->user->role }}
                        </span>
                    </dd>
                </div>

            </div>

        </div>
    @endif


    {{-- FOOTER ACTION --}}
    <div class="flex justify-end pb-2">

        <a href="{{ route('perangkat-desa.penduduk.index') }}"
            class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">

            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>

            Kembali ke Data Penduduk

        </a>

    </div>

</div>
